<?php

namespace App\Controller\Api;

use App\Entity\Tag;
use App\Entity\Locale;
use App\Entity\Support;
use App\Entity\Bookmark;
use App\Entity\Difficulty;
use App\Form\BookmarkType;
use App\Services\ApiUtils;
use App\Services\ApiUtilsTools;
use App\Controller\Api\TagController;
use App\Repository\BookmarkRepository;
use App\Controller\Api\LocaleController;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\Api\SupportController;
use App\Controller\Api\DifficultyController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api")
 */
class BookmarkController extends AbstractController
// route qui commence par /api pour toutes les routes du controller
{
    /**
     * @Route("/bookmarks", name="listBookmarks", methods="GET")
     */
    public function getBookmarks(BookmarkRepository $bookmarkRepo, Request $request, ApiUtils $utils )
    //Méthode permettant de renvoyer la liste de tous les items, avec filtres, ordre pagination et niveau de détail possible
    {
        
        $bookmark = new Bookmark; // On instancie un nouvel item temporaire et vide pour disposer de la liste de tous les propriétés possibles

        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->getItems($bookmark, $bookmarkRepo, $request); 

        return $response; //On retourne la réponse formattée (liste d'items trouvés si réussi, message d'erreur sinon)
    }

    /**
     * @Route("/bookmarks/{id}", name="showBookmark", requirements={"id"="\d+"}, methods="GET")
     */
    public function getBookmark(BookmarkRepository $bookmarkRepo, $id, Request $request, ApiUtils $utils)
    //Méthode permettant de renvoyer l'item spécifié par l'id reçue et suivant un niveau de détail demandé
    {
        $bookmark = $bookmarkRepo->findOneById($id);



      
        $response = $utils->getItem($bookmarkRepo, $id, $request);

        return $response; //On retourne la réponse formattée (item trouvé si réussi, message d'erreur sinon)
    }

    /**
     * @Route("/bookmarks/{id}/{child}/{relation}", name="showBookmarkRelation", requirements={"id"="\d+","child"="[a-z-A-Z]+", "relation"="[a-z-A-Z_]+"}, methods="GET")
     */
    public function getBookmarkRelations( $id, $relation, $child, Request $request, ApiUtils $utils)
    //Méthode permettant de renvoyer les items d'une relation de l'item spécifié par l'id reçue et suivant un niveau de détail demandé
    {
        $bookmark = $bookmarkRepo->findOneById($id);

        

        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->getItemRelations( $id,  $child, $relation , $request);

        return $response; //On retourne la réponse formattée (item trouvé si réussi, message d'erreur sinon)
    }
    

    /**
     * @Route("/bookmarks", name="postBookmark", methods="POST")
     */
    public function postBookmark (Request $request, ApiUtils $utils)
    //Méthode permettant de persister un nouvel item à partir des informations reçues dans la requête (payload) et de le renvoyer
    {
        $bookmark = new Bookmark(); // On instancie un nouvel item qui va venir être hydraté par les informations fournies dans la requête

        // On crée un formulaire "virtuel" qui va permettre d'utiliser le système de validation des forms Symfony pour checker les données reçues
        // Cf le fichier config/validator/validation.yaml pour les contraintes
        $form = $this->createForm(BookmarkType::class, $bookmark);


        
        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->postItem($bookmark, $form, $request);

        return $response; //On retourne la réponse formattée (item créé si réussi, message d'erreur sinon)
    }

    /**
     * @Route("/bookmarks/{id}", name="upadateBookmark", requirements={"id"="\d+"}, methods="PUT")
     */
    public function updateBookmark ( Request $request, Bookmark $bookmark, ApiUtils $utils)
    //Méthode permettant de persister les modifications sur un item existant à partir des informations reçues dans la requête (payload) et de le renvoyer
    {
        

        // $this->denyAccessUnlessGranted('edit', $bookmark);

        // On crée un formulaire "virtuel" qui va permettre d'utiliser le système de validation des forms Symfony pour checker les données reçues
        // Cf le fichier config/validator/validation.yaml pour les contraintes
        $form = $this->createForm(BookmarkType::class, $bookmark);


        
        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->updateItem($bookmark, $form, $request);

        return $response; //On retourne la réponse formattée (item créé si réussi, message d'erreur sinon)
    }

    /**
     * @Route("/bookmarks/{id}", name="deleteBookmark", requirements={"id"="\d+"}, methods="DELETE")
     */
    public function deleteBookmark ( Request $request, Bookmark $bookmark, ApiUtils $utils)
    //Méthode permettant de persister les modifications sur un item existant à partir des informations reçues dans la requête (payload) et de le renvoyer
    {
 
        
        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->deleteItem($bookmark, $request);

        return $response; //On retourne la réponse formattée (item créé si réussi, message d'erreur sinon)
    }

    /**
     * @Route("/filters", name="getFilters", methods="GET")
     */
    public function getFilters (Request $request,ApiUtilsTools $utilsTools)
    //
    {
        $support = new SupportController();
        $supportRepo = $this->getDoctrine()->getRepository(Support::class);
        $supports= $supportRepo->findBy(array(), array('name' => 'ASC'));
        $difficulty = new DifficultyController();
        $difficultyRepo = $this->getDoctrine()->getRepository(Difficulty::class);
        $difficulties= $difficultyRepo->findBy(array(), array('name' => 'ASC'));;
        $locale = new LocaleController();
        $localeRepo = $this->getDoctrine()->getRepository(Locale::class);
        $locales= $localeRepo->findBy(array(), array('name' => 'ASC'));;
        $tag = new TagController();
        $tagRepo = $this->getDoctrine()->getRepository(Tag::class);
        $tags= $tagRepo->findBy(array(), array('label' => 'ASC'));;

        $filters = array(
            'supports' => $supports,
            'difficulties' => $difficulties,
            'locales' => $locales,
            'tags' => $tags
        );
        


        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $jsonContent = $utilsTools->handleSerialization($filters, "filters");


      
        $response =  new Response($jsonContent, Response::HTTP_OK);
        // On set le header Content-Type sur json et utf-8
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response; //On renvoie la réponse

    }





}