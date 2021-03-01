<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FraisController extends AbstractController
{
    /**
     * @Route("/frais", name="frais")
     */
    public function index(): Response
    {
        return $this->render('frais/index.html.twig', [
            'controller_name' => 'FraisController',
        ]);
    }
}
