<?php

namespace App\Controller;

use App\Repository\JwtRepository;
use App\Repository\SessionDataRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class SessionDataController extends AbstractController
{
    #[Route('/api/getdatasession', name: 'get_data_session')]
    public function getDataSession(Request $request, SessionDataRepository $sessionDataRepository, UserRepository $userRepository, JwtRepository $jwtRepository)
    {

        $data = json_decode($request->getContent(), true);

        $user = null;
        

        $getSessionData = $sessionDataRepository->getSessionData($data['session']);



        if (count($getSessionData) > 0) {

            $user = $userRepository->findUserById($getSessionData[0]['user_id']);



            // get jwt //

            $jwtData = $jwtRepository->getJwt($getSessionData[0]['user_id']);


            if (count($jwtData) > 0) {

                $user[0]['jwt'] = $jwtData[0]['code'];
            }







            if (count($user) > 0) {

                return new JsonResponse([
                    'flag' => true,
                    'user' => $user
                ]);
            }

        }
        

        else {

            return new JsonResponse([
                'flag' => false,
                'user' => $user
            ]);
        }
    }












    /*--- DELETE DATA SESSION FROM USER ---*/

    #[Route('/api/deletedatasession/{user_id}', name: 'delete_data_session')]
    public function deleteDataSession($user_id, SessionDataRepository $sessionDataRepository) {


        $deleteDataSession = $sessionDataRepository->deleteDataSession($user_id);

        return new JsonResponse([
            'flag' => true
        ]);
    }
}
