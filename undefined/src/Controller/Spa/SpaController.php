<?php
namespace App\Controller\Spa;

use App\Services\JWTUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\Security\SecurityController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class SpaController extends Controller
{
    /**
     * @Route("/app/{string}/{integer}", name="app")
     * @Method("GET")
     */
    public function homepage(EntityManagerInterface $manager, $string = 'foo', $integer = 1)
    //string et integer avec des valeurs par défaut (sans importance) permettent de s'assurer que quelque soit la page dans React Router
    // au refresh, ça recharge la page /app (et donc React)
    {

        return $this->render('app/app.html.twig', [
        ]);
    }


}