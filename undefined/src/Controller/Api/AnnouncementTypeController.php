<?php

namespace App\Controller\Api;

use App\Entity\AnnouncementType;
use App\Repository\AnnouncementTypeRepository;
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
class AnnouncementTypeController extends AbstractController
{
    /**
     * @Route("/announcementTypes", name="ListAnnouncementTypes")
     * @Method("GET")
     */
    public function getAnnouncementTypes(AnnouncementTypeRepository $announcementTypeRepo, Request $request )
    {
        
        $announcementType = new AnnouncementType;
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
            else if(property_exists($announcementType, $key)){
                $params[$key] = $value;
            }
            else{
                return new JsonResponse(['message' => 'Un critère n\'a pas été trouvé'], Response::HTTP_NOT_FOUND);
            }
        }

        if(empty($order)) {
            $order['created_at'] = 'DESC';
        }

        $announcementTypes = $announcementTypeRepo->findBy(
            $params,
            $order,
            intval($limit), // limit
            intval($limit * ($num_pages - 1)) // offset
        );


        $serializer = SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($announcementTypes, 'json', SerializationContext::create()->enableMaxDepthChecks());
        $response =  new Response($jsonContent, 200);
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        return $response;
    }

    /**
     * @Route("/announcementTypes/{announcementType_id}", name="ShowAnnouncementType")
     * @Method("GET")
     */
    public function getAnnouncementType(AnnouncementTypeRepository $announcementTypeRepo, $announcementType_id)
    {
        $announcementType = $announcementTypeRepo->findById($announcementType_id);
        if (empty($announcementType)){
            return new JsonResponse(['message' => 'AnnouncementType non trouvé'], Response::HTTP_NOT_FOUND);
        };
        $serializer = SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($announcementType, 'json', SerializationContext::create()->enableMaxDepthChecks());
        $response =  new Response($jsonContent, 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


}
