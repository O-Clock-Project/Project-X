<?php

namespace App\Services;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
//use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
// Pour utiliser les annotations des entités en YAML, pour les groups
use Symfony\Component\Serializer\Mapping\Loader\YamlFileLoader;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;



class ApiUtils
// Service qui me permet de factoriser tout le code qui serait redondant dans mes controllers
{

    public function handleRequestWithParams($object, $repo, $request, $id = null, $relation = null){

        $params = [];
        $order = []; 
        $limit = 20; // 20 résultats retournés par défaut
        $num_pages = 1; // Page 1 par défaut
        $group = 'concise'; // Tous les détails par défaut
        $params['is_active'] = true; // Filtre sur is_active = true par défaut (pour éviter d'avoir à dire à chaque fois qu'on ne veut pas les inactifs)
        if(property_exists($object, 'banned')){
            $params['banned'] = false; 
        }
        

        // Pour chaque entrée dans le tableau query
        foreach($request->query as $key => $value){
            // Si la clé est sortType, tu sors de ce tour de boucle car cette clé ne sert qu'associé à la clé orderField (donc on évite tous les tests inutiles)
            if($key === 'sortType'){
                continue;
            }
            // Si la clé est OrderField et que sortType n'est pas nul, alors on met dans l'array $order sur quel champ trier et on utilise sortType pour le sens de tri
            else if( $key === 'orderField' ){
                $order[$value] = !empty($request->query->get('sortType'))? $request->query->get('sortType') : 'ASC'; //si sortType non renseigné : ASC par défaut
                continue;
            }
            // Si la clé est rowsByPage on change la valeur par défaut de $limit par celle indiquée
            else if($key === 'rowsByPage'){
                $limit = $value;
                continue;
            }
            // Si la clé est pageNumber on change la valeur par défaut du numéro de page et on met la page indiquée
            else if($key === 'pageNumber'){
                $num_pages = $value;
                continue;
            }
            // Si la clé est displayGroup on change la valeur par défaut du niveau de détails affiché par la valeur demandée
            else if($key === 'displayGroup'){
                $group = $value;
                continue;
            }
            // Si la clé correspond à une propriété existante dans l'entité demandée, alors on alimente le tableau $params avec le champ
            // sur lequel on veut filtrer et la valeur recherchée
            else if(property_exists($object, $key)){

                $params[$key] = $value;
            }
            // Si la clé ne correspond à rien d'attendu alors on renvoie un message d'erreur avec le header "mauvaise requête"
            else{
                return array('error' => new JsonResponse(['error' => 'Un critère n\'a pas été trouvé'], Response::HTTP_BAD_REQUEST));
            }

        }
        
        // Si $order est toujours vide après l'analyse de la requête alors on triera sur created_at en DESC par défaut
        if(empty($order)) {
            $order['created_at'] = 'DESC';
        }
    
        //On crée un queryBuilder à partir du Repo reçu (donc de la bonne entité)
        $qb = $repo->createQueryBuilder('obj');
        //Pour chaque item dans $params on ajoute une condition de filtre
        foreach($params as $key => $value){
            $qb->andWhere('obj.'.$key.' = :filterParam'.$key)
            ->setParameter('filterParam'.$key, $value);
        }
        //Pour chaque item dans $order on ajoute un critère de trie
        foreach($order as $key => $value){
            $qb->orderBy('obj.'.$key, $value );
        }
        // Si on a une id reçue (donc !null), c'est qu'on a une jointure à faire
        if ($id !== null){
            $qb->leftJoin('obj.'.$relation, 'objrel') //On va chercher la collection doctrine ( objet_requêté.$relation) et on lui donne l'alias objrel
                ->andWhere('objrel.id = :id') //On ajoute comme condition pour cette jointure que l'id des items dans la collection soit égal à l'id reçu
                ->setParameter('id', $id); //on affecte $id à id pour la ligne au dessus
        }
        $qb->setMaxResults( $limit ); //on applique la limite
        $qb->setFirstResult($limit * ($num_pages - 1)); //Et l'offset pour la pagination

        $objects = $qb->getQuery() //On crée la requête en SQL
                       ->execute(); //Et on l'éxécute

        // Si $objects est vide, on renvoie une erreur 404 et un mesage d'erreur
        if (empty($objects)){
            return array('error' => new JsonResponse(['error' => 'Items non trouvés'], Response::HTTP_NOT_FOUND));
        };
        
        // Si tout va bien, on envoie un array avec les résultats de la requêtes ($objects), le groupe d'affichage ($group) et error à vide puisque ça a marché
        return array('objects' => $objects, 'group' => $group, 'error' => null);
    }




    public function getItems($object, $repo, $request )
    // Méthode qui permet de récupérer tous les items d'une entité, avec filtres, ordre, pagination et niveau de détails configurables
    {

        // je passe les paramètres nécessaires au traitement de la requête et des paramètres demandés
        $result = $this->handleRequestWithParams($object, $repo, $request);

        // je vérifie si j'ai eu une erreur en retour, si oui je la return au controller
        if($result['error'] !== null ){
            return $result['error'];
        }
        // si pas d'erreur je récupère les objets retournés par la requête et le groupe de sérialization
        $objects = $result['objects'];
        $group = $result['group'];
        

        // On passe les objets reçus à la méthode handleSerialization qui s'occupe de transformer tout ça en json
        $jsonContent = $this->handleSerialization($objects, $group);
        // on crée une Réponse avec le code http 200 ("réussite")
        $response =  new Response($jsonContent, 200);
        // On set le header Content-Type sur json et utf-8
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response; //On renvoie la réponse
    }


  


    public function getItem($repo, $id, $request )
    //Méthode qui permet de trouver un item par son id passé dans l'url
    {
        // On cherche avec l'id grace au repo si on trouve l'objet correspondant
        $object = $repo->findOneById($id);
        //dump($object);die;
        // Si $object est vide on retourne une erreur 404 et un message d'erreur
        if (empty($object)){
            return new JsonResponse(['error' => 'Item non trouvé'], Response::HTTP_NOT_FOUND);
        };

        $group = 'concise'; //valeur par défaut de $group
        // Si dans la requête on a la clé displayGroup on met sa value dans $group
        foreach($request->query as $key => $value){
            if($key === 'displayGroup'){
                $group = $value;
            }
        }
        // On passe l'objet reçu à la méthode handleSerialization qui s'occupe de transformer tout ça en json
        $jsonContent = $this->handleSerialization($object, $group);
        // on crée une Réponse avec le code http 200 ("réussite")
        $response =  new Response($jsonContent, 200);
        // On set le header Content-Type sur json et utf-8
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response; //On renvoie la réponse
    }


  

    public function getItemRelations($id, $child, $relation, $em, $request )
    //Méthode qui permet de trouver un item par son id passé dans l'url et d'aller chercher les éléments de la relation spécifiée (avec filtres, etc comme getItems)
    {
        $childClass= substr(ucfirst($child),0,-1);
        if(substr($childClass,-2) === 'ie'){
            $childClass = substr($childClass, 0, -2).'y';
        }
        //On va chercher la classe de l'entité-enfant reçue
        $childClass = 'App\Entity\\' .$childClass; //on met la première lettre en majuscule et on enlève le s à la fin
        $childObject = new $childClass; // On instancie un objet vide à partir 
        $childObjectRepo = $em->getRepository($childClass); // On récupère le repo correspondant à l'entité-enfant pour faire la requête


  
        $result = $this->handleRequestWithParams($childObject, $childObjectRepo, $request, $id, $relation);
        

        // je vérifie si j'ai eu une erreur en retour, si oui je la return au controller
        if($result['error'] !== null ){
            return $result['error'];
        }
        // si pas d'erreur je récupère les objets retournés par la requête et le groupe de sérialization
        $objects = $result['objects'];
        $group = $result['group'];
        


        // On passe l'objet reçu à la méthode handleSerialization qui s'occupe de transformer tout ça en json
        $jsonContent = $this->handleSerialization($objects, $group);
        // on crée une Réponse avec le code http 200 ("réussite")
        $response =  new Response($jsonContent, 200);
        // On set le header Content-Type sur json et utf-8
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response; //On renvoie la réponse
    }



    public function postItem($object, $form, $request, $em)
    // Méthode permettant de persister un nouvel objet en BDD après avoir fait les tests sur les datas reçus grace au validator des forms symfony
    {

        $form->submit($request->request->all()); // Validation des données par les forms symfony (cf config/validator/validation.yaml et l'EntityType correspondant)

        // Si le "form virtuel" n'est pas valide on renvoie un code http bad request et un message d'erreur
        if(!$form->isValid()){
            return new JsonResponse(['error' => 'Creation impossible'], Response::HTTP_BAD_REQUEST);
        }

        // Si le "form virtuel" est valide, on persiste l'objet en BDD
        if($form->isValid()){
            $em->persist($object);
            $em->flush();

            // On passe l'objet reçu à la méthode handleSerialization qui s'occupe de transformer tout ça en json
            $jsonContent = $this->handleSerialization($object);
            // on crée une Réponse avec le code http 201 ("created")
            $response =  new Response($jsonContent, Response::HTTP_CREATED);
            // On set le header Content-Type sur json et utf-8
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');

            return $response; //On renvoie la réponse
        }
    
    }



    

    public function handleSerialization($toSerialize, $group = 'concise')
    // Méthode qui permet de factoriser toute la partie redondante de sérialization
    {
        //On crée un ClassMetadataFactory qui va aller parcourir les annotations de nos entités
        //$classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        
        // Pour utiliser les annotations des entités en YAML, pour les groups 
        $classMetadataFactory = new ClassMetadataFactory(new YamlFileLoader('config/serialization.yaml'));
        
        //On crée un ObjectNormalizer en lui passant le ClassMetadataFactory 
        //nb: un normalizer est une classe qui est en charge de la transformation d'un objet en un tableau
        $objectNormalizer = new ObjectNormalizer($classMetadataFactory, new CamelCaseToSnakeCaseNameConverter( null , true));

        
        // On dit au normalizer comment gérer les références circulaires (ici en substituant un json {'id': id } à l'objet entier)
        $objectNormalizer->setCircularReferenceHandler(function ($reference) {
            return ['id' => $reference->getId()];
        });
        
        // On prépare l'array $options en lui rajoutant l'option de tenir compte des MaxDepth en annotations
        $options = array(
            'enable_max_depth' => true
        );
        // Si $group n'est pas vide (il est sur "full" par défaut de tt manière) alors on rajoute l'option de filtre sur les groupes avec l'option correspondante
        if(!empty($group)){
            $options['groups'] =  array($group);
        }
        
        // On crée le sérializer en lui passant les normalizers (DateTimeNormalizer en premier pour qu'il puisse prendre la main en priorité sur les dates)
        // et les encoders (on utilise pour le moment seulement JsonEncoder)
        //nb: un encoder est une classe qui est en charge de la transformation de la donnée normalisée (tableau) en une chaîne de caractères (json/xml).
        //nb: un serializer est une classe qui gère des normalizers et des encoders pour réaliser la transformation totale dans un sens ou l'autre
        $serializer = new Serializer(array(new DateTimeNormalizer, $objectNormalizer), array(new JsonEncoder()));

        // On retourne le contenu sérialisé en json
        return $jsonContent = $serializer->serialize($toSerialize, 'json', $options);
    }
}