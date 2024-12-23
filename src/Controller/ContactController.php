<?php

namespace App\Controller;

use App\Entity\Contact;
use Config;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;


class ContactController extends AbstractController
{
    #[Route('/api/sendcontact', name: 'send_contact')]
    public function sendContact(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer)
    {

        include_once('../Config.php');

        $data = json_decode($request->getContent(), true);

        $nameContact = null;
        $firstNameContact = null;
        $emailContact = null;
        $textareaContact = null;


        if (isset($data['nameContact']) &&
            isset($data['firstNameContact']) &&
            isset($data['emailContact']) &&
            isset($data['textareaContact'])) {

            

            $nameContact = htmlspecialchars($data['nameContact']);
            $firstNameContact = htmlspecialchars($data['firstNameContact']);
            $emailContact = htmlspecialchars($data['emailContact']);
            $textareaContact = htmlspecialchars($data['textareaContact']);






            if (preg_match('/[@]/', $emailContact) && strlen($textareaContact) >= 20) {


                try {

                    $email = (new Email())
                        ->from(Config::getEmailContact())
                        ->to(Config::getEmailContact())
                        //->cc('cc@example.com')
                        //->bcc('bcc@example.com')
                        ->replyTo($emailContact)
                        //->priority(Email::PRIORITY_HIGH)
                        ->subject('New contact message (Faismoiunprix)')
                        // ->text('Sending emails is fun again!')
                        ->html('<p style="font-size: 1rem; font-family: monospace, \'Times New Roman\', Times, serif;">' . $textareaContact . '</p>');
            
                    $mailer->send($email);


                    // ↓ CODE OK ↓ //

                    $contactObj = new Contact();

                    $contactObj->setName($nameContact);
                    $contactObj->setFirstname($firstNameContact);
                    $contactObj->setEmail($emailContact);
                    $contactObj->setMessage($textareaContact);

                    
                    $entityManager->persist($contactObj);
                    $entityManager->flush();



                    return new JsonResponse([
                        'flag' => true,
                        'message' => 'Message sent'
                    ]);

                }


                

                catch (\Exception $e) {

                    return new JsonResponse([
                        'flag' => false,
                        'message' => 'Error while sending the message'
                    ]);
                }
            }



            return new JsonResponse([
                'flag' => false,
                'message' => 'Error while sending the message'
            ]);

            
        }

        

        return new JsonResponse([
            'flag' => false,
            'message' => 'Error while sending the message'
        ]);

        
    }
}
