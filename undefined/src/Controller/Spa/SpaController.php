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
     * @Route("/", name="app")
     * @Method("GET")
     */
    public function homepage(EntityManagerInterface $manager)
    {

        $user = $this->getUser();
        $JWTUtils = new JWTUtils;
        $token = $JWTUtils->generateToken($user, $this->container->get('lexik_jwt_authentication.jwt_manager'));

        $response = new Response(
            $this->renderView('app/app.html.twig',
            array('token' => $token),200
          ));
          $response->headers->setCookie(new Cookie('BEARER', $token));
      
          return $response;
  
    }


}