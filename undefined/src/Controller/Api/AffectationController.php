<?php

namespace App\Controller\Api;

use App\Entity\Affectation;
use App\Repository\AffectationRepository;
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
class AffectationController extends AbstractController
{
    /**
     * @Route("/affectations", name="ListAffectations")
     * @Method("GET")
     */
    public function getAffectations(AffectationRepository $AffectationRepo, Request $request )
    {
        
        $affectation = new Affectation;
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
            else if(property_exists($affectation, $key)){
                $params[$key] = $value;
            }
            else{
                return new JsonResponse(['message' => 'Un critère n\'a pas été trouvé'], Response::HTTP_NOT_FOUND);
            }
        }

        if(empty($order)) {
            $order['created_at'] = 'DESC';
        }

        $affectations = $affectationRepo->findBy(
            $params,
            $order,
            intval($limit), // limit
            intval($limit * ($num_pages - 1)) // offset
        );


        $serializer = SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($affectations, 'json', SerializationContext::create()->enableMaxDepthChecks());
        $response =  new Response($jsonContent, 200);
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        return $response;
    }

    /**
     * @Route("/affectations/{affectation_id}", name="ShowAffectation")
     * @Method("GET")
     */
    public function getAffectation(AffectationRepository $affectationRepo, $affectation_id)
    {
        $affectation = $affectationRepo->findById($affectation_id);
        if (empty($affectation)){
            return new JsonResponse(['message' => 'Affectation non trouvé'], Response::HTTP_NOT_FOUND);
        };
        $serializer = SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($affectation, 'json', SerializationContext::create()->enableMaxDepthChecks());
        $response =  new Response($jsonContent, 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


}
