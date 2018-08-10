<?php

namespace App\Controller\Api;

use App\Entity\WarningBookmark;
use App\Repository\WarningBookmarkRepository;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializationContext;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api")
 */
class WarningBookmarkController extends AbstractController
{
    /**
     * @Route("/warningBookmarks", name="ListWarningBookmarks")
     * @Method("GET")
     */
    public function getWarningBookmarks(WarningBookmarkRepository $warningBookmarkRepo, Request $request )
    {
        
        $warningBookmark = new WarningBookmark;
        $params = [];
        $order = [];
        $limit = 20;
        $num_pages = 1;
        $params['is_active'] = true;
        foreach($request->query as $key => $value){
            if($key === 'sortType'){
                break;
            }
            else if($key === 'orderField'){
                $order[$value] = $request->query->get('sortType');
            }
            else if($key === 'rowsByPage'){
                $limit = $value;
            }
            else if($key === 'pageNumber'){
                $num_pages = $value;
            }
            else if(property_exists($warningBookmark, $key)){
                $params[$key] = $value;
            }
            else{
                return new JsonResponse(['message' => 'Un critère n\'a pas été trouvé'], Response::HTTP_NOT_FOUND);
            }
        }

        if(empty($order)) {
            $order['created_at'] = 'DESC';
        }

        $warningBookmarks = $warningBookmarkRepo->findBy(
            $params,
            $order,
            intval($limit), // limit
            intval($limit * ($num_pages - 1)) // offset
        );


        $serializer = SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($warningBookmarks, 'json', SerializationContext::create()->enableMaxDepthChecks());
        $response =  new Response($jsonContent, 200);
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        return $response;
    }

    /**
     * @Route("/warningBookmarks/{warningBookmark_id}", name="ShowWarningBookmark")
     * @Method("GET")
     */
    public function getWarningBookmark(WarningBookmarkRepository $warningBookmarkRepo, $warningBookmark_id)
    {
        $warningBookmark = $warningBookmarkRepo->findById($warningBookmark_id);
        if (empty($warningBookmark)){
            return new JsonResponse(['message' => 'WarningBookmark non trouvé'], Response::HTTP_NOT_FOUND);
        };
        $serializer = SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($warningBookmark, 'json', SerializationContext::create()->enableMaxDepthChecks());
        $response =  new Response($jsonContent, 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


}
