<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\EvenementLike;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Like;
use App\Repository\LikeRepository;
use App\Repository\DislikeRepository;
use App\Entity\Dislike;
use App\Repository\EvenementLikeRepository;
use App\Entity\Paiement;
use App\Repository\PaiementRepository;
use App\Form\ReservationType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mine\Email;


class EvenementController extends AbstractController
{
    /**
     * @Route("/afficherevenement", name="afficherevenement")
     */
    public function AfficherEvent(Request $request,EvenementRepository $repository, PaginatorInterface $paginator){
        $tableevents=$repository->listEventsbydate();
        $tableevents = $paginator->paginate(
            $tableevents,
            $request->query->getInt('page', 1),
            3
        );



        return $this->render('evenement/afficherevenement.html.twig'
            ,['tableevents'=>$tableevents

            ]);

    }

     /**
     * @Route("/afficherevenementclient", name="afficherevenementclient")
     */
    public function AfficherEventFront(EvenementRepository $repository){
        $tableevents=$repository->listEventsbydate();


        $categorie= [];
        foreach($tableevents as $categorie ){

        $nombredislike[]= count($categorie->getDislikes());

        }

        $categorie2= [];
        foreach($tableevents as $categorie2 ){

            $nombreslike[]= count($categorie2->getLikes());

        }
        return $this->render('evenement/afficherevenementclient.html.twig'
            ,['tableevents'=>$tableevents,
                'nombredislike'=>$nombredislike,
                'nombreslike'=>$nombreslike

            ]

        );

    }

     /**
     * @Route("/ajouterevenement",name="ajouterevenement")
     */
    public function ajouterEvent(EntityManagerInterface $em,Request $request,\Swift_Mailer $mailer ){
        $event= new Evenement();

        $form= $this->createForm(EvenementType::class,$event);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $new=$form->getData();
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                          $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $event->setImage($newFilename);
            }
            $em->persist($event);
            $em->flush();
            $mail=[];




            $message = (new \Swift_Message("Un nouveau Evenement a été ajouté  "))

                ->setFrom('azizhannachi98@gmail.com')
                ->setTo('hannachi.aziz@esprit.tn')
                //message avec vue twig
                ->setBody(
                    $this->renderView(
                        'emails/contact.html.twig'
                    ),
                    'text/html'
                ) ;

            $mailer->send($message);








            return $this->redirectToRoute("afficherevenement");

        }
        return $this->render("evenement/ajouterevenement.html.twig",array("formulaire"=>$form->createView()));
    }

    /**
     * @Route("/supprimerevenement/{id}",name="supprimerevenement")
     */
    public function supprimerEvent($id,EntityManagerInterface $em ,EvenementRepository $repository){
        $event=$repository->find($id);
        $em->remove($event);
        $em->flush();

        return $this->redirectToRoute('afficherevenement');
    }

    /**
     * @Route("/{id}/modifierevenement", name="modifierevenement", methods={"GET","POST"})
     */
    public function edit(Request $request, Evenement $event): Response
    {
        $form = $this->createForm(EvenementType::class, $event);
        $form->add('Confirmer',SubmitType::class);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $event->setImage($newFilename);
            }
            $this->getDoctrine()->getManager()->flush();


            return $this->redirectToRoute('afficherevenement');
        }

        return $this->render('evenement/modifierevenement.html.twig', [
            'eventmodif' => $event,
            'formulaire' => $form->createView(),
        ]);
    }

    /**
     * @Route("/pdf/{id}", name="pdf" ,  methods={"GET"})
     */
    public function pdf($id,EvenementRepository $repository){

        $materiel=$repository->find($id);

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);
        $html = $this->renderView('evenement/pdf.html.twig', [
            'pdf' => $materiel
        ]);
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();
        //  $dompdf->stream();
        // Output the generated PDF to Browser (force download)
        $dompdf->stream($materiel->getNomEvent(), [
            "Attachment" => false
        ]);
    }

     /**
     * @Route("/add/like/{id}",name="add_like")
     */

    public function addLike (EvenementRepository $repository,LikeRepository  $repositoryLike,Request $request, EntityManagerInterface $em) {
        $like = new Like() ;
        $article = $repository->findOneBy(['id' => $request->get('id')]);
      //  $liked = $repositoryLike->findOneBy(['article' => $article,'utilisateur'=>$currentUser]);
        //dump($liked);die();
        $like->setEvenement($article);
       // $like->setUtilisateur($currentUser);
       // if($liked == null){
            $em->persist($like);
       // }else{
          //  $em->remove($liked);
       // }
        $em->flush();
        return $this->redirectToRoute('afficherevenementclient', ['id' =>$request->get('id')]);

    }

    /**
     * @Route("/add/dislike/{id}",name="add_dislike")
     */

    public function addDisLike (EvenementRepository $repository,DislikeRepository  $repositoryLike,Request $request, EntityManagerInterface $em) {
        $dislike = new Dislike() ;
        $article = $repository->findOneBy(['id' => $request->get('id')]);
        //  $liked = $repositoryLike->findOneBy(['article' => $article,'utilisateur'=>$currentUser]);
        //dump($liked);die();
        $dislike->setEvenement($article);
        // $like->setUtilisateur($currentUser);
        // if($liked == null){
        $em->persist($dislike);
        // }else{
        //  $em->remove($liked);
        // }
        $em->flush();
        return $this->redirectToRoute('afficherevenementclient', ['id' =>$request->get('id')]);

    }

    /**
     * @Route ("/evenement/{id}/jaime",name="evenement_jaime")
     * @param Evenement $evenement
     * @param EntityManagerInterface $manager
     * @param EvenementLikeRepository $jaimeRepository
     * @return Response
     */
   public function like(Evenement $evenement , EntityManagerInterface $manager, EvenementLikeRepository $jaimeRepository, Request $request):Response
   {
        //$user=$this->getUserid();
        $user=$this->getUser();
       // $userid = 4;

       if (!$user) return $this->json(['code'=>403,'message'=>"unauthorized"],403);

         if ($evenement->isLikeByUser($user)){
             $jaime=$jaimeRepository->findOneBy(['evenement'=>$evenement , 'user'=>$user]);
             $manager->remove($jaime);
             $manager->flush();

             return $this->json([
                 'code'=>200,
                 'message'=>'Like bien supprimé',
                 'likes' => $jaimeRepository->count(['evenement'=>$evenement])
             ],200);
         }

         $jaime= new EvenementLike();
         $jaime->setEvenement($evenement)->setUser($user);
         $manager->persist($jaime);
         $manager->flush();

         return $this->json(['code'=> 200 ,
             'message'=> 'Like bien ajoutee',
             'likes'=>$jaimeRepository->count(['evenement'=>$evenement])
         ],200);

   }
       /**
     * @Route("/evenement", name="evenement")
     */
    public function index(EvenementRepository $repository, PaginatorInterface $paginator, Request $request)
    {
        $evenement=$repository->findAll();
        $e = $paginator->paginate(
            $evenement,
            $request->query->getInt('page', 1),
            3
        );
        return $this->render('evenement/index.html.twig',['evenement'=>$e]);
    }

    /**
     * @Route("/evenement/detail/{id}", name="evenement_detail")
     */
    public function DetaillEvent(Request $request, EvenementRepository $repository, $id,\Swift_Mailer $mailer): Response
    {
        $evenement = $repository->findOneBy(['id' => $id]);
        if(!$evenement){
            throw new NotFoundHttpException('Pas d événement trouvée');
        }

        $paiement = new Paiement();

        $user=$this->getUser();       
        $paiement->setUser($user);

         // On génère le formulaire
         $paiementForm = $this->createForm(ReservationType::class, $paiement);
         $paiementForm->add('prix', EntityType::class, [
            'class' => Evenement::class,
            'placeholder' => $evenement->getPrixEvent(),
            'attr' => array('readonly' => true),
            
        ]);
         $paiementForm->add('Reserver',SubmitType::class); 
         $paiementForm->handleRequest($request);
         if($paiementForm->isSubmitted() && $paiementForm->isValid()){
            
             $paiement->setEvenement($evenement);
           
             $paiement->setPrix($evenement->getPrixEvent());
             $em = $this->getDoctrine()->getManager();
             $em->persist($paiement);
             $em->flush();

             $message = (new \Swift_Message('New'))

             ->setFrom('azizhannachi98@gmail.com')
 
             ->setTo($paiement->getEmail())
             
             ->setSubject('Réservation bien faite')
             ->setBody('Bonjour Monsieur '.$paiement->getUser()->getNom().'. '.
             'Vous avez faite une réservation pour l evenement '.$evenement->getNomEvent().
             '. Nous allons vous envoyer un email concernant le status de votre réservation dans les prochaines 48h au plutard'. 
             '. Merci pour votre confiance!' );
             
  
    
             $mailer->send($message); 
 
             return $this->redirectToRoute('evenement_detail', ['id' => $evenement->getId()]);
         }


        return $this->render('evenement/details.html.twig',[
            'evenement'=>$evenement,
            'paiementForm' => $paiementForm->createView(),
        ]);
    }

     
            

}
