<?php

namespace App\Controller;

use App\Entity\MessageContact;
use App\Repository\MessageContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Security\JwtAuthenticator;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\User;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;

class MessageContactController extends AbstractController
{
    #[Route('/api/insertmessagecontact', name: 'insert_message_contact')]
    public function insertMessageContact(Request $request, 
    MessageContactRepository $messageContactRepository,
    UserRepository $userRepository,
    ProductRepository $productRepository,
    EntityManagerInterface $entityManager)
    {

        $data = json_decode($request->getContent(), true);

        $jwtReceived = null;




        if (isset($data['user_send_id'])) {

            $jwtReceived = str_replace('Bearer ', '', getallheaders()['Authorization']);



            try {

                $jwtResult = JwtAuthenticator::decodeJwt($jwtReceived);



                /* create current date time */

                $dateTimeZone = new \DateTimeZone('Europe/Paris');
                $currentDateTime = new \DateTimeImmutable('now', $dateTimeZone);

                $currentDateTimeFormatDataBase = $currentDateTime->format('Y-m-d H:i:s');

                /* ------------------------ */


                $created_at = $currentDateTimeFormatDataBase;
                $updated_at = $currentDateTimeFormatDataBase;






                $messageContact = new MessageContact();


                $messageContact->setUserProduct($userRepository->findUserByIdObj($data['user_product_id'])[0]);

                $messageContact->setUserSend($userRepository->findUserByIdObj($data['user_send_id'])[0]);
                $messageContact->setUserReceive($userRepository->findUserByIdObj($data['user_receive_id'])[0]);

                $messageContact->setProduct($productRepository->findProductByIdObj($data['product_id'])[0]);



                // generate conversation code if new message OR use current conversation_code //

                $conversation_code = null;

                if (isset($data['conversation_code'])) {

                    $conversation_code = $data['conversation_code'];

                    $findMessageContactByConversationCode = $messageContactRepository->findMessageContactByConversationCode($conversation_code);




                    // put all messages "visible" again if user has "delete" conversation -> it can see it again if new message arrives //

                    foreach ($findMessageContactByConversationCode as $key => $value) {

                        $messageContactStatus = $entityManager->getRepository(MessageContact::class)->find($value['message_contact_id']);

                        $messageContactStatus->setUserReceiveIdStatus('visible');
                        $messageContactStatus->setUserSendIdStatus('visible');

                        $entityManager->flush();
                    }
                }




                else if (!isset($data['conversation_code'])) {

                    $conversation_code = uniqid($data['user_send_id'] . '_');
                }





                
                $messageContact->setConversationCode($conversation_code);

                $messageContact->setMessage(htmlspecialchars($data['message']));
                $messageContact->setCreatedAt($created_at);
                $messageContact->setUpdatedAt($updated_at);

                $messageContact->setUserReceiveIdStatus('visible');
                $messageContact->setUserSendIdStatus('visible');

                $messageContact->setUserReceiveIdRead('false');
                $messageContact->setUserSendIdRead('true');

                $entityManager->persist($messageContact);
                $entityManager->flush();






                return new JsonResponse([
                    'flag' => true,
                    'message' => 'Message sent',
                    'lastMessageInserted' => $messageContactRepository->lastMessageInserted($data['user_send_id'], $data['user_receive_id'], $data['product_id'], $conversation_code, $created_at)
                ]);
            }


            catch (\Exception $e) {

                return new JsonResponse([
                    'flag' => false,
                    'message' => $e->getMessage()
                ]);
            }
        }


        return new JsonResponse([
            'flag' => false
        ]);
    }





    #[Route('/api/findmessagecontactbyuserid/{user_id}', name: 'find_message_contact_by_user_id')]
    public function findMessageContactByUserId(Request $request,
    $user_id,
    MessageContactRepository $messageContactRepository) {

        $findMessageContactByUserId = $messageContactRepository->findMessageContactByUserId($user_id);





        foreach ($findMessageContactByUserId as $index => $obj) {

            foreach ($obj as $key => $value) {

                if ($key = 'message_contact_message') {

                    $findMessageContactByUserId[$index][$key] = htmlspecialchars_decode($findMessageContactByUserId[$index][$key]);
                }
            }
        }





        return new JsonResponse([
            'result' => $findMessageContactByUserId
        ]);
    }













    #[Route('/api/hideconversation', name: 'hide_conversation')]
    public function hideConversation(
        Request $request,
        MessageContactRepository $messageContactRepository
        ) {



        $data = json_decode($request->getContent(), true);

        $conversation_code = null;
        $user_id_action = null;


        $jwtReceived = null;



        if (isset(getallheaders()['Authorization'])) {

            $jwtReceived = str_replace('Bearer ', '', getallheaders()['Authorization']);



            try {

                $jwtResult = JwtAuthenticator::decodeJwt($jwtReceived);


                $conversation_code = $data['conversation_code'];
                $user_id_action = $data['user_id_action'];



                $findMessageContactByConversationCode = $messageContactRepository->findMessageContactByConversationCode($conversation_code);




                foreach ($findMessageContactByConversationCode as $key => $value) {


                    if ($value['message_contact_user_send_id'] == $user_id_action) {

                        $messageContactRepository->updateStatusSendId($value['message_contact_id'], $value['message_contact_user_receive_id_status']);
                    }

                    else if ($value['message_contact_user_receive_id'] == $user_id_action) {

                        $messageContactRepository->updateStatusReceiveId($value['message_contact_id'], $value['message_contact_user_send_id_status']);
                    }
                }






                return new JsonResponse([
                    'flag' => true,
                    'message' => 'Conversation removed successfully'
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
            'flag' => false,
            'message' => false
        ]);
    }













    #[Route('/api/getmessagecontactbyconversationcode/{conversation_code}/user/{user_id}', name: 'get_message_contact_by_conversation_code_user')]
    public function getMessageContactByConversationCode($conversation_code, $user_id,
        MessageContactRepository $messageContactRepository) {




        // if (isset(getallheaders()['Authorization'])) {

        //     $jwtReceived = str_replace('Bearer ', '', getallheaders()['Authorization']);



        //     try {

        //         $jwtResult = JwtAuthenticator::decodeJwt($jwtReceived);




                // find message by conversation code and update read status //

                $findMessageContactByConversationCode = $messageContactRepository->findMessageContactByConversationCode($conversation_code);

                foreach ($findMessageContactByConversationCode as $key => $message) {

                    $updateUserReceiveIdRead = $messageContactRepository->updateUserReceiveIdRead($message['message_contact_id'], $conversation_code, $user_id);
                }







                // check if there are still messages read set to "false" //

                $messageContactReadStillFalse = false;

                $findMessageContactByUserId = $messageContactRepository->findMessageContactByUserId($user_id);

                foreach ($findMessageContactByUserId as $key => $message) {


                    // check only message receive by user_id //

                    if ($message['message_contact_user_receive_id'] == $user_id) {


                        if ($message['message_contact_user_receive_id_read'] == "false") {

                            $messageContactReadStillFalse = true;
                            break;
                        }
                    }
                }





                return new JsonResponse([
                    'flag' => true,
                    'messageContactReadStillFalse' => $messageContactReadStillFalse,
                    'message' => 'Message read status updated'
                ]);
            // }


            // catch (\Exception $e) {

            //     return new JsonResponse([
            //         'flag' => false,
            //         'message' => 'Expired token'
            //     ]);
            // }

        // }

        // return new JsonResponse([
        //     'flag' => false
        // ]);
    }


















    /*--- CHECK MESSAGE CONTACT ---*/

    #[Route('/api/checkmessagecontact', name: 'check_message_contact')]
    public function checkMessageContact(
        Request $request,
        MessageContactRepository $messageContactRepository) {





        $data = json_decode($request->getContent(), true);



        if (isset(getallheaders()['Authorization'])) {

            $jwtReceived = str_replace('Bearer ', '', getallheaders()['Authorization']);

            try {



                $user_id = $data['userId'];


                

                // check if there are still messages read set to "false" //

                $messageContactReadStillFalse = false;

                $findMessageContactByUserId = $messageContactRepository->findMessageContactByUserId($user_id);

                foreach ($findMessageContactByUserId as $key => $message) {


                    // check only message receive by user_id //

                    if ($message['message_contact_user_receive_id'] == $user_id) {


                        if ($message['message_contact_user_receive_id_read'] == 'false') {

                            $messageContactReadStillFalse = true;
                            break;
                        }
                    }
                }



                return new JsonResponse([
                    'flag' => true,
                    'messageContactReadStillFalse' => $messageContactReadStillFalse,
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
}
