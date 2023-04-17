<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FunnyPageController extends AbstractController
{
    #[Route('/funny/page', name: 'app_funny_page')]
    public function index(): Response
    {
        return $this->render('funny_page/funnypage.html.twig', [
            'controller_name' => 'FunnyPageController',
        ]);
    }
}
