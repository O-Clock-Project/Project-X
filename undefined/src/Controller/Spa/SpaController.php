<?php
namespace App\Controller\Spa;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class SpaController extends AbstractController
{
    /**
     * @Route("/app", name="app")
     * @Method("GET")
     */
    public function homepage(EntityManagerInterface $manager)
    {
        $repo = $manager->getRepository('App\Entity\Bookmark');
        $bookmarks = $repo->findAll();
        foreach($bookmarks as $bookmark){
            $bookmark->setVoteScore();
            $manager->persist($bookmark);
        }
        $manager->flush();
        return $this->render('app/app.html.twig', [
        ]);
    }
}