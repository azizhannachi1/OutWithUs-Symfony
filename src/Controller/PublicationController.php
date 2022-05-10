<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PublicationType;
use App\Entity\Publication;
use App\Repository\PublicationRepository;
use App\Repository\PublicationLikeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sension\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\Comments;
use App\Form\CommentsType;
use App\Repository\CommentsRepository;
use App\Entity\PublicationLike;
use App\Repository\UserRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class PublicationController extends AbstractController
{
    /**
     * @Route("/publication", name="publication")
     */
    public function index(PublicationRepository $repository, PaginatorInterface $paginator, Request $request)
    {
        $publication=$repository->findAll();

        $e = $paginator->paginate(
            $publication,
            $request->query->getInt('page', 1),
            3
        );
        return $this->render('publication/index.html.twig',['publication'=>$e]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\\HttpFoundation\Response
     * @Route("publication/Add", name="ajouter_publication")
     */
    function Add(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository){
        $publication = new Publication();
        $user=$this->getUser();       
        $publication->setUser($user);
        $form=$this->createForm(PublicationType::class,$publication);
        $form->add('Ajouter',SubmitType::class,
    ['attr'=>['formnovalidate'=> 'formnovalidate']]); 
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){

              /** @var UploadedFile $imageFile */
              $imageFile = $form->get('image')->getData();

              if ($imageFile) {
                  $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                  $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                  try {
                      $imageFile->move(
                          $this->getParameter('images_directory'),
                          $newFilename
                      );
                  } catch (FileException $e) {
                      // ... handle exception if something happens during file upload
                  }
                  $publication->setImage($newFilename);
                  $date = new \DateTime('now');
            $publication->setDate($date);
                  $entityManager->persist($publication);
                  $entityManager->flush();
                  $this->addFlash('message', 'la publication a bien ete ajouter ');
                  return $this->redirectToRoute('publication');
              } else {
                $date = new \DateTime('now');
                $publication->setDate($date);
                  $entityManager->persist($publication);
                  $entityManager->flush();
                  $this->addFlash('message', 'la publication a bien ete ajouter ');
                  return $this->redirectToRoute('publication');
              }

        }
        return $this->render('publication/Add.html.twig',[
            'f'=>$form->createView()
        ]);
            }

            
/**
     * @Route("publication/edit/{id}", name="publication_edit")
     */
    public function edit(Request $request, Publication $publication, EntityManagerInterface $entityManager, $id, PublicationRepository $repository, UserRepository $userRepository): Response
    {
        $publication=$repository->find($id);
        $user=$this->getUser();       
        $publication->setUser($user);
        $form = $this->createForm(PublicationType::class, $publication);
        $form->add('Modifier',SubmitType::class,
    ['attr'=>['formnovalidate'=> 'formnovalidate']]); 
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
              /** @var UploadedFile $imageFile */
              $imageFile = $form->get('image')->getData();

              if ($imageFile) {
                  $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                  $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                  try {
                      $imageFile->move(
                          $this->getParameter('images_directory'),
                          $newFilename
                      );
                  } catch (FileException $e) {
                      // ... handle exception if something happens during file upload
                  }
                  $publication->setImage($newFilename);
                  $date = new \DateTime('now');
                $publication->setDate($date);
                  $entityManager->persist($publication);
                  $entityManager->flush();
                 
                  return $this->redirectToRoute('publication');
              } else {
                $date = new \DateTime('now');
                $publication->setDate($date);
                  $entityManager->persist($publication);
                  $entityManager->flush();
               
                  return $this->redirectToRoute('publication');
              }
        }

        return $this->render('publication/edit.html.twig', [
            'publication' => $publication,
            'f' => $form->createView(),
        ]);
    }


    /**
     * @param PublicationRepository $repository
     * @return \Symfony\Component\\HttpFoundation\Response
     * @Route("/AffichePublication", name="AffichePublication")
     */
    public function Affiche(PublicationRepository $repository){
        
        $publication=$repository->findAll();
        return $this->render('publication/Affiche.html.twig',['publication'=>$publication]);
    }

        /**
         * @param $id
         * @param $repository
         * @return \Symfony\Component\\HttpFoundation\RedirectResponse
     * @Route("publication/Delete/{id}", name="delete_pub")
     */
    public function Delete($id, PublicationRepository $repository){
        $publication=$repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($publication);
        $em->flush();
        return $this->redirectToRoute('AffichePublication');
            }


               /**
     * @param PublicationRepository $repository
     * @param CommentsRepository $commentrepository
     * @return \Symfony\Component\\HttpFoundation\Response
     * @Route("publication/details/{id}", name="details_publication")
     */
    public function Details(PublicationRepository $repository, $id, Request $request, CommentsRepository $commentrepository, UserRepository $userRepository)
    {
        
       // $publication = $repository->findOneBy(['id' => $id]);
        $publication= $this->getDoctrine()->getManager()
        ->getRepository(Publication::class)
        ->find($request->get("id"));

        if(!$publication){
            throw new NotFoundHttpException('Pas de publication trouvée');
        }


        // Partie commentaires
        // On crée le commentaire "vierge"
        $comment = new Comments();


        $user=$this->getUser();       
        $comment->setUser($user);


        // On génère le formulaire
        $commentForm = $this->createForm(CommentsType::class, $comment);
        $commentForm->add('Ajouter',SubmitType::class); 
        $commentForm->handleRequest($request);
        if($commentForm->isSubmitted() && $commentForm->isValid()){
            $date = new \DateTime('now');          
            $comment->setCreatedAt($date);
            $comment->setPublications($publication);
            $comment->setUserId(24);

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('details_publication', ['id' => $publication->getId()]);
        }

        //parti modification du commentaire


        //$commentaire=$commentrepository->find($idComment);
        $commentaire=$this->getDoctrine()->getManager()->getRepository(Comments::class)->find($request->get("id"));
        $form=$this->createForm(CommentsType::class,$commentaire);
        $form->add('Update',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('details_publication', ['id' => $publication->getId()]);
        }
        
        // Fin partie modifier commentaire






  //parti Suppression du commentaire

        //$comments=$this->getDoctrine()->getManager()->getRepository(Comments::class)->find($request->get("id"));
        //$comments=$this->getDoctrine()->getManager()->getRepository(Comments::class)->find($request->get("id"));
        /*$comments=$commentrepository->find($idcomment);
        $deleteForm=$this->createForm(CommentsType::class,$comments);
        $deleteForm->add('supprimer',SubmitType::class);
        $deleteForm->handleRequest($request);
        if($deleteForm->isSubmitted() && $deleteForm->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->remove($comments);
            $em->flush();
            return $this->redirectToRoute('details_publication', ['id' => $publication->getId()]);
        }*/
        
        // Fin partie Suppression commentaire




        return $this->render('publication/details.html.twig',[
            'publication'=>$publication,
            'commentForm' => $commentForm->createView(),
            'form' => $form->createView()
        ]);
    }


     /**
     * @Route("/Supprimer/{id}", name="supprimer_commentaire")
     */
    public function DeleteComment($id, CommentsRepository $repository, Request $request){
        $commentaire=$repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($commentaire);
        $em->flush();
      
        return $this->redirectToRoute('publication');
    }

    /**
     * @Route("publication/update/{id}",name="update_comment")
     */
    function Update(CommentsRepository $repository, $id, Request $request){
        $commentaire=$repository->find($id);
        $form=$this->createForm(CommentsType::class,$commentaire);
        $form->add('Modifier',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('publication');
        }
        return $this->render('publication/update.html.twig',[
            'f'=>$form->createView()
        ]);
    }

    
    /**
     * @Route ("/publication/{id}/like",name="publication_like")
     * @param Publication $publication
     * @param EntityManagerInterface $manager
     * @param PublicationLikeRepository $likeRepository
     * @return Response
     */
   public function like(Publication $publication , EntityManagerInterface $manager, PublicationLikeRepository $likeRepository, Request $request):Response
   {
        //$user=$this->getUserid();
        $user=$this->getUser();
       // $userid = 4;

       if (!$user) return $this->json(['code'=>403,'message'=>"unauthorized"],403);

         if ($publication->isLikeByUser($user)){
             $like=$likeRepository->findOneBy(['publication'=>$publication , 'user'=>$user]);
             $manager->remove($like);
             $manager->flush();

             return $this->json([
                 'code'=>200,
                 'message'=>'Like bien supprimé',
                 'likes' => $likeRepository->count(['publication'=>$publication])
             ],200);
         }

         $like= new PublicationLike();
         $like->setPublication($publication)->setUser($user);
         $manager->persist($like);
         $manager->flush();

         return $this->json(['code'=> 200 ,
             'message'=> 'Like bien ajoutee',
             'likes'=>$likeRepository->count(['publication'=>$publication])
         ],200);

   }

  /**
     * @param CommentsRepository $repository
     * @return \Symfony\Component\\HttpFoundation\Response
     * @Route("/AfficheComments", name="AfficheComments")
     */
    public function AfficheComments(CommentsRepository $repository){
        
        $commentaires=$repository->findAll();
        return $this->render('publication/AfficheComments.html.twig',['commentaires'=>$commentaires]);
    }
    


}
