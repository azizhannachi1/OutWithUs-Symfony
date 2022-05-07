<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ReclamationType;
use App\Entity\Reclamation;
use App\Repository\ReclamationRepository;
use App\Repository\CategorieResRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mine\Email;
use Sension\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class ReclamationController extends AbstractController
{
    /**
     * @Route("/reclamation", name="index")
     */
    public function index(): Response
    {

        return $this->render('reclamation/index.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }


 /**
     * @param ReclamationRepository $repository
     * @return \Symfony\Component\\HttpFoundation\Response
     * @Route("/AfficheR", name="AfficheR")
     */
    public function Affiche(ReclamationRepository $repository, CategorieResRepository $catRepo, Request $request, PaginatorInterface $paginator){
         
        $reclamation=$repository->findAll();
        
        $e = $paginator->paginate(
            $reclamation,
            $request->query->getInt('page', 1),
            3
        );
      

       
        return $this->render('reclamation/Affiche.html.twig', [
            'reclamation' => $e,

        ]);
    }

        /**
         * @param $id
         * @param $repository
         * @return \Symfony\Component\\HttpFoundation\RedirectResponse
     * @Route("reclamation/Delete/{id}", name="d")
     */
    public function Delete($id, ReclamationRepository $repository){
        $reclamation=$repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($reclamation);
        $em->flush();
        return $this->redirectToRoute('AfficheR');
            }


    /**
     * @Route("reclamation/Update/{id}",name="modifier")
     */
    function Update(ReclamationRepository $repository, $id, Request $request){
        $reclamation=$repository->find($id);
        $form=$this->createForm(ReclamationType::class,$reclamation);
        $form->add('Update',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $date = new \DateTime('now');
                $reclamation->setDate($date);
            $em->flush();
            return $this->redirectToRoute('AfficheR');
        }
        return $this->render('reclamation/Update.html.twig',[
            'f'=>$form->createView()
        ]);
    }

 /**
     * @param Request $request
     * @return \Symfony\Component\\HttpFoundation\Response
     * @Route("reclamation/Add")
     */
    function Add(Request $request,\Swift_Mailer $mailer) {
        $reclamation = new Reclamation();
        $form=$this->createForm(ReclamationType::class,$reclamation);
       
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){

           
            $em=$this->getDoctrine()->getManager();
            $date = new \DateTime('now');

            $reclamation->setDate($date);
            
           

            $em->persist($reclamation);
            $em->flush();



            $message = (new \Swift_Message('New'))

            ->setFrom('azizhannachi98@gmail.com')

            ->setTo($reclamation->getEmail())
            
            ->setSubject('Réclamation bien envoyée')
            ->setBody('Bonjour Monsieur '.$reclamation->getNom().'. '.
            'Cet email est pour confirmer l envoi de votre réclamation concernant '.$reclamation->getSujet()->getNom().
            '. Merci pour votre confiance' );
            
 
   
            $mailer->send($message); 
          
             
            
        return $this->redirectToRoute('AfficheR');
        }
        return $this->render('reclamation/Add.html.twig',[
            'f'=>$form->createView()
        ]);
            }


            /**
     * @param Request $request
     * @return \Symfony\Component\\HttpFoundation\Response
     * @Route("reclamation/New")
     */
    function New(Request $request){
        $reclamation = new Reclamation();
        $form=$this->createForm(ReclamationType::class,$reclamation);
       
                $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $date = new \DateTime('now');

            $reclamation->setDate($date);

            $em->persist($reclamation);
            $em->flush();
            
        return $this->redirectToRoute('index');
        }
        return $this->render('reclamation/New.html.twig',[
            'f'=>$form->createView()
        ]);
            }


    /**
     * @Route("reclamation/recherche/",name="recherche")
     */
            function Recherche (ReclamationRepository $repository, Request $request ){
              $data=$request->get('search');
              $reclamation=$repository->findBy(['nom'=>$data]);
              return $this->render('reclamation/Affiche.html.twig',['reclamation'=>$reclamation]);
            }
 /**
     * @param ReclamationRepository $repository
     * @return Response
     * @Route("reclamation/ListDQL")
     */
            function OrderByMailDQL(ReclamationRepository $repository){
                $reclamation=$repository->OrderByMailDQL();
                return $this->render('reclamation/Affiche.html.twig',['reclamation'=>$reclamation]);
            }

             /**
     * @param ReclamationRepository $repository
     * @return Response
     * @Route("reclamation/ListQB")
     */
    function OrderByMailQB(ReclamationRepository $repository){
        $reclamation=$repository->OrderByMailQB();
        return $this->render('reclamation/Affiche.html.twig',['reclamation'=>$reclamation]);
    }


}
