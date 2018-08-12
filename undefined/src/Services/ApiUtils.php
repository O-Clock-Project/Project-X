<?php

namespace App\Services;

use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiUtils
{


    public function getItems($object, $repo, $request )
    {
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
            else if(property_exists($object, $key)){
                $params[$key] = $value;
            }
            else{
                return new JsonResponse(['message' => 'Un critère n\'a pas été trouvé'], Response::HTTP_NOT_FOUND);
            }
        }

        if(empty($order)) {
            $order['created_at'] = 'DESC';
        }

        $objects = $repo->findBy(
            $params,
            $order,
            intval($limit), // limit
            intval($limit * ($num_pages - 1)) // offset
        );
        $serializer = SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($objects, 'json', SerializationContext::create()->enableMaxDepthChecks());
        $response =  new Response($jsonContent, 200);
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');

        return $response;
    }


    public function getItem($repo, $id )
    {
        $object = $repo->findById($id);
        if (empty($object)){
            return new JsonResponse(['error' => 'Item non trouvé'], Response::HTTP_NOT_FOUND);
        };
        $serializer = SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($object, 'json', SerializationContext::create()->enableMaxDepthChecks());
        $response =  new Response($jsonContent, 200);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function postItem($object, $form, $request, $em)
    {

        $form->submit($request->request->all()); // Validation des données

        if($form->isValid()){
            $em->persist($object);
            $em->flush();
            $serializer = SerializerBuilder::create()->build();
            $jsonContent = $serializer->serialize($object, 'json', SerializationContext::create()->enableMaxDepthChecks());
            $response =  new Response($jsonContent, 200);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        else{
            return new JsonResponse(['error' => 'Creation impossible'], Response::HTTP_BAD_REQUEST);
        }
        
    }
}