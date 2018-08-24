<?php

namespace App\Controller\Api;

use App\Entity\Affectation;
use App\Form\AffectationType;
use App\Services\ApiUtils;
use App\Repository\AffectationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api")
 */
class AffectationController extends AbstractController
// route qui commence par /api pour toutes les routes du controller
{
    /**
     * @Route("/affectations", name="listAffectations", methods="GET")
     */
    public function getAffectations(AffectationRepository $affectationRepo, Request $request, ApiUtils $utils )
    //Méthode permettant de renvoyer la liste de tous les items, avec filtres, ordre pagination et niveau de détail possible
    {
        
        $affectation = new Affectation; // On instancie un nouvel item temporaire et vide pour disposer de la liste de tous les propriétés possibles
       
        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->getItems($affectation, $affectationRepo, $request); 

        return $response; //On retourne la réponse formattée (liste d'items trouvés si réussi, message d'erreur sinon)
    }

    /**
     * @Route("/affectations/{id}", name="showAffectation", requirements={"id"="\d+"}, methods="GET")
     */
    public function getAffectation(AffectationRepository $affectationRepo, $id, Request $request)
    //Méthode permettant de renvoyer l'item spécifié par l'id reçue et suivant un niveau de détail demandé
    {
        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->getItem($affectationRepo, $id, $request);

        return $response; //On retourne la réponse formattée (item trouvé si réussi, message d'erreur sinon)
    }

    /**
     * @Route("/affectations/{id}/{child}/{relation}", name="showAffectationRelation", requirements={"id"="\d+","child"="[a-z-A-Z]+", "relation"="[a-z-A-Z_]+"}, methods="GET")
     */
    public function getAffectationRelations(AffectationRepository $affectationRepo, $id, $relation, $child, Request $request,  ApiUtils $utils)
    //Méthode permettant de renvoyer les items d'une relation de l'item spécifié par l'id reçue et suivant un niveau de détail demandé
    {
        
        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->getItemRelations( $id,  $child, $relation , $request);

        return $response; //On retourne la réponse formattée (item trouvé si réussi, message d'erreur sinon)
    }
    

    /**
     * @Route("/affectations", name="postAffectation", methods="POST")
     */
    public function postAffectation (Request $request,  ApiUtils $utils)
    //Méthode permettant de persister un nouvel item à partir des informations reçues dans la requête (payload) et de le renvoyer
    {
        $affectation = new Affectation(); // On instancie un nouvel item qui va venir être hydraté par les informations fournies dans la requête

        // On crée un formulaire "virtuel" qui va permettre d'utiliser le système de validation des forms Symfony pour checker les données reçues
        // Cf le fichier config/validator/validation.yaml pour les contraintes
        $form = $this->createForm(AffectationType::class, $affectation);

        
        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->postItem($affectation, $form, $request);

        return $response; //On retourne la réponse formattée (item créé si réussi, message d'erreur sinon)
    }

    /**
     * @Route("/affectations/{id}", name="updateAffectation", requirements={"id"="\d+"}, methods="PUT")
     */
    public function updateAffectation ($id, Request $request,  AffectationRepository $affectationRepo, ApiUtils $utils)
    //Méthode permettant de persister les modifications sur un item existant à partir des informations reçues dans la requête (payload) et de le renvoyer
    {
        $affectation = $affectationRepo->findOnebyId($id);

        // On crée un formulaire "virtuel" qui va permettre d'utiliser le système de validation des forms Symfony pour checker les données reçues
        // Cf le fichier config/validator/validation.yaml pour les contraintes
        $form = $this->createForm(AffectationType::class, $affectation);

        
        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->updateItem($affectation, $form, $request);

        return $response; //On retourne la réponse formattée (item créé si réussi, message d'erreur sinon)
    }

    /**
     * @Route("/affectations/{id}", name="deleteAffectation", requirements={"id"="\d+"}, methods="DELETE")
     */
    public function deleteAffectation ($id, Request $request, AffectationRepository $affectationRepo, ApiUtils $utils)
    //Méthode permettant de persister les modifications sur un item existant à partir des informations reçues dans la requête (payload) et de le renvoyer
    {
        $affectation = $affectationRepo->findOneById($id);

        
        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->deleteItem($affectation, $request);

        return $response; //On retourne la réponse formattée (item créé si réussi, message d'erreur sinon)
    }
}


