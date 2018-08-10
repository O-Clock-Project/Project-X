<?php

namespace App\Controller\Api;

use App\Entity\Vote;
use App\Repository\VoteRepository;
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
class VoteController extends AbstractController
{
    /**
     * @Route("/votes", name="ListVotes")
     * @Method("GET")
     */
    public function getVotes(VoteRepository $voteRepo, Request $request )
    {
        
        $vote = new Vote;
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
            else if(property_exists($vote, $key)){
                $params[$key] = $value;
            }
            else{
                return new JsonResponse(['message' => 'Un critère n\'a pas été trouvé'], Response::HTTP_NOT_FOUND);
            }
        }

        if(empty($order)) {
            $order['created_at'] = 'DESC';
        }

        $votes = $voteRepo->findBy(
            $params,
            $order,
            intval($limit), // limit
            intval($limit * ($num_pages - 1)) // offset
        );


        $serializer = SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($votes, 'json', SerializationContext::create()->enableMaxDepthChecks());
        $response =  new Response($jsonContent, 200);
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        return $response;
    }

    /**
     * @Route("/votes/{vote_id}", name="ShowVote")
     * @Method("GET")
     */
    public function getVote(VoteRepository $voteRepo, $vote_id)
    {
        $vote = $voteRepo->findById($vote_id);
        if (empty($vote)){
            return new JsonResponse(['message' => 'Vote non trouvé'], Response::HTTP_NOT_FOUND);
        };
        $serializer = SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($vote, 'json', SerializationContext::create()->enableMaxDepthChecks());
        $response =  new Response($jsonContent, 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


}
