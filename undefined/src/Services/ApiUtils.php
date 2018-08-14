<?php

namespace App\Services;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;


class ApiUtils
// Service qui me permet de factoriser tout le code qui serait redondant dans mes controllers
{


    public function getItems($object, $repo, $request )
    // Méthode qui permet de récupérer tous les items d'une entité, avec filtres, ordre, pagination et niveau de détails configurables
    {

        $params = [];
        $order = []; 
        $limit = 20; // 20 résultats retournés par défaut
        $num_pages = 1; // Page 1 par défaut
        $group = 'concise'; // Tous les détails par défaut
        $params['is_active'] = true; // Filtre sur is_active = true par défaut (pour éviter d'avoir à dire à chaque fois qu'on ne veut pas les inactifs)
        $params['banned'] = false; 
        
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
                return new JsonResponse(['error' => 'Un critère n\'a pas été trouvé'], Response::HTTP_BAD_REQUEST);
            }
        }
        
        // Si $order est toujours vide après l'analyse de la requête alors on triera sur created_at en DESC par défaut
        if(empty($order)) {
            $order['created_at'] = 'DESC';
        }

        // On envoie la demande au Repo avec tous nos critères
        $objects = $repo->findBy(
            $params, //critères de filtre
            $order, //critères de tri/ordre
            intval($limit), // limite de résultats
            intval($limit * ($num_pages - 1)) // numéro de "page" demandé (pour le décalage/offset)
        );

        // Si $objects est vide, on renvoie une erreur 404 et un mesage d'erreur
        if (empty($objects)){
            return new JsonResponse(['error' => 'Items non trouvés'], Response::HTTP_NOT_FOUND);
        };
              
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
        // Si $object est vide on retourne une erreur 404 et un message d'erreur
        if (empty($object)){
            return new JsonResponse(['error' => 'Item non trouvé'], Response::HTTP_NOT_FOUND);
        };
        // Si dans la requête on a la clé displayGroup on met sa value dans $group
        $group = $request->query->get('displayGroup');
        // On passe l'objet reçu à la méthode handleSerialization qui s'occupe de transformer tout ça en json
        $jsonContent = $this->handleSerialization($object, $group);
        // on crée une Réponse avec le code http 200 ("réussite")
        $response =  new Response($jsonContent, 200);
        // On set le header Content-Type sur json et utf-8
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response; //On renvoie la réponse
    }








    

    public function getItemRelations($repo, $id, $request, $relation )
    //Méthode qui permet de trouver un item par son id passé dans l'url et d'aller chercher les éléments de la relation spécifiée
    {
        // On cherche avec l'id grace au repo si on trouve l'objet correspondant
        $object = $repo->findOneById($id);
        // Si $object est vide on retourne une erreur 404 et un message d'erreur
        if (empty($object)){
            return new JsonResponse(['error' => 'Item non trouvé'], Response::HTTP_NOT_FOUND);
        };

        $_classMethods = get_class_methods(get_class($object));
        $method = 'get' . ucfirst($relation);
        if (!in_array($method, $_classMethods)) {
            $method = substr($method, 0, -1);
            if (!in_array($method, $_classMethods)){
                return new JsonResponse(['error' => 'Relation non trouvée'], Response::HTTP_NOT_FOUND);
            }
        }
        else{
            $relationItems = $object->$method();
        }

        // Si dans la requête on a la clé displayGroup on met sa value dans $group
        $group = $request->query->get('displayGroup');
        // On passe l'objet reçu à la méthode handleSerialization qui s'occupe de transformer tout ça en json
        $jsonContent = $this->handleSerialization($relationItems, $group);
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
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
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