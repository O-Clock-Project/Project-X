<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Form\UserType;
use App\Services\ApiUtils;
use App\Repository\UserRepository;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api")
 */
class UserController extends AbstractController
// route qui commence par /api pour toutes les routes du controller
{
    /**
     * @Route("/users", name="listUsers", methods="GET")
     */
    public function getUsers(UserRepository $userRepo, Request $request )
    //Méthode permettant de renvoyer la liste de tous les items, avec filtres, ordre pagination et niveau de détail possible
    {
        
        $user = new User; // On instancie un nouvel item temporaire et vide pour disposer de la liste de tous les propriétés possibles
        $utils = new ApiUtils; // On instancie notre service ApiUtils qui va réaliser tous le travail de préparation de la requête 
                               //puis la mise en forme de la réponse reçue au format json
        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->getItems($user, $userRepo, $request); 

        return $response; //On retourne la réponse formattée (liste d'items trouvés si réussi, message d'erreur sinon)
    }

    /**
     * @Route("/users/{id}", name="showUser", requirements={"id"="\d+"}, methods="GET")
     */
    public function _getUser(UserRepository $userRepo, $id, Request $request)
    //Méthode permettant de renvoyer l'item spécifié par l'id reçue et suivant un niveau de détail demandé (_get sinon conflit avec getUser)
    {
        $utils = new ApiUtils; // On instancie notre service ApiUtils qui va réaliser tous le travail de préparation de la requête 
                               //puis la mise en forme de la réponse reçue au format json
        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->getItem($userRepo, $id, $request);

        return $response; //On retourne la réponse formattée (item trouvé si réussi, message d'erreur sinon)
    }




    /**
     * @Route("/users/{id}/{child}/{relation}", name="showUserRelation", requirements={"id"="\d+","child"="[a-z-A-Z]+", "relation"="[a-z-A-Z_]+"}, methods="GET")
     */
    public function getUserRelations(UserRepository $userRepo, $id, $relation, $child, Request $request, EntityManagerInterface $em)
    //Méthode permettant de renvoyer les items d'une relation de l'item spécifié par l'id reçue et suivant un niveau de détail demandé
    {

        $utils = new ApiUtils; // On instancie notre service ApiUtils qui va réaliser tous le travail de préparation de la requête 
                               //puis la mise en forme de la réponse reçue au format json
        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->getItemRelations( $id,  $child, $relation, $em , $request);

        return $response; //On retourne la réponse formattée (item trouvé si réussi, message d'erreur sinon)
    }

    /**
     * @Route("/users", name="postUser", methods="POST")
     */
    public function postUser (Request $request, EntityManagerInterface $em)
    //Méthode permettant de persister un nouvel item à partir des informations reçues dans la requête (payload) et de le renvoyer
    {
        $user = new User(); // On instancie un nouvel item qui va venir être hydraté par les informations fournies dans la requête

        // On crée un formulaire "virtuel" qui va permettre d'utiliser le système de validation des forms Symfony pour checker les données reçues
        // Cf le fichier config/validator/validation.yaml pour les contraintes
        $form = $this->createForm(UserType::class, $user);

        $utils = new ApiUtils; // On instancie notre service ApiUtils qui va réaliser tous le travail de préparation de la requête 
                               //puis la mise en forme de la réponse reçue au format json
        
        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->postItem($user, $form, $request, $em);

        return $response; //On retourne la réponse formattée (item créé si réussi, message d'erreur sinon)
    }

    /**
     * @Route("/rights/{user_id}/promotion/{promotion_id}", name="rightsByProm", requirements={"user_id"="\d+", "promotion_id"="\d+"}, methods="GET")
     */
    public function getUserRoleByPromotion(UserRepository $userRepo, $user_id, PromotionRepository $promotionRepo, $promotion_id, Request $request)
    //Méthode custom permettant de renvoyer les rôles d'un user spécifié dans une promotion en particulier
    {
        $user = $userRepo->findOneById($user_id);
        $promotion = $promotionRepo->findOneById($promotion_id);        
        if (empty($user)){
            return new JsonResponse(['error' => 'User non trouvé'], Response::HTTP_NOT_FOUND);
        };
        if (empty($promotion)){
            return new JsonResponse(['error' => 'Promotion non trouvée'], Response::HTTP_NOT_FOUND);
        };
        $roles = $user->getBestRole($promotion);
        $response = new JsonResponse(
            array(
                'user' => $user->getId(), 
                'promotion' => $promotion->getId(), 
                'best_role' => $roles
        ));


        return $response; //On retourne la réponse formattée (item trouvé si réussi, message d'erreur sinon)
    }

    /**       
     * @Route("/users/{id}", name="updateUser", requirements={"id"="\d+"}, methods="PUT")
     */
    public function updateUser ($id, Request $request, EntityManagerInterface $em, UserRepository $userRepo)
    //Méthode permettant de persister les modifications sur un item existant à partir des informations reçues dans la requête (payload) et de le renvoyer
    {
        $user = $userRepo->findOnebyId($id);

        // On crée un formulaire "virtuel" qui va permettre d'utiliser le système de validation des forms Symfony pour checker les données reçues
        // Cf le fichier config/validator/validation.yaml pour les contraintes
        $form = $this->createForm(UserType::class, $user);

        $utils = new ApiUtils; // On instancie notre service ApiUtils qui va réaliser tous le travail de préparation de la requête 
                               //puis la mise en forme de la réponse reçue au format json
        
        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->updateItem($user, $form, $request, $em);

        return $response; //On retourne la réponse formattée (item créé si réussi, message d'erreur sinon)
    }
}