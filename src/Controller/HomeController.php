<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\PublicationRepository;
use App\Entity\Publication;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(UserRepository $clientRepository ,                          
                          PublicationRepository $publicationRepository

   , Request $request): Response
    {
        // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
        $donnees = $this->getDoctrine()->getRepository(Publication::class)->findAll();
        

        return $this->render('home/front.html.twig', [            
            'publication' => $publicationRepository->findAll(),
        ]);
    }
}
