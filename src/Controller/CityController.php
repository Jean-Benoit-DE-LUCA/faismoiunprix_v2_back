<?php

namespace App\Controller;

use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

use Config;

class CityController extends AbstractController
{


    /*--- CHECK IF CITY IN DATABASE ---*/

    #[Route('/api/checkcity', name: 'check_city')]
    public function checkCity(Request $request, CityRepository $cityRepository)
    {

        $data = json_decode($request->getContent(), true);

        $city = null;
        $zip = null;

        $checkCity = null;


        if ($data !== null) {

            $city = $data['city'];
            $zip = $data['zip'];

            $checkCity = $cityRepository->checkCity($city, $zip);
        }

        return new JsonResponse([
            'checkCity' => $checkCity
        ]);
    }







    

    





    /*--- INSERT CITY ---*/

    #[Route('/api/insertcity', name: 'insert_city')]
    public function insertCity(Request $request, CityRepository $cityRepository) {

        $data = json_decode($request->getContent(), true);

        $city = null;
        $zip = null;
        $latitude = null;
        $longitude = null;

        if ($data !== null) {

            $city = $data['city'];
            $zip = $data['zip'];
            $latitude = $data['latitude'];
            $longitude = $data['longitude'];



            $cityRepository->insertCity($city, $zip, $latitude, $longitude);
        }

        return new JsonResponse([
            'flag' => true
        ]);
    }













    /*--- GET COORDINATES ---*/

    #[Route('/api/getcoordinates/city/{city}/zip/{zip}', name: 'get_coordinates_city_zip')]
    public function getCoordinatesByCityAndZip($city, $zip) {

        include_once('../Config.php');

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'https://geocode.maps.co/search?q=' . $city . '+' . $zip . '+france&api_key=' . Config::getKeyGeocodeMaps());

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);

        curl_close($curl);







        $json_response = json_decode($response);


        $latitude = null;
        $longitude = null;

        // get latitude and longitude //

        if (isset($json_response[0]->lat) && isset($json_response[0]->lon)) {

            $latitude = $json_response[0]->lat;
            $longitude = $json_response[0]->lon;

            return new JsonResponse([
                'flag' => true,
                'latitude' => $latitude,
                'longitude' => $longitude
            ]);
        }


        return new JsonResponse([
            'flag' => false
        ]);
    }
}
