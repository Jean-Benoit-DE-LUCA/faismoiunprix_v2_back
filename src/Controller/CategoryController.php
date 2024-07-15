<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/api/getcategoryall', name: 'get_category')]
    public function getCategoryAll(CategoryRepository $categoryRepository): Response
    {

        $result = $categoryRepository->getCategoryAll();

        return new JsonResponse([
            'result' => $result
        ]);
    }
}
