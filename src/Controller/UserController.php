<?php

namespace App\Controller;

use App\Repository\JwtRepository;
use App\Repository\SessionDataRepository;
use App\Repository\UserRepository;

use App\Security\JwtAuthenticator;

use Config;
use DateTimeZone;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;


class UserController extends AbstractController
{
    #[Route('/api/register', name: 'api_register_user')]
    public function registerUser(Request $request, UserRepository $userRepository, SessionDataRepository $sessionDataRepository, JwtRepository $jwtRepository)
    {   

        $flag = null;
        $message = null;

        $data = json_decode($request->getContent(), true);

        $name = null;
        $firstname = null;
        $email = null;
        $phone = null;
        $password = null;
        $created_at = null;
        $updated_at = null;

        $checkUser = null;
        $user = null;
        $sessionKey = null;

        $currentDateTime = null;

        if ($data !== null) {


            $name = htmlspecialchars($data['name']);
            $firstname = htmlspecialchars($data['firstname']);
            $email = htmlspecialchars($data['email']);
            $phone = htmlspecialchars($data['phone']);
            $password = htmlspecialchars($data['password']);




            $checkUser = $userRepository->findUserByEmail($email);

            if (count($checkUser) == 0) {




                $factory = new PasswordHasherFactory([
                    'common' => ['algorithm' => 'bcrypt']   
                ]);

                $hasher = $factory->getPasswordHasher('common');
                $hash_password = $hasher->hash($password);



                /* create current date time */

                $dateTimeZone = new \DateTimeZone('Europe/Paris');
                $currentDateTime = new \DateTimeImmutable('now', $dateTimeZone);
                $currentDateTime = $currentDateTime->format('Y/m/d H:i:s');

                /* ------------------------ */


                $created_at = $currentDateTime;
                $updated_at = $currentDateTime;

                


                /* register and find user -> return user data */

                $userRepository->registerUser($name, $firstname, $email, $phone, $hash_password, $created_at, $updated_at);

                /* ------------------------- */




                /* get last user registered */

                $getLastUserInserted = $userRepository->findUserByEmail($email);

                /* ------------------------- */



                /* encrypt user_id to store cookie data */

                $sessionKey = self::encryptUserId($getLastUserInserted[0]['id']);


                $sessionDataRepository->insertSessionData($sessionKey, $getLastUserInserted[0]['id']);













                $flag = true;
                $message = 'User registered';
                $user = $userRepository->findUserByEmail($email);





                /* create current date time */

                $dateTimeZone = new \DateTimeZone('Europe/Paris');
                $currentDateTime = new \DateTimeImmutable('now', $dateTimeZone);
                $currentDateTime = $currentDateTime->format('Y/m/d H:i:s');

                /* ------------------------ */


                $created_at = $currentDateTime;
                $updated_at = $currentDateTime;









                // set JWT //


                $jwt = JwtAuthenticator::encodeJwt();

                $user[0]['jwt'] = $jwt;


                $jwtRepository->deleteJwt($user[0]['id']);
                $jwtRepository->insertJwt($user[0]['id'], $jwt, $created_at, $updated_at);





                // --- //
            }

            else {

                $flag = false;
                $message = 'User already registered';
                $user = null;
            }
            
        }


        else {

            $flag = false;
        }

        return new JsonResponse([
    
            'registered' => $flag,
            'message' => $message,
            'user' => $user,
            'sessionKey' => $sessionKey
        ]);

    }















    /*--- UPDATE USER DATA ---*/
    #[Route('/api/updateuser', name: 'update_user')]
    public function updateUser(
        Request $request,
        UserRepository $userRepository) {

        $flag = null;
        $message = null;

        $data = json_decode($request->getContent(), true);

        $name = null;
        $firstname = null;
        $email = null;
        $phone = null;
        $password = null;
        $user_id = null;


        $jwtReceived = null;








        if ($data !== null) {


            $name = htmlspecialchars($data['name']);
            $firstname = htmlspecialchars($data['firstname']);
            $email = htmlspecialchars($data['email']);
            $phone = htmlspecialchars($data['phone']);
            $password = htmlspecialchars($data['password']);
            $user_id = $data['user_id'];




            if ($user_id !== 0) {



                if (isset(getallheaders()['Authorization'])) {

                    $jwtReceived = str_replace('Bearer ', '', getallheaders()['Authorization']);





                    try {

                        $jwtResult = JwtAuthenticator::decodeJwt($jwtReceived);



                        $findUserByEmail = $userRepository->findUserByEmail($email);



                        if ($findUserByEmail[0]['id'] == $user_id) {




                            $factory = new PasswordHasherFactory([
                                'common' => ['algorithm' => 'bcrypt']   
                            ]);
            
                            $hasher = $factory->getPasswordHasher('common');
                            $hash_password = $hasher->hash($password);
            
            
            
                            /* create current date time */
            
                            $dateTimeZone = new \DateTimeZone('Europe/Paris');
                            $currentDateTime = new \DateTimeImmutable('now', $dateTimeZone);
                            $currentDateTime = $currentDateTime->format('Y/m/d H:i:s');
            
                            /* ------------------------ */
            
            
                            $created_at = $currentDateTime;
                            $updated_at = $currentDateTime;





                            $userRepository->updateUser($name, $firstname, $email, $phone, $hash_password, $updated_at, $user_id);



                            return new JsonResponse([
                                'flag' => true,
                                'message' => 'User data updated successfully'
                            ]);
                        }


                        else {

                            return new JsonResponse([
                                'flag' => false,
                                'message' => 'Please log in again'
                            ]);
                        }
                    }


                    catch (\Exception $e) {

                        return new JsonResponse([
                            'flag' => false,
                            'message' => 'Expired token'
                        ]);
                    }


                }
            }





            // if user not authenticate //

            else {

                $message = 'You must log in to update your profile';

                return new JsonResponse([
                    'flag' => false,
                    'message' => $message
                ]);
            }
        }

        return new JsonResponse([
            'flag' => false,
            'message' => false
        ]);
    }







    #[Route('/api/finduser', name: 'finduser')]
    public function findUser(Request $request, UserRepository $userRepository, SessionDataRepository $sessionDataRepository, JwtRepository $jwtRepository) {

        $flag = null;
        $message = null;


        $data = json_decode($request->getContent(), true);

        $email = null;
        $password = null;

        $user = null;


        if ($data !== null) {

            $email = $data['email'];
            $password = $data['password'];

            $user = $userRepository->findUserByEmail($email);

            if (count($user) > 0) {

                $factory = new PasswordHasherFactory([
                    'common' => ['algorithm' => 'bcrypt']
                ]);

                $hasher = $factory->getPasswordHasher('common');

                if ($hasher->verify($user[0]['password'], $password)) {




                    // set session key cookie //


                    $sessionKey = self::encryptUserId($user[0]['id']);

                    $sessionDataRepository->insertSessionData($sessionKey, $user[0]['id']);


                    // --- //











                    /* create current date time */

                    $dateTimeZone = new \DateTimeZone('Europe/Paris');
                    $currentDateTime = new \DateTimeImmutable('now', $dateTimeZone);
                    $currentDateTime = $currentDateTime->format('Y/m/d H:i:s');

                    /* ------------------------ */


                    $created_at = $currentDateTime;
                    $updated_at = $currentDateTime;









                    // set JWT //


                    $jwt = JwtAuthenticator::encodeJwt();

                    $user[0]['jwt'] = $jwt;



                    $jwtRepository->deleteJwt($user[0]['id']);
                    $jwtRepository->insertJwt($user[0]['id'], $jwt, $created_at, $updated_at);





                    // --- //


                    $flag = true;
                    $message = 'User has logged in';

                    return new JsonResponse([
                        'logged' => $flag,
                        'message' => $message,
                        'user' => $user,
                        'sessionKey' => $sessionKey
                    ]);
                }

                else {

                    $flag = false;
                    $message = 'Wrong password';

                    return new JsonResponse([
                        'logged' => $flag,
                        'message' => $message,
                        'user' => $user
                    ]);
                }

                
            }



            else if (count($user) == 0) {

                $flag = false;
                $message = 'User not found';

                return new JsonResponse([
                    'logged' => $flag,
                    'message' => $message,
                    'user' => $user
                ]);
            }
        }

        return new JsonResponse([
            'logged' => false,
            'message' => $message,
            'user' => $user
        ]);
    }






    protected function encryptUserId($user_id) {

        // encrypt user_id to store cookie data //

        include_once('../Config.php');

        $key = Config::getKeyOpensslEncrypt();
        $iv_length = openssl_cipher_iv_length('aes-128-ctr');
        $iv = openssl_random_pseudo_bytes($iv_length);

        $encryptId = openssl_encrypt(strval($user_id), 'aes-128-ctr', $key, OPENSSL_RAW_DATA, $iv);

        $sessionKey = md5(base64_encode($encryptId));



        return $sessionKey;
    }
}
