<?php

namespace App\Services;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;



class JWTUtils
// Service qui gère tout ce qui concerne les tokens
{
    private $jwtManager;

    public function __construct(JWTTokenManagerInterface $jwtManager){
        
        $this->jwtManager = $jwtManager;
    }

    public function generateToken($user)
    {


        $token = $this->jwtManager->create($user);


    
        return $token;
    } 

    
}