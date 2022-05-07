<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategorieResRepository;
use App\Repository\ReclamationRepository;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

      /**
       * @param CategorieResRepository $repository
     * @Route("/stats", name="stats")
     */
    public function statistiques(CategorieResRepository $repository, ReclamationRepository $repos)
    {
        $categorieres=$repository->findAll();

        $categNom = [];
        $categColor = [];
        $categCount = [];

        foreach($categorieres as $categorie){
            $categNom[] = $categorie->getNom();
            $categColor[] = $categorie->getColor();
            $categCount[] = count($categorie->getReclamations());
        }

       // $reclamation = $repos->selectInterval("2022-04-23", "2022-04-25");
        $reclamation = $repos->countByDate();

        $dates = [];
        $reclamationsCount = [];

        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach($reclamation as $reclamations){
            $dates[] = $reclamations['date'];
            $reclamationsCount[] = $reclamations['count'];
        }


        return $this->render('admin/stats.html.twig', [
            'categNom' => json_encode($categNom),
            'categColor' => json_encode($categColor),
            'categCount' => json_encode($categCount),
            'dates' => json_encode($dates),
            'reclamationsCount' => json_encode($reclamationsCount)
        ]);
    }
   
}
