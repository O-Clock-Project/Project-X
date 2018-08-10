<?php

namespace App\Controller\Api;

use App\Entity\Role;
use App\Repository\RoleRepository;
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
class RoleController extends AbstractController
{
    /**
     * @Route("/roles", name="ListRoles")
     * @Method("GET")
     */
    public function getRoles(RoleRepository $roleRepo, Request $request )
    {
        
        $role = new Role;
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
            else if(property_exists($role, $key)){
                $params[$key] = $value;
            }
            else{
                throw new \Exception('Un des critères demandés n\'est pas disponible');
            }
        }
        if(empty($order)) {
            $order['created_at'] = 'DESC';
        }
        $data = $roleRepo->findBy(
            $params,
            $order,
            intval($limit), // limit
            intval($limit * ($num_pages - 1)) // offset
        );
        $serializer = SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($data, 'json', SerializationContext::create()->enableMaxDepthChecks());
        $response =  new Response($jsonContent, 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/roles/{id}", name="ShowRole")
     * @Method("GET")
     */
    public function getRole(Role $role )
    {

        return $this->json($role);
    }


}
