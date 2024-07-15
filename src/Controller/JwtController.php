<?php

namespace App\Controller;

use App\Repository\JwtRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class JwtController extends AbstractController
{
    #[Route('/api/deletejwt/{user_id}', name: 'delete_jwt')]
    public function deleteJwt($user_id, JwtRepository $jwtRepository)
    {

        $jwtDelete = $jwtRepository->deleteJwt($user_id);
        
        return new JsonResponse([
            'flag' => true
        ]);
    }
}
