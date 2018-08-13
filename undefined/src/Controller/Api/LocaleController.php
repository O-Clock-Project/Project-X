<?php

namespace App\Controller\Api;

use App\Entity\Locale;
use App\Form\LocaleType;
use App\Services\ApiUtils;
use App\Repository\LocaleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api")
 */
class LocaleController extends AbstractController
// route qui commence par /api pour toutes les routes du controller
{
    /**
     * @Route("/locales", name="listLocales", methods="GET")
     */
    public function getLocales(LocaleRepository $localeRepo, Request $request )
    //Méthode permettant de renvoyer la liste de tous les items, avec filtres, ordre pagination et niveau de détail possible
    {
        
        $locale = new Locale; // On instancie un nouvel item temporaire et vide pour disposer de la liste de tous les propriétés possibles
        $utils = new ApiUtils; // On instancie notre service ApiUtils qui va réaliser tous le travail de préparation de la requête 
                               //puis la mise en forme de la réponse reçue au format json
        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->getItems($locale, $localeRepo, $request); 

        return $response; //On retourne la réponse formattée (liste d'items trouvés si réussi, message d'erreur sinon)
    }

    /**
     * @Route("/locales/{id}", name="showLocale", requirements={"id"="\d+"}, methods="GET")
     */
    public function getLocale(LocaleRepository $localeRepo, $id, Request $request)
    //Méthode permettant de renvoyer l'item spécifié par l'id reçue et suivant un niveau de détail demandé
    {
        $utils = new ApiUtils; // On instancie notre service ApiUtils qui va réaliser tous le travail de préparation de la requête 
                               //puis la mise en forme de la réponse reçue au format json
        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->getItem($localeRepo, $id, $request);

        return $response; //On retourne la réponse formattée (item trouvé si réussi, message d'erreur sinon)
    }


    /**
     * @Route("/locales/{id}/{relation}", name="showLocaleRelation", requirements={"id"="\d+", "relation"="[a-z-A-Z]+"}, methods="GET")
     */
    public function getLocaleRelations(LocaleRepository $localeRepo, $id, $relation, Request $request)
    //Méthode permettant de renvoyer les items d'une relation de l'item spécifié par l'id reçue et suivant un niveau de détail demandé
    {
        $utils = new ApiUtils; // On instancie notre service ApiUtils qui va réaliser tous le travail de préparation de la requête 
                               //puis la mise en forme de la réponse reçue au format json
        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->getItemRelations($localeRepo, $id, $request,$relation);

        return $response; //On retourne la réponse formattée (item trouvé si réussi, message d'erreur sinon)
    }

    
    /**
     * @Route("/locales", name="postLocale", methods="POST")
     */
    public function postLocale (Request $request, EntityManagerInterface $em)
    //Méthode permettant de persister un nouvel item à partir des informations reçues dans la requête (payload) et de le renvoyer
    {
        $locale = new Locale(); // On instancie un nouvel item qui va venir être hydraté par les informations fournies dans la requête

        // On crée un formulaire "virtuel" qui va permettre d'utiliser le système de validation des forms Symfony pour checker les données reçues
        // Cf le fichier config/validator/validation.yaml pour les contraintes
        $form = $this->createForm(LocaleType::class, $locale);

        $utils = new ApiUtils; // On instancie notre service ApiUtils qui va réaliser tous le travail de préparation de la requête 
                               //puis la mise en forme de la réponse reçue au format json
        
        // On envoie à ApiUtils les outils et les informations dont il a besoin pour travailler et il nous renvoie une réponse
        $response = $utils->postItem($locale, $form, $request, $em);

        return $response; //On retourne la réponse formattée (item créé si réussi, message d'erreur sinon)
    }
}