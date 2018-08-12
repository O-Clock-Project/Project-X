<?php

namespace App\Controller\Api;

use App\Entity\Tag;
use App\Form\TagType;
use App\Services\ApiUtils;
use App\Repository\TagRepository;
use JMS\Serializer\SerializerBuilder;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api")
 */
class TagController extends AbstractController
{
    /**
     * @Route("/tags", name="ListTags", methods="GET")
     */
    public function getTags(TagRepository $tagRepo, Request $request )
    {
        
        $tag = new Tag;
        $utils = new ApiUtils;
        $response = $utils->getItems($tag, $tagRepo, $request);

        return $response;
    }

    /**
     * @Route("/tags/{tag_id}", name="ShowTag", requirements={"tag_id"="\d+"}, methods="GET")
     */
    public function getTag(TagRepository $tagRepo, $tag_id)
    {
        $utils = new ApiUtils;
        $response = $utils->getItem($tagRepo, $tag_id);
        return $response;
    }

    /**
     * @Route("/tags", name="PostTag", methods="POST")
     */
    public function postTag (Request $request, EntityManagerInterface $em)
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $utils = new ApiUtils;
        $response = $utils->postItem($tag, $form, $request, $em);
        return $response;
    }
}
