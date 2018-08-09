<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Doctrine\ORM\EntityManagerInterface;

class BaseController extends Controller
{
    /**
     * @Route("/", name="index")
     * @Method("GET")
     */
    public function Index()
    {


        return $this->render('base.html.twig', [
        ]);
    }
}