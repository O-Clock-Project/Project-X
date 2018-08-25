<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Form\UserType;
use App\Services\ApiUtils;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api")
 */
class UserController extends AbstractController
// route qui commence par /api pour toutes les routes du controller
{
    /**
     * @Route("/users", name="listUsers", methods="GET")
     */
    public function getUsers(UserRepository $userRepo, Request $request, ApiUtils $utils )
    //Méthode permettant de renvoyer la liste de tous les items, avec filtres, ordre pagination et niveau de détail possible
    {
        
        $user = new User; // On instancie un nouvel item temporaire et vide pour disposer de la liste de tous les propriétés possibles

        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->getItems($user, $userRepo, $request); 

        return $response; //On retourne la réponse formattée (liste d'items trouvés si réussi, message d'erreur sinon)
    }

    /**
     * @Route("/users/{id}", name="showUser", requirements={"id"="\d+"}, methods="GET")
     */
    public function _getUser(UserRepository $userRepo, $id, Request $request, ApiUtils $utils)
    //Méthode permettant de renvoyer l'item spécifié par l'id reçue et suivant un niveau de détail demandé (_get sinon conflit avec getUser)
    {

        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->getItem($userRepo, $id, $request);

        return $response; //On retourne la réponse formattée (item trouvé si réussi, message d'erreur sinon)
    }




    /**
     * @Route("/users/{id}/{child}/{relation}", name="showUserRelation", requirements={"id"="\d+","child"="[a-z-A-Z]+", "relation"="[a-z-A-Z_]+"}, methods="GET")
     */
    public function getUserRelations($id, $relation, $child, Request $request, ApiUtils $utils)
    //Méthode permettant de renvoyer les items d'une relation de l'item spécifié par l'id reçue et suivant un niveau de détail demandé
    {
        

        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->getItemRelations( $id,  $child, $relation , $request);

        return $response; //On retourne la réponse formattée (item trouvé si réussi, message d'erreur sinon)
    }

    /**
     * @Route("/users", name="postUser", methods="POST")
     */
    public function postUser (Request $request, ApiUtils $utils)
    //Méthode permettant de persister un nouvel item à partir des informations reçues dans la requête (payload) et de le renvoyer
    {
        $user = new User(); // On instancie un nouvel item qui va venir être hydraté par les informations fournies dans la requête

        // On crée un formulaire "virtuel" qui va permettre d'utiliser le système de validation des forms Symfony pour checker les données reçues
        // Cf le fichier config/validator/validation.yaml pour les contraintes
        $form = $this->createForm(UserType::class, $user);


        
        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->postItem($user, $form, $request);

        return $response; //On retourne la réponse formattée (item créé si réussi, message d'erreur sinon)
    }

    /**
     * @Route("/users/{id}", name="updateUser", requirements={"id"="\d+"}, methods="PUT")
     */
    public function updateUser ( Request $request, User $user, UserPasswordEncoderInterface $encoder, ApiUtils $utils)
    //Méthode permettant de persister les modifications sur un item existant à partir des informations reçues dans la requête (payload) et de le renvoyer
    {

        // On crée un formulaire "virtuel" qui va permettre d'utiliser le système de validation des forms Symfony pour checker les données reçues
        // Cf le fichier config/validator/validation.yaml pour les contraintes
        $form = $this->createForm(UserType::class, $user);



         // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->updateItem($user, $form, $request, $encoder);

        return $response; //On retourne la réponse formattée (item créé si réussi, message d'erreur sinon)
    }

    /**
     * @Route("/users/{id}", name="deleteUser", requirements={"id"="\d+"}, methods="DELETE")
     */
    public function deleteUser ( Request $request, User $user, ApiUtils $utils)
    //Méthode permettant de persister les modifications sur un item existant à partir des informations reçues dans la requête (payload) et de le renvoyer
    {

        
        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->deleteItem($user, $request);

        return $response; //On retourne la réponse formattée (item créé si réussi, message d'erreur sinon)
    }
}