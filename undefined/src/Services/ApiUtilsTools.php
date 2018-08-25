<?php

namespace App\Services;

use App\Repository\BookmarkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Mapping\Loader\YamlFileLoader;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;



class ApiUtilsTools
//Service servant de boite à outils pour ApiUtils afin de n'y laisser que la partie gestion du CRUD pour les controllers
{

    private $em;

    public function __construct(EntityManagerInterface $em){
   
        $this->em = $em;
    }

    public function handleRequestWithParams($object, $repo, $request, $id = null, $relation = null)
    //Méthode permettant de gérer de manière générique les demandes complexe GET (excepté pour GetItem qui passe simplement par un findOne)
    //avec gestion des query string et des hiérarchies
    {
        $params = [];
        $order = []; 
        $limit = 20; // 20 résultats retournés par défaut
        $num_pages = 1; // Page 1 par défaut
        $group = 'concise'; // Tous les détails par défaut
        $params['is_active'] = true; // Filtre sur is_active = true par défaut (pour éviter d'avoir à dire à chaque fois qu'on ne veut pas les inactifs)
        //Si la propriété banned existe sur la classe traitée, on initialise le filtre à false par défaut (attention dans la query string, 0 ou 1 sont attendus)
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
            // Si on a reçu un array, c'est pour gérer une relation xxxToMany, donc on met les données dans un array à part pour traitement dans la requête
            else if(is_array($value)){
                $arrayParams[$key] = $value;
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
        if(isset($params)){
            foreach($params as $key => $value){
                $qb->andWhere('obj.'.$key.' = :filterParam'.$key)
                   ->setParameter('filterParam'.$key, $value);
            }
        }
        //Pour chaque item dans $arrayParams on ajoute une condition de filtre qui va vérifier 
        //si au moins une des ids reçues est dans la collection doctrine correspondante des objets à renvoyer (pour relation xxxToMany)
        if(isset($arrayParams)){
            foreach($arrayParams as $key => $value){
                $qb->andWhere(':filterParams'.$key.' MEMBER OF obj.'.$key)
                   ->setParameter('filterParams'.$key, $value);
            }
        }
        //Pour chaque item dans $order on ajoute un critère de tri
        if(isset($order)){
            foreach($order as $key => $value){
                if($key === 'faved_by'){
                    $qb->addSelect('COUNT(u) AS HIDDEN favScore')
                        ->leftJoin('obj.'.$key, 'u')
                        ->orderBy('favScore', $value)
                        ->groupBy('obj');
                }
                else if ($key === 'votes'){
                    $qb->addSelect('SUM(CASE WHEN v.value IS NULL THEN :zero ELSE v.value END) AS HIDDEN votesScore')
                        ->setParameter(':zero', 0)
                        ->leftJoin('obj.'.$key, 'v')
                        ->orderBy('votesScore', $value)
                        ->groupBy('obj');
                    }
                else{
                $qb->orderBy('obj.'.$key, $value );
                }
            }
        }
        // Si on a une id reçue (donc !null), c'est qu'on a une jointure à faire
        if ($id !== null){
            $qb->leftJoin('obj.'.$relation, 'objrel') //On va chercher la collection doctrine ( objet_requêté.$relation) et on lui donne l'alias objrel
                ->andWhere('objrel.id = :id') //On ajoute comme condition pour cette jointure que l'id des items dans la collection soit égal à l'id reçu
                ->setParameter('id', $id); //on affecte $id à id pour la ligne au dessus
        }
        if(isset($limit)){
            $qb->setMaxResults( $limit ); //on applique la limite
            $qb->setFirstResult($limit * ($num_pages - 1)); //Et l'offset pour la pagination
        }

        $objects = $qb->getQuery() //On crée la requête en SQL
                       ->execute(); //Et on l'éxécute

                    
        // Si $objects est vide, on renvoie une erreur 404 et un mesage d'erreur
        if (empty($objects)){
            return array('error' => $this->handleSerialization(['error' => 'Items non trouvés']));
        };

        

        
        // Si tout va bien, on envoie un array avec les résultats de la requêtes ($objects), le groupe d'affichage ($group) et error à vide puisque ça a marché
        return array('objects' => $objects, 'group' => $group, 'error' => null);
    }


    public function handleSerialization($toSerialize, $group = 'concise')
    // Méthode qui permet de factoriser toute la partie redondante de sérialization
    {
        //On crée un ClassMetadataFactory qui va aller parcourir les annotations de nos entités
        $classMetadataFactory = new ClassMetadataFactory(new YamlFileLoader('../config/serialization.yaml'));
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



    public function prepareAddRelationsActions($object, $parametersAsArray)
    //Méthode qui prépare les créations de relation avec d'autres entités de l'objet créé/modifié (dispo en POST/PUT)
    {
        $parentClass = get_class($object); //on récupère la classe de l'objet créé
        $classMethods = get_class_methods($parentClass); //et grace à ça la liste des méthodes de cette classe (on veut les setters et les adders)

        $actionsAsArray = []; //On prépare un array pour recevoir la décomposition des actions sur les relations de l'objet créé
       
            foreach($parametersAsArray['add'] as $addParameter){ //pour chaque paramètres dans le "sac" add
                $id = $addParameter['id']; //on récupère l'id de l'objet qui va être en relation
                $childClass= "App\Entity\\".ucfirst($addParameter['entity']); //on récupère la classe (complete path) pour instancier le repo ensuite
                $property= $addParameter['property']; //on récupère la propriété sur laquelle est basée la relation (au singulier, sans le s, ex: difficulty)
                
                $method = 'set'.ucfirst($property); //On construit la méthode utilisée pour créer la relation, d'abord avec set
                if(!in_array($method, $classMethods)){ //On vérifie que cette méthode setPropriété existe bien dans les méthodes de la classe parente
                    $method = 'add'.ucfirst($property); //Si non, on construit addPropriété
                    if(!in_array($method, $classMethods)){ // On reteste
                        $actionsAddAsArray['error'][] = ['error'=>$property.' non trouvée pour l\'add']; //Si toujours pas, on envoie un message d'erreur
                    }
                }
                $childRepo = $this->em->getRepository($childClass); //On va chercher le repository de la classe "enfante"
                $childObject = $childRepo->findOneById($id); // On va chercher l'objet correspondant à l'id donnée
                if(null===$childObject){ //Si on trouve pas d'objet avec l'id passé, on retourne un message d'erreur
                    $actionsAddAsArray['error'][] = ['error'=>$childClass . ' id '. $id. ' non trouvée pour l\'add'];
                }
                //On remplit notre tableau d'actions pour chaque relation à faire avec l'objet trouvé et la méthode à appliquer pour l'ajouter à l'objet parent
                $actionsAddAsArray[] = array(
                    'child' => $childObject, //Un object instancié
                    'method' => $method, //Un setter/adder
                );
            }
            //On vide notre array de paramètres reçus par la requête de la partie "ajout de relation" 
            // pour que le form puisse correctement être validé pour les autres champs "simples"

        return $actionsAddAsArray;
    }

    public function prepareRemoveRelationsActions($object, $parametersAsArray)
    //Méthode qui prépare les retraits de relation avec d'autres entités de l'objet modifié/supprimé (dispo en PUT/DELETE)
    {
        $parentClass = get_class($object); //on récupère la classe de l'objet créé
        $classMethods = get_class_methods($parentClass); //et grace à ça la liste des méthodes de cette classe (on veut les setters et les adders)

        $actionsRemoveAsArray = []; //On prépare un array pour recevoir la décomposition des actions sur les relations de l'objet créé
       
            foreach($parametersAsArray['remove'] as $removeParameter){ //pour chaque paramètres dans le "sac" add
                $id = $removeParameter['id']; //on récupère l'id de l'objet qui va être en relation
                $childClass= "App\Entity\\".ucfirst($removeParameter['entity']); //on récupère la classe (complete path) pour instancier le repo ensuite
                $property= $removeParameter['property']; //on récupère la propriété sur laquelle est basée la relation (au singulier, sans le s, ex: difficulty)
                
                $method = 'remove'.ucfirst($property); //On construit la méthode utilisée pour créer la relation avec le mot clé remove
                if(!in_array($method, $classMethods)){ //On vérifie que cette méthode setPropriété existe bien dans les méthodes de la classe parente
                    $actionsRemoveAsArray['error'][] = ['error'=>$property.' non trouvée pour le remove']; //Si la méhode n'existe pas, on envoie un message d'erreur
                }
                $childRepo = $this->em->getRepository($childClass); //On va chercher le repository de la classe "enfante"
                $childObject = $childRepo->findOneById($id); // On va chercher l'objet correspondant à l'id donnée
                if(null===$childObject){ //Si on trouve pas d'objet avec l'id passé, on retourne un message d'erreur
                    $actionsRemoveAsArray['error'][] = ['error'=>$childClass . ' id '. $id. ' non trouvée pour le remove'];
                }
                //On remplit notre tableau d'actions pour chaque relation à faire avec l'objet trouvé et la méthode à appliquer pour l'ajouter à l'objet parent
                $actionsRemoveAsArray[] = array(
                    'child' => $childObject, //Un object instancié
                    'method' => $method, //Un setter/adder
                );
            }
            //On vide notre array de paramètres reçus par la requête de la partie "ajout de relation" 
            // pour que le form puisse correctement être validé pour les autres champs "simples"

        return $actionsRemoveAsArray;
    }
}