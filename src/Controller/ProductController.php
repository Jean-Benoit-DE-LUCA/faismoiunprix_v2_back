<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\MessageContact;
use App\Repository\MessageContactRepository;
use App\Repository\MessageRepository;
use App\Repository\OfferRepository;
use App\Repository\PhotoRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Security\JwtAuthenticator;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{

    /*--- GET PRODUCTS BY SEARCH ---*/

    #[Route('/api/getproducts', name: 'get_products')]
    public function getProducts(Request $request, ProductRepository $productRepository, OfferRepository $offerRepository, PhotoRepository $photoRepository): JsonResponse
    {

        $search = $request->query->get('search');


        $getAllProducts = null;
        $getAllProductsByName = null;

        $getAllProductsByNameFilter = null;



        $getResult = [];

        if (strlen($search) == 0) {

            $getAllProducts = $productRepository->getAllProducts();



            

            $count = 0;

            foreach ($getAllProducts as $key => $value) {


                /* insert product */


                $product = [];

                foreach ($value as $k => $v) {

                    if ($k == 'product_city' || $k == 'product_name' || $k == 'product_description' || $k == 'product_email') {

                        $product[$k] = htmlspecialchars_decode($v);
                    }

                    else {

                        $product[$k] = $v;
                    }
                }

                $getResult[$count]['product'] = $product;







                /* insert offer array */

                $offer = [];

                $getResult[$count]['offer'] = $offerRepository->getOfferByProductId($value['product_id']);








                /* insert photo */

                $photo = [];

                $getResult[$count]['photo'] = $photoRepository->getPictureByProductId($value['product_id'])[0];




                /* increment count */

                $count++;
            }



            
            


            // sort by product created_at //

            $arrayCreatedAt = [];

            foreach ($getResult as $key => $value) {

                $arrayCreatedAt[$key] = $value['product']['product_created_at'];
            }

            array_multisort($arrayCreatedAt, SORT_DESC, $getResult);

            //

            




            return new JsonResponse([
                'result' => $getResult
            ]);
        }



        else {




            /* remove multiple whitespace in GET search key parameter */

            $cleanSearch = '';

            foreach (str_split($search) as $key => $value) {

                if ($value == ' ' && str_split($search)[$key + 1] == ' ') {

                    continue;
                }

                else {

                    $cleanSearch .= $value;
                }
            }

            $cleanSearch = trim($cleanSearch);

            /* -------------------------- */







            $getAllProductsByName = [];

            $arrayKeywordUser = explode(' ', $cleanSearch);



            foreach ($arrayKeywordUser as $value) {

                array_push($getAllProductsByName, $productRepository->getAllProductsByName(preg_replace('/[^a-z0-9]/i', '', $value)));
            }






            ///////////


            $productIdTemp = [];

            foreach ($getAllProductsByName as $key => $value) {

                

                foreach ($value as $k => $v) {

                    if (!in_array($v['product_id'], $productIdTemp)) {

                        $productIdTemp[] = $v['product_id'];
                    }
                }
            }








            $getResult = [];

            $count = 0;

            foreach ($productIdTemp as $value) {



                /* insert product */

                $product = [];

                $getResult[$count]['product'] = $productRepository->getProductById($value)[0];






                /* insert offer array */

                $offer = [];

                $getResult[$count]['offer'] = $offerRepository->getOfferByProductId($value);






                /* insert photo */

                $photo = [];

                $getResult[$count]['photo'] = $photoRepository->getPictureByProductId($value)[0];

                $count++;
            }





            // decode value //

            foreach ($getResult as $k => $v) {

                foreach ($v['product'] as $keyProduct => $valueProduct) {

                    if ($keyProduct == 'product_city' || $keyProduct == 'product_name' || $keyProduct == 'product_description' || $keyProduct == 'product_email') {

                        $getResult[$k]['product'][$keyProduct] = htmlspecialchars_decode($valueProduct);
                    }
                }
            }






            // sort by product created_at //

            $arrayCreatedAt = [];

            foreach ($getResult as $key => $value) {

                $arrayCreatedAt[$key] = $value['product']['product_created_at'];
            }

            array_multisort($arrayCreatedAt, SORT_DESC, $getResult);

            //






            return new JsonResponse([
                'result' => $getResult
            ]);

            ///////////

        }
    }















    /*--- INSERT PRODUCT ---*/

    #[Route('/api/insertproduct', name: 'insert_product')]
    public function insertProduct(ProductRepository $productRepository, OfferRepository $offerRepository, PhotoRepository $photoRepository) {


        $title = htmlspecialchars($_POST['title']);
        $category_id = $_POST['category_id'];
        $description = htmlspecialchars($_POST['description']);
        $city = htmlspecialchars($_POST['city']);
        $zip = $_POST['zip'];
        $phone = $_POST['phone'];
        $email = htmlspecialchars($_POST['email']);
        $delivery = $_POST['delivery'];
        $offer = $_POST['offer'];
        $user_id = intval($_POST['user_id']);

        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];

        $Authorization = $_POST['Authorization'];




        $getLastProductInserted = null;






        if (isset($Authorization)) {




            try {

                JwtAuthenticator::decodeJwt($Authorization);



                if (isset($title) && isset($category_id) && isset($description) && isset($city) && isset($zip) && isset($phone) && isset($email) && isset($delivery) && isset($offer) && isset($latitude) && isset($longitude)) {

                    if ($description == '') {

                        $description = null;
                    }

                    if ($offer == '') {

                        $offer = null;
                    }




                    /* create current date time */

                    $dateTimeZone = new \DateTimeZone('Europe/Paris');
                    $currentDateTime = new \DateTimeImmutable('now', $dateTimeZone);

                    $currentDateTimeFormatDataBase = $currentDateTime->format('Y/m/d H:i:s');

                    /* ------------------------ */


                    $created_at = $currentDateTimeFormatDataBase;
                    $updated_at = $currentDateTimeFormatDataBase;








                    // /* if user_id > 0 */


                    if ($user_id !== 0) {


                        $productRepository->insertProduct($title, $category_id, $description, $created_at, $updated_at, strtolower($city), $user_id, $delivery, $latitude, $longitude, $zip, $phone, $email);



                        /* get last id */

                        $getLastProductInserted = $productRepository->getLastProductInserted($title, $created_at, $city, $user_id);



                        /* insert into offer */

                        $offerRepository->insertOffer($getLastProductInserted[0]['id'], $offer, $user_id, $created_at, $updated_at);

                        
                            





                        /* ---------- picture part ----------- */

                        if (count($_FILES) == 0) {

                            $photoRepository->insertPhotoList($getLastProductInserted[0]['id'], null, $created_at, $updated_at);

                            return new JsonResponse([
                                'flag' => true,
                                'message' => 'Product added successfully'
                            ]);
                        }



                        else if (count($_FILES) > 0) {


                            // check size files //

                            $flagSize = false;

                            foreach ($_FILES as $key => $value) {

                                if ($value['size'] > 8500000) {

                                    $flagSize = true;
                                    break;
                                }
                            }






                            // check extension files //

                            $flagExtension = false;

                            $arrayAllowExtension = ['jpg', 'jpeg', 'bmp', 'gif', 'png', 'svg', 'webp'];

                            foreach ($_FILES as $key => $value) {

                                $explodeNamePicture = explode('.', $value['name']);

                                if (!in_array(end($explodeNamePicture), $arrayAllowExtension)) {

                                    $flagExtension = true;
                                    break;
                                }
                            }









                            // if tests OK => upload file //

                            if (!$flagSize && !$flagExtension) {

                                $targetDirectory = './uploads/pictures/';

                                $currentDateTimeFormatPicture = $currentDateTime->format('Y-m-d_H-i-s');

                                $arrayFileName = [];



                                /* push upload to server folder */


                                foreach ($_FILES as $key => $value) {

                                    $nameFile = $currentDateTimeFormatPicture . '__' . str_replace(',', '_', $value['name']);

                                    move_uploaded_file($value['tmp_name'], $targetDirectory . $nameFile);

                                    array_push($arrayFileName, $nameFile);
                                }







                                /* insert into database */

                                $photoRepository->insertPhotoList($getLastProductInserted[0]['id'], implode(',', $arrayFileName), $created_at, $updated_at);





                                return new JsonResponse([
                                    'flag' => true,
                                    'message' => 'Product added successfully'
                                ]);
                            }




                            else if ($flagSize) {

                                return new JsonResponse([
                                    'flag' => false,
                                    'message' => 'File size exceeds the maximum authorized (~8.2Mb)'
                                ]);
                            }

                            else if ($flagExtension) {

                                return new JsonResponse([
                                    'flag' => false,
                                    'message' => 'File extension is not authorized (only "jpg", "jpeg", "bmp", "gif", "png", "svg", "webp")'
                                ]);
                            }
                        }
                    }







                    else {

                        return new JsonResponse([
                            'flag' =>  false,
                            'message' => 'User must be logged in'
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















    /*--- UPDATE PRODUCT ---*/

    #[Route('/api/updateproduct/{product_id}', name: 'update_product')]
    public function updateProduct(
        $product_id,
        ProductRepository $productRepository,
        PhotoRepository $photoRepository,
        OfferRepository $offerRepository,
        UserRepository $userRepository
    ) {

        $title = htmlspecialchars($_POST['title']);
        $category_id = $_POST['category_id'];
        $description = htmlspecialchars($_POST['description']);
        $city = htmlspecialchars($_POST['city']);
        $zip = $_POST['zip'];
        $phone = $_POST['phone'];
        $email = htmlspecialchars($_POST['email']);
        $delivery = $_POST['delivery'];
        $offer = $_POST['offer'];
        $user_id = intval($_POST['user_id']);

        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];

        $nameFile1 = $_POST['nameFile1'];
        $nameFile2 = $_POST['nameFile2'];
        $nameFile3 = $_POST['nameFile3'];

        $arrayFileNameInput = [$nameFile1, $nameFile2, $nameFile3];


        $result = [];


        $result[0]['product'] = $productRepository->getProductById($product_id)[0];
        $result[0]['photo'] = $photoRepository->getPictureByProductId($product_id)[0];
        $result[0]['offer'] = $offerRepository->getOfferByProductIdUserData($product_id);


        $Authorization = $_POST['Authorization'];





        if (isset($Authorization)) {




            try {

                JwtAuthenticator::decodeJwt($Authorization);

                if (isset($title) && isset($category_id) && isset($description) && isset($city) && isset($zip) && isset($phone) && isset($email) && isset($delivery) && isset($offer) && isset($latitude) && isset($longitude)) {

                    if ($description == '') {

                        $description = null;
                    }





                    if ($offer == '' || $offer == '0') {

                        $offer = null;
                    }





                    /* create current date time */

                    $dateTimeZone = new \DateTimeZone('Europe/Paris');
                    $currentDateTime = new \DateTimeImmutable('now', $dateTimeZone);

                    $currentDateTimeFormatDataBase = $currentDateTime->format('Y/m/d H:i:s');

                    /* ------------------------ */


                    $created_at = $currentDateTimeFormatDataBase;
                    $updated_at = $currentDateTimeFormatDataBase;








                    // /* if user_id > 0 */


                    if ($user_id !== 0) {

                        

                        if (count($userRepository->findUserByEmail($email)) == 0 || 
                            $userRepository->findUserById($user_id)[0]['email'] == $email) {




                            // update photo //

                            foreach ($arrayFileNameInput as $key => $value) {

                                if ($value == '') {

                                    unset($arrayFileNameInput[$key]);
                                }
                            }




                            
                            

                            $newArrayFileNameInput = [];

                            foreach ($arrayFileNameInput as $key => $fileName) {

                                // dd($fileName);
                                $isIn = false;

                                foreach (explode(',', $result[0]['photo']['photo_photo_list']) as $keyDb => $fileNameDb) {

                                    if ($fileName == $fileNameDb) {

                                        $isIn = true;
                                        break;
                                    }
                                }

                                if (!$isIn) {

                                    $newArrayFileNameInput[$key] = $fileName;
                                }
                            }

                            





                            // create new array with future new files names //

                            $arrayNewFileSaved = [];

                            foreach ($arrayFileNameInput as $key => $fileName) {

                                $arrayNewFileSaved[$key] = $fileName;
                            }

                            foreach ($newArrayFileNameInput as $key => $fileName) {

                                $flag = false;
                                $keyFileFlag = '';

                                foreach ($_FILES as $keyFiles => $valueFiles) {

                                    foreach ($valueFiles as $keyFile => $valueFile) {

                                        if ($keyFile == 'name') {

                                            if ($valueFiles[$keyFile] == $fileName) {

                                                $flag = true;
                                                break;
                                            }
                                        }
                                    }

                                    if ($flag) {

                                        $keyFileFlag = $keyFiles;
                                        break;
                                    }
                                }





                                if ($flag) {





                                    

                                    // check size files //

                                    $flagSize = false;

                                    if ($_FILES[$keyFileFlag]['size'] > 8500000) {

                                        $flagSize = true;
                                        break;
                                    }

                                    






                                    // check extension files //

                                    $flagExtension = false;

                                    $arrayAllowExtension = ['jpg', 'jpeg', 'bmp', 'gif', 'png', 'svg', 'webp'];

                                    $explodeNamePicture = explode('.', $_FILES[$keyFileFlag]['name']);



                                    if (!in_array(end($explodeNamePicture), $arrayAllowExtension)) {

                                        $flagExtension = true;
                                    }

                                    








                                    if (!$flagSize && !$flagExtension) {

                                        $targetDirectory = './uploads/pictures/';
                
                                        $currentDateTimeFormatPicture = $currentDateTime->format('Y-m-d_H-i-s');
                

                
                
                
                                        /* push upload to server folder */
                
                
                                        $nameFile = $currentDateTimeFormatPicture . '__' . str_replace(',', '_', $_FILES[$keyFileFlag]['name']);
                
                                        move_uploaded_file($_FILES[$keyFileFlag]['tmp_name'], $targetDirectory . $nameFile);






                                        // update new array with future new files names //

                                        $arrayNewFileSaved[$key] = $nameFile;

                                    }

                                    else if ($flagSize) {

                                        return new JsonResponse([
                                            'flag' => false,
                                            'message' => 'File size exceeds the maximum authorized (~8.2Mb)'
                                        ]);
                                    }
                
                                    else if ($flagExtension) {
                
                                        return new JsonResponse([
                                            'flag' => false,
                                            'message' => 'File extension is not authorized (only "jpg", "jpeg", "bmp", "gif", "png", "svg", "webp")'
                                        ]);
                                    }
                                    
                                }
                            }








                            
                            // delete old pictures //

                            foreach (explode(',', $result[0]['photo']['photo_photo_list']) as $keyDb => $fileNameDb) {


                                if (!in_array($fileNameDb, $arrayNewFileSaved)) {

                                    if ($fileNameDb !== '') {

                                        unlink(getcwd() . '/uploads/pictures/' . $fileNameDb);
                                    }
                                }
                            }





                            // update photo_list column database //

                            if (count($arrayNewFileSaved) == 0) {

                                $updatePhoto = $photoRepository->updatePhotoList($product_id, null, $updated_at);
                            }

                            else if (count($arrayNewFileSaved) > 0) {

                                $updatePhoto = $photoRepository->updatePhotoList($product_id, implode(',', $arrayNewFileSaved), $updated_at);
                            }










                            // update offer //

                            $result[0]['offer'] = self::sortOffer($result[0]['offer']);



                            $updateOfferMin = $offerRepository->updateOfferMin($result[0]['offer'][count($result[0]['offer']) - 1]['offer_id'], $offer, $updated_at);










                            // update data //



                            $updateData = $productRepository->updateProduct($title, $description, $updated_at, $city, $delivery, $latitude, $longitude, $zip, $phone, $category_id, $email, $product_id);





                            $result = [];

                            $result[0]['product'] = $productRepository->getProductById($product_id)[0];
                            $result[0]['photo'] = $photoRepository->getPictureByProductId($product_id)[0];
                            $result[0]['offer'] = $offerRepository->getOfferByProductIdUserData($product_id);


                            return new JsonResponse([
                                'flag' => true,
                                'result' => $result
                            ]);
                        }



                        else {

                            return new JsonResponse([
                                'flag' => false,
                                'message' => 'User already registered'
                            ]);
                        }

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





    public function sortOffer($arrayOffer) {

        $newArr = [];

        foreach ($arrayOffer as $key => $value) {

            $newArr[$key] = $value['offer_created_at'];
        }

        array_multisort($newArr, SORT_DESC, $arrayOffer);

        return $arrayOffer;
    }














    /*--- GET PRODUCT DISTANCE FROM USER INPUT ---*/

    #[Route('/api/getdistance', name: 'get_distance')]
    public function getDistance(Request $request, ProductRepository $productRepository)
    {

        $data = json_decode($request->getContent(), true);

        $latitudeInput = null;
        $longitudeInput = null;
        $getProductDistance = null;



        if ($data !== null) {

            $latitudeInput = $data['latitudeInput'];
            $longitudeInput = $data['longitudeInput'];

            $getProductDistance = $productRepository->getDistance($latitudeInput, $longitudeInput);
        }

        return new JsonResponse([
            'latitudeInput' => $latitudeInput,
            'longitudeInput' => $longitudeInput,
            'result' => $getProductDistance
        ]);
    }













    /*--- GET PRODUCT BY ID ---*/

    #[Route('/api/getproductbyid/{product_id}', name: 'get_product_by_id')]
    public function getProductById($product_id, ProductRepository $productRepository, PhotoRepository $photoRepository, OfferRepository $offerRepository) {

        $result = [];

        $result[0]['product'] = $productRepository->getProductById($product_id)[0];
        $result[0]['photo'] = $photoRepository->getPictureByProductId($product_id)[0];
        $result[0]['offer'] = $offerRepository->getOfferByProductIdUserData($product_id);




        foreach ($result[0]['product'] as $k => $v) {

            if ($k == 'product_city' || $k == 'product_name' || $k == 'product_description' || $k == 'product_email') {

                $result[0]['product'][$k] = htmlspecialchars_decode($v);
            }
        }

        return new JsonResponse([
            'result' => $result
        ]);
    }












    /*--- GET PRODUCT BY USER ID ---*/

    #[Route('/api/getproductsbyuserid/{user_id}', name: 'get_products_by_user_id')]
    public function getProductsByUserId($user_id,
        ProductRepository $productRepository) {


        $getProducts = $productRepository->getProductsByUserId($user_id);

        foreach ($getProducts as $k => $v) {

            foreach ($v as $keyProduct => $valueProduct) {

                if ($keyProduct == 'product_city' || $keyProduct == 'product_name' || $keyProduct == 'product_description' || $keyProduct == 'product_email') {
    
                    $getProducts[$k][$keyProduct] = htmlspecialchars_decode($valueProduct);
                }
            }
            
        }

        return new JsonResponse([
            'result' => $getProducts
        ]);
    }









    /*--- GET PRODUCTS BY USER ID => DIFFERENT RETURN ---*/

    #[Route('/api/getproductsbyuserid_alt/{user_id}', name: 'get_products_by_user_id_alt')]
    public function getProductsByUserIdAlt($user_id,
        ProductRepository $productRepository,
        OfferRepository $offerRepository,
        PhotoRepository $photoRepository) {



        $getProductsByUserIdAlt = null;



        $getResult = [];

        

        $getProductsByUserIdAlt = $productRepository->getProductsByUserIdAlt($user_id);





        

        $count = 0;

        foreach ($getProductsByUserIdAlt as $key => $value) {


            /* insert product */


            $product = [];

            foreach ($value as $k => $v) {

                if ($k == 'product_city' || $k == 'product_name' || $k == 'product_description' || $k == 'product_email') {

                    $product[$k] = htmlspecialchars_decode($v);
                }

                else {

                    $product[$k] = $v;
                }
            }

            $getResult[$count]['product'] = $product;







            /* insert offer array */

            $offer = [];

            $getResult[$count]['offer'] = $offerRepository->getOfferByProductId($value['product_id']);








            /* insert photo */

            $photo = [];

            $getResult[$count]['photo'] = $photoRepository->getPictureByProductId($value['product_id'])[0];




            /* increment count */

            $count++;
        }



        
        


        // sort by product created_at //

        $arrayCreatedAt = [];

        foreach ($getResult as $key => $value) {

            $arrayCreatedAt[$key] = $value['product']['product_created_at'];
        }

        array_multisort($arrayCreatedAt, SORT_DESC, $getResult);

        //

        




        return new JsonResponse([
            'result' => $getResult
        ]);
        
    }














    /*--- DELETE PRODUCT ---*/

    #[Route('/api/deleteproduct/{product_id_delete}', name: 'delete_product')]
    public function deleteProduct(
        $product_id_delete,
        ProductRepository $productRepository,
        PhotoRepository $photoRepository,
        OfferRepository $offerRepository,
        MessageContactRepository $messageContactRepository,
        MessageRepository $messageRepository) {




        if (isset(getallheaders()['Authorization'])) {



            $jwtReceived = str_replace('Bearer ', '', getallheaders()['Authorization']);




            try {

                JwtAuthenticator::decodeJwt($jwtReceived);






                // delete message //

                $deleteMessage = $messageRepository->deleteMessageByProductId($product_id_delete);

                // delete message_contact //

                $deleteMessageContact = $messageContactRepository->deleteMessageContactByProductId($product_id_delete);

                // delete offer //

                $deleteOffer = $offerRepository->deleteOfferByProductId($product_id_delete);







                // delete photo //

                $getPicture = $photoRepository->getPictureByProductId($product_id_delete);

                $arrayPicture = [];
                $pathPicture = getcwd() . '/uploads/pictures/';

                if ($getPicture[0]['photo_photo_list'] !== null) {

                    $arrayPicture = explode(',', $getPicture[0]['photo_photo_list']);

                    foreach ($arrayPicture as $key => $picture) {

                        unlink($pathPicture . $picture);
                    }
                }

                $deletePicture = $photoRepository->deletePhotoByProductId($product_id_delete);






                // delete product //

                $deleteProduct = $productRepository->deleteProduct($product_id_delete);
                
                

                return new JsonResponse([
                    'flag' => true,
                    'message' => 'Product successfully removed'
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
            'message' => ''
        ]);
    }
}
