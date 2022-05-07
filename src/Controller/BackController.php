<?php

namespace App\Controller;


use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Repository\UserRepository;

class BackController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/back", name="back")
     */
    public function index(UserRepository $clientRepository ,                          
                          ReclamationRepository $reclamationRepository): Response
    { $reclamations=$reclamationRepository->findAll();
        return $this->render('Back/Back.html.twig', [           
            'reclamations' => $reclamations,
           ]);

    }
}
