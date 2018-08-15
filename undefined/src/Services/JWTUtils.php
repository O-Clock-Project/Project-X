<?php

namespace App\Services;



class JWTUtils
// Service qui gère tout ce qui concerne les tokens
{

    public function generateToken($user, $jwtManager)
    {


        $token = $jwtManager->create($user);


    
        return $token;
    } 
}