<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainsController extends AbstractController
{
    /**
     * @Route("/mains", name="app_mains")
     */
    public function index(): Response
    {
        return $this->render('mains/index.html.twig', [
            'controller_name' => 'MainsController',
        ]);
    }
}
