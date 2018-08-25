<?php
namespace App\Controller\Spa;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class SpaController extends AbstractController
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