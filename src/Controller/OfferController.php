<?php

namespace App\Controller;

use App\Repository\MessageRepository;
use App\Repository\OfferRepository;

use App\Security\JwtAuthenticator;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OfferController extends AbstractController
{

    /*--- GET RECEIVED OFFER BY USER ---*/

    #[Route('/api/getreceivedofferbyuser/{user_id}', name: 'get_received_offer_by_user')]
    public function getReceivedOfferByUser($user_id, 
        OfferRepository $offerRepository,
        MessageRepository $messageRepository)
    {


        if (isset(getallheaders()['Authorization'])) {

            $jwtReceived = str_replace('Bearer ', '', getallheaders()['Authorization']);

                try {

                    JwtAuthenticator::decodeJwt($jwtReceived);

                    $getReceivedOfferByUser = $offerRepository->getReceivedOfferByUser($user_id);

                    $findReceiveMessageByUserId = $messageRepository->findReceiveMessageByUserId($user_id);






                    // check if user_receive_id_read == 'false' //

                    foreach ($getReceivedOfferByUser as $keyOffer => $offer) {

                        $messageNotRead = false;

                        foreach ($findReceiveMessageByUserId as $keyMessage => $message) {
                            
                            if ($offer['offer_id'] == $message['message_offer_id']) {

                                if ($message['message_user_receive_id'] == $user_id && $message['message_user_receive_id_read'] == 'false') {

                                    $messageNotRead = true;
                                    break;
                                }
                            }
                        }

                        $getReceivedOfferByUser[$keyOffer]['message_not_read'] = $messageNotRead;
                    }





                    return new JsonResponse([
                        'flag' => true,
                        'result' => $getReceivedOfferByUser
                    ]);
                }


                catch (\Exception $e) {

                    return new JsonResponse([
                        'flag' => false,
                        'message' => 'Expired token'
                    ]);
                }
        }

        return new JsonResponse([
            'flag' => false
        ]);
    }










    /*--- GET SENT OFFER BY USER ---*/

    #[Route('/api/getsentofferbyuser/{user_id}', name: 'get_sent_offer_by_user')]
    public function getSentOfferByUser($user_id, 
        OfferRepository $offerRepository,
        MessageRepository $messageRepository) 
    {


        if (isset(getallheaders()['Authorization'])) {

            $jwtReceived = str_replace('Bearer ', '', getallheaders()['Authorization']);

                try {

                    JwtAuthenticator::decodeJwt($jwtReceived);

                    $getSendOfferByUser = $offerRepository->getSentOfferByUser($user_id);

                    $findReceiveMessageByUserId = $messageRepository->findReceiveMessageByUserId($user_id);


                    

                    
                    // check if user_receive_id_read == 'false' //

                    foreach ($getSendOfferByUser as $keyOffer => $offer) {

                        $messageNotRead = false;

                        foreach ($findReceiveMessageByUserId as $keyMessage => $message) {
                            
                            if ($offer['offer_id'] == $message['message_offer_id']) {

                                if ($message['message_user_receive_id'] == $user_id && $message['message_user_receive_id_read'] == 'false') {

                                    $messageNotRead = true;
                                    break;
                                }
                            }
                        }

                        $getSendOfferByUser[$keyOffer]['message_not_read'] = $messageNotRead;
                    }




                    return new JsonResponse([
                        'flag' =>  true,
                        'result' => $getSendOfferByUser
                    ]);
                }



                catch (\Exception $e) {

                    return new JsonResponse([
                        'flag' => false,
                        'message' => 'Expired token'
                    ]);
                }
        }

        return new JsonResponse([
            'flag' => false
        ]);
    }










    /*--- INSERT OFFER ---*/

    #[Route('/api/insertoffer', name: 'insert_offer')]
    public function insertOffer(OfferRepository $offerRepository) {


        $input_offer = null;
        $product_id = null;
        $user_id = null;

        $created_at = null;
        $updated_at = null;


        if (isset(getallheaders()['Authorization'])) {

            $jwtReceived = str_replace('Bearer ', '', getallheaders()['Authorization']);

                try {

                    JwtAuthenticator::decodeJwt($jwtReceived);

                    if (isset($_POST['offer'])) {

                        $input_offer = $_POST['offer'];
                        $product_id = $_POST['product_id'];
                        $user_id = $_POST['user_id'];



                        if (strlen($input_offer) > 0) {




                            /* create current date time */

                            $dateTimeZone = new \DateTimeZone('Europe/Paris');
                            $currentDateTime = new \DateTimeImmutable('now', $dateTimeZone);

                            $currentDateTimeFormatDataBase = $currentDateTime->format('Y/m/d H:i:s');

                            /* ------------------------ */


                            $created_at = $currentDateTimeFormatDataBase;
                            $updated_at = $currentDateTimeFormatDataBase;

                            $insertOffer = $offerRepository->insertOffer($product_id, $input_offer, $user_id, $created_at, $updated_at);



                            return new JsonResponse([
                                'flag' => true,
                                'message' => 'Offer transmitted'
                            ]);

                        }
                    }
                }


                catch (\Exception $e) {

                    return new JsonResponse([
                        'flag' => false,
                        'message' => 'Expired token'
                    ]);
                }
        }

        return new JsonResponse([
            'flag' => false,
            'message' => ''
        ]);
    }











    /*--- UPDATE STATUS OFFER ---*/

    #[Route('/api/updateofferstatus', name: 'update_status_offer')]
    public function updateOfferStatus(Request $request, OfferRepository $offerRepository) {

        $data = json_decode($request->getContent(), true);

        $offer_id = null;
        $response = null;



        if (isset(getallheaders()['Authorization'])) {



            $jwtReceived = str_replace('Bearer ', '', getallheaders()['Authorization']);

                try {

                    JwtAuthenticator::decodeJwt($jwtReceived);


                    if (isset($data['offerId'])) {

                        $offer_id = $data['offerId'];
                        $response = $data['response'];




                        /* create current date time */

                        $dateTimeZone = new \DateTimeZone('Europe/Paris');
                        $currentDateTime = new \DateTimeImmutable('now', $dateTimeZone);

                        $currentDateTimeFormatDataBase = $currentDateTime->format('Y/m/d H:i:s');

                        /* ------------------------ */


                        $created_at = $currentDateTimeFormatDataBase;
                        $updated_at = $currentDateTimeFormatDataBase;






                    $updateOfferStatus = $offerRepository->updateOfferStatus($offer_id, $response/*, $updated_at */);

                        if ($response == 'accepted') {

                            return new JsonResponse([
                                'flag' => true,
                                'response' => true,
                                'message' => 'Offer status updated'
                            ]);
                        }

                        else if ($response == 'denied') {

                            return new JsonResponse([
                                'flag' => true,
                                'response' => false,
                                'message' => 'Offer status updated'
                            ]);
                        }
                    }
                }




                catch (\Exception $e) {

                    return new JsonResponse([
                        'flag' => false,
                        'message' => 'Expired token'
                    ]);
                }

        }


        return new JsonResponse([
            'flag' => false,
            'message' => ''
        ]);


    }
}
