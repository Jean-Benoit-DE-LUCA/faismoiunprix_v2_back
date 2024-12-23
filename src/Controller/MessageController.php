<?php

namespace App\Controller;

use App\Repository\MessageRepository;
use App\Security\JwtAuthenticator;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MessageController extends AbstractController
{


    /*--- INSERT MESSAGE ---*/

    #[Route('/api/insertmessage', name: 'insert_message')]
    public function insertMessage(Request $request, MessageRepository $messageRepository)
    {   

        $data = json_decode($request->getContent(), true);

        $user_id = null;
        $user_receive_id = null;
        $user_id_offer = null;
        $offer_id = null;
        $product_id = null;
        $inputMessage = null;



        if (isset($data['userId'])) {



            $jwtReceived = str_replace('Bearer ', '', getallheaders()['Authorization']);



            try {

                $jwtResult = JwtAuthenticator::decodeJwt($jwtReceived);

                $user_id = $data['userId'];
                $user_id_offer = $data['userIdOffer'];
                $offer_id = $data['offerId'];
                $product_id = $data['productId'];
                $inputMessage = htmlspecialchars($data['inputMessage']);



                if ($user_id != $user_id_offer) {

                    $user_receive_id = $user_id_offer;
                }

                else if ($user_id == $user_id_offer) {

                    $user_receive_id = $data['productUserId'];
                }




                /* create current date time */

                $dateTimeZone = new \DateTimeZone('Europe/Paris');
                $currentDateTime = new \DateTimeImmutable('now', $dateTimeZone);

                $currentDateTimeFormatDataBase = $currentDateTime->format('Y-m-d H:i:s');

                /* ------------------------ */


                $created_at = $currentDateTimeFormatDataBase;
                $updated_at = $currentDateTimeFormatDataBase;


                $messageRepository->insertMessage($user_id, $user_id_offer, $product_id, $inputMessage, $created_at, $updated_at, $offer_id, 'true', 'false', $user_receive_id);

                $getLastMessageInserted = $messageRepository->getLastMessageInserted($user_id, $user_id_offer, $product_id, $created_at, $offer_id);


                return new JsonResponse([
                    'flag' => true,
                    'message' => 'Message sent successfully',
                    'lastMessageInserted' => $getLastMessageInserted
                ]);



            }

            catch(Exception $e) {

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











    /*--- GET MESSAGE BY OFFER ---*/

    #[Route('/api/getmessagebyoffer', name: 'get_message_by_offer')]
    public function getMessageByOffer(Request $request, MessageRepository $messageRepository) {


        $data = json_decode($request->getContent(), true);

        $offer_id = null;
        $user_id = null;
        $offer_user_offer = null;


        if (isset($data)) {



            if (isset(getallheaders()['Authorization'])) {



                $jwtReceived = str_replace('Bearer ', '', getallheaders()['Authorization']);

                try {

                    JwtAuthenticator::decodeJwt($jwtReceived);



                    $offer_id = $data['offerId'];
                    $user_id = $data['userId'];
                    $offer_user_offer = $data['userOfferId'];

                    $getMessageByOffer = $messageRepository->getMessageByOffer($offer_id, $user_id, $offer_user_offer);





                    foreach ($getMessageByOffer as $key => $value) {

                        $offer_message = htmlspecialchars_decode($value['message_message']);

                        $getMessageByOffer[$key]['message_message'] = $offer_message;
                    }
                    



                    return new JsonResponse([
                        'flag' => true,
                        'result' => $getMessageByOffer
                    ]);
                }




                catch (\Exception $e) {

                    return new JsonResponse([
                        'flag' => false,
                        'message' => 'Expired token'
                    ]);
                }

            }
        }

        return new JsonResponse([
            'flag' => false
        ]);
    }















    /*--- UPDATE MESSAGE USER RECEIVE READ ---*/

    #[Route('/api/updatemessagereceiveread', name: 'update_message_receive_read')]
    public function updateMessageReceiveRead(
        Request $request,
        MessageRepository $messageRepository) {

        $data = json_decode($request->getContent(), true);

        $offer_id = null;
        $user_id = null;



        if (isset($data)) {



            if (isset(getallheaders()['Authorization'])) {


                $jwtReceived = str_replace('Bearer ', '', getallheaders()['Authorization']);



                try {

                    JwtAuthenticator::decodeJwt($jwtReceived);

                    $offer_id = $data['offerId'];
                    $user_id = $data['userId'];





                    $updateMessageReceiveRead = $messageRepository->updateMessageReceiveRead($offer_id, $user_id);





                    // check if there are still messages read set to "false" for a specific offer //

                    $messageReadStillFalse = false;

                    $findMessageByOfferIdUserId = $messageRepository->findMessageByOfferIdUserId($offer_id, $user_id);


                    foreach ($findMessageByOfferIdUserId as $key => $message) {


                        if ($message['message_user_receive_id'] == $user_id) {


                            if ($message['message_user_receive_id_read'] == "false") {

                                $messageReadStillFalse = true;
                                break;
                            }
                        }
                    }










                    // check if there are still messages read set to "false" for all //

                    $messageNotRead = false;

                    $findReceiveMessageByUserId = $messageRepository->findReceiveMessageByUserId($user_id);

                    foreach ($findReceiveMessageByUserId as $key => $message) {

                        if ($message['message_user_receive_id_read'] == 'false') {

                            $messageNotRead = true;
                            break;
                        }
                    }






                    return new JsonResponse([
                        'flag' => true,
                        'messageReadStillFalse' => $messageReadStillFalse,
                        'messageNotRead' => $messageNotRead,
                        'message' => 'Updated successfully'
                    ]);
                }



                catch (\Exception $e) {

                    return new JsonResponse([
                        'flag' => false,
                        'message' => 'Expired token'
                    ]);
                }




            }
        }




        return new JsonResponse([
            'flag' => false
        ]);
    }
















    /*--- CHECK MESSAGE ---*/

    #[Route('/api/checkmessage', name: 'check_message')]
    public function checkMessage(
        Request $request,
        MessageRepository $messageRepository
    ) {






        $data = json_decode($request->getContent(), true);



        if (isset(getallheaders()['Authorization'])) {

            $jwtReceived = str_replace('Bearer ', '', getallheaders()['Authorization']);

            try {



                $user_id = $data['userId'];


                

                // check if there are still messages read set to "false" for all //

                $messageNotRead = false;

                $findReceiveMessageByUserId = $messageRepository->findReceiveMessageByUserId($user_id);

                foreach ($findReceiveMessageByUserId as $key => $message) {

                    if ($message['message_user_receive_id_read'] == 'false') {

                        $messageNotRead = true;
                        break;
                    }
                }



                return new JsonResponse([
                    'flag' => true,
                    'messageNotRead' => $messageNotRead
                ]);
            }

            catch (\Exception $e) {

                return new JsonResponse([
                    'flag' => false
                ]);
            }
        }


        

        return new JsonResponse([
            'flag' => false
        ]);
    }






    /*--- CHECK ROW ENVELOPE ---*/

    #[Route('/api/checkmessagerowenvelope', name: 'check_message_row_envelope')]
    public function checkMessageRowEnvelope(
        Request $request,
        MessageRepository $messageRepository
    ) {

        $data = json_decode($request->getContent(), true);

        if (isset(getallheaders()['Authorization'])) {


            $jwtReceived = str_replace('Bearer ', '', getallheaders()['Authorization']);




            try {

                JwtAuthenticator::decodeJwt($jwtReceived);

                $user_id = $data['userId'];

                $findReceiveMessageByUserId = $messageRepository->findReceiveMessageByUserId($user_id);


                // TODO => CHECK MESSAGE message_user_receive_id_read if "false" and message_user_receive_id

                $result = [];

                foreach($findReceiveMessageByUserId as $key => $value) {

                    if ($value['message_user_receive_id_read'] == 'false' && $value['message_user_receive_id'] == $user_id) {

                        $result[] = $value['message_offer_id'];
                    }
                }

                return new JsonResponse([
                    'flag' => true,
                    'result' => $result
                ]);
            }





            catch (\Exception $e) {

                return new JsonResponse([
                    'flag' => false
                ]);
            }
        }

        return new JsonResponse([
            'flag' => false
        ]);
    }












    #[Route('/api/findreceivemessagebyuserid/{user_id}', name: 'find_receive_message_by_user_id')]
    public function findMessageByUserId(
        $user_id,
        MessageRepository $messageRepository) {

        $findReceiveMessageByUserId = $messageRepository->findReceiveMessageByUserId($user_id);





        foreach ($findReceiveMessageByUserId as $index => $obj) {

            foreach ($obj as $key => $value) {

                if ($key = 'message_message') {

                    $findReceiveMessageByUserId[$index][$key] = htmlspecialchars_decode($findReceiveMessageByUserId[$index][$key]);
                }
            }
        }



        return new JsonResponse([
            'result' => $findReceiveMessageByUserId
        ]);
    }
}
