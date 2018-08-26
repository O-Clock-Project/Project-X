<?php

namespace App\Controller\Api;

use App\Entity\Vote;
use App\Form\VoteType;
use App\Services\ApiUtils;
use App\Repository\UserRepository;
use App\Repository\VoteRepository;
use App\Repository\BookmarkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api")
 */
class VoteController extends AbstractController
// route qui commence par /api pour toutes les routes du controller
{
    /**
     * @Route("/votes", name="listVotes", methods="GET")
     */
    public function getVotes(VoteRepository $voteRepo, Request $request, ApiUtils $utils )
    //Méthode permettant de renvoyer la liste de tous les items, avec filtres, ordre pagination et niveau de détail possible
    {
        
        $vote = new Vote; // On instancie un nouvel item temporaire et vide pour disposer de la liste de tous les propriétés possibles

        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->getItems($vote, $voteRepo, $request); 

        return $response; //On retourne la réponse formattée (liste d'items trouvés si réussi, message d'erreur sinon)
    }

    /**
     * @Route("/votes/{id}", name="showVote", requirements={"id"="\d+"}, methods="GET")
     */
    public function getVote(VoteRepository $voteRepo, $id, Request $request, ApiUtils $utils)
    //Méthode permettant de renvoyer l'item spécifié par l'id reçue et suivant un niveau de détail demandé
    {

        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->getItem($voteRepo, $id, $request);

        return $response; //On retourne la réponse formattée (item trouvé si réussi, message d'erreur sinon)
    }

    /**
     * @Route("/votes/{id}/{child}/{relation}", name="showVoteRelation", requirements={"id"="\d+","child"="[a-z-A-Z]+", "relation"="[a-z-A-Z_]+"}, methods="GET")
     */
    public function getVoteRelations( $id, $relation, $child, Request $request, ApiUtils $utils)
    //Méthode permettant de renvoyer les items d'une relation de l'item spécifié par l'id reçue et suivant un niveau de détail demandé
    {
        

        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->getItemRelations( $id,  $child, $relation , $request);

        return $response; //On retourne la réponse formattée (item trouvé si réussi, message d'erreur sinon)
    }


    /**
     * @Route("/votes", name="postVote", methods="POST")
     */
    public function postVote (Request $request, ApiUtils $utils, VoteRepository $voteRepo, UserRepository $userRepo, BookmarkRepository $bookmarkRepo)
    //Méthode permettant de persister un nouvel item à partir des informations reçues dans la requête (payload) et de le renvoyer
    {
       $response = $this->checkVote($request, $voteRepo, $userRepo, $bookmarkRepo);
       if($response['exists']){
            return new JsonResponse($response, Response::HTTP_BAD_REQUEST);
        }
        // $voter = $userRepo->findOneById($request['add'][''])
        $vote = new Vote(); // On instancie un nouvel item qui va venir être hydraté par les informations fournies dans la requête

        // On crée un formulaire "virtuel" qui va permettre d'utiliser le système de validation des forms Symfony pour checker les données reçues
        // Cf le fichier config/validator/validation.yaml pour les contraintes
        $form = $this->createForm(VoteType::class, $vote);


        
        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->postItem($vote, $form, $request);

        return $response; //On retourne la réponse formattée (item créé si réussi, message d'erreur sinon)
    }

    /**
     * @Route("/votes/{id}", name="updateVote", requirements={"id"="\d+"}, methods="PUT")
     */
    public function updateVote ( Request $request, Vote $vote, ApiUtils $utils)
    //Méthode permettant de persister les modifications sur un item existant à partir des informations reçues dans la requête (payload) et de le renvoyer
    {

        // On crée un formulaire "virtuel" qui va permettre d'utiliser le système de validation des forms Symfony pour checker les données reçues
        // Cf le fichier config/validator/validation.yaml pour les contraintes
        $form = $this->createForm(VoteType::class, $vote);


        
        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->updateItem($vote, $form, $request);

        return $response; //On retourne la réponse formattée (item créé si réussi, message d'erreur sinon)
    }


    /**
     * @Route("/votes/{id}", name="deleteVote", requirements={"id"="\d+"}, methods="DELETE")
     */
    public function deleteVote ( Request $request, Vote $vote, ApiUtils $utils)
    //Méthode permettant de persister les modifications sur un item existant à partir des informations reçues dans la requête (payload) et de le renvoyer
    {
        
        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->deleteItem($vote, $request);

        return $response; //On retourne la réponse formattée (item créé si réussi, message d'erreur sinon)
    }


    /**
     * @Route("/votes/check", name="checkVote", methods="GET")
     */
    public function checkVote ( Request $request, VoteRepository $voteRepo, UserRepository $userRepo, BookmarkRepository $bookmarkRepo)
    {
        if((null!==$request->query->get('voter'))&& null!==$request->query->get('bookmark')){
            $directRoute = true;
            $voterId = $request->get('voter');
            $bookmarkId=$request->get('bookmark');
        }
        if ($content = $request->getContent()) { //Si requête pas vide, on met dans $content

            $parametersAsArray = json_decode($content, true); //Et on decode en json
            if(isset($parametersAsArray['add'])){
                $directRoute=false;
                foreach($parametersAsArray['add'] as $relation){
                    if($relation['property']==='voter'){
                        $voterId = $relation['id'];
                    }
                    elseif($relation['property']==='bookmark'){
                        $bookmarkId = $relation['id'];
                    }
                }
            }
        }

        $voter = $userRepo->findOneById($voterId);
        $bookmark = $bookmarkRepo->findOneById($bookmarkId);

        $existingVote = $voteRepo->findOneBy(array('voter'=>$voter, 'bookmark'=>$bookmark));
        
        if(!$existingVote){
            $response = array('exists' => false, 'value' => 0);
        }
        elseif($existingVote){
            $response = array('exists' => true, 'value' => $existingVote->getValue());
        }
        
        if($directRoute===true){
            return new JsonResponse($response, Response::HTTP_OK);
        }
        else{
            return $response;
        }
    }


}