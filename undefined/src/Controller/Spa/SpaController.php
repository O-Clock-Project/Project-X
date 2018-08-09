<?php
namespace App\Controller\Spa;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class SpaController extends AbstractController
{
    /**
     * @Route("/app", name="app")
     * @Method("GET")
     */
    public function homepage()
    {
        return $this->render('app/app.html.twig', [
        ]);
    }
}