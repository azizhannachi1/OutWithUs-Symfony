<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Offre;
use App\Form\EvenementType;
use App\Form\OffreType;
use App\Repository\EvenementRepository;
use App\Repository\OffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OffreController extends AbstractController
{
    /**
     * @Route("/afficheroffre", name="afficheroffre")
     */
    public function Affiche(OffreRepository $repository){
        $tableoffre=$repository->findAll();
        return $this->render('offre/afficheroffre.html.twig'
            ,['tableoffre'=>$tableoffre]);

    }
    /**
     * @Route("/afficheroffreclient", name="afficheroffreclient")
     */
    public function AfficheOffreFront(OffreRepository $repository){
        $tableoffre=$repository->findAll();
        return $this->render('offre/afficheroffreclient.html.twig'
            ,['tableoffre'=>$tableoffre]);

    }
    /**
     * @Route("/ajouteroffre",name="ajouteroffre")
     */
    public function ajouterOffre(EntityManagerInterface $em,Request $request ){
        $offre= new Offre();

        $form= $this->createForm(OffreType::class,$offre);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $new=$form->getData();

            $em->persist($offre);
            $em->flush();
            return $this->redirectToRoute("afficheroffre");

        }
        return $this->render("offre/ajouteroffre.html.twig",array("formulaire"=>$form->createView()));
    }
    /**
     * @Route("/supprimeroffre/{id}",name="supprimeroffre")
     */
    public function supprimeroffre($id,EntityManagerInterface $em ,OffreRepository $repository){
        $offre=$repository->find($id);
        $em->remove($offre);
        $em->flush();

        return $this->redirectToRoute('afficheroffre');
    }

    /**
     * @Route("/{id}/modifieroffre", name="modifieroffre", methods={"GET","POST"})
     */
    public function edit(Request $request, Offre $offre): Response
    {
        $form = $this->createForm(OffreType::class, $offre);
        $form->add('Confirmer',SubmitType::class);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();


            return $this->redirectToRoute('afficheroffre');
        }

        return $this->render('offre/modifieroffre.html.twig', [
            'offremodif' => $offre,
            'formulaire' => $form->createView(),
        ]);
    }



}
