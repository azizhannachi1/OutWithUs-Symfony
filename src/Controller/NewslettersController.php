<?php

namespace App\Controller;

use App\Entity\Newsletters\Newsletters;
use App\Entity\Newsletters\Usersn;
use App\Form\NewslettersUsersType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sension\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use App\Form\NewslettersType;
use App\Repository\Newsletters\NewslettersRepository;

/**
     * @Route("/newsletters", name="newsletters_")
     */
class NewslettersController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, MailerInterface $mailer): Response
    {

        $user = new Usersn();
        $form = $this->createForm(NewslettersUsersType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $token = hash('sha256', uniqid());

            $user->setValidationToken($token);
            $date = new \DateTime('now');

            $user->setCreatedAt($date);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();


            
            $email = (new TemplatedEmail())
            ->from('azizhannachi98@gmail.com')
            ->to($user->getEmail())
            ->subject('Votre inscription à la newsletter')
            ->htmlTemplate('emails/inscription.html.twig')
            ->context(compact('user', 'token'))
            ;

        $mailer->send($email);

            $this->addFlash('message', 'Inscription en attente de validation');
            return $this->redirectToRoute('newsletters_home');
        }

        return $this->render('newsletters/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

      /**
     * @Route("/confirm/{id}/{token}", name="confirm")
     */
    public function confirm(Usersn $user, $token): Response
    {
        if($user->getValidationToken() != $token){
            throw $this->createNotFoundException('Page non trouvée');
        }

        $user->setIsValid(true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $this->addFlash('message', 'Compte activé');

        return $this->redirectToRoute('app_mains');
    }

    /**
     * @Route("/prepare", name="prepare")
     */
    public function prepare(Request $request): Response
    {
        $newsletter = new Newsletters();
        $form = $this->createForm(NewslettersType::class, $newsletter);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($newsletter);
            $em->flush();

            return $this->redirectToRoute('newsletters_list');
        }

        return $this->render('newsletters/prepare.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/list", name="list")
     */
    public function list(NewslettersRepository $newsletters): Response
    {
        return $this->render('newsletters/list.html.twig', [
            'newsletters' => $newsletters->findAll()
        ]);
    }

    
    /**
     * @Route("/send/{id}", name="send")
     */
    public function send(Newsletters $newsletter, MailerInterface $mailer): Response
    {
        $users = $newsletter->getCategoriesn()->getUsersn();

        foreach($users as $user){
            if($user->getIsValid()){

                $email = (new TemplatedEmail())
            ->from('azizhannachi98@gmail.com')
            ->to($user->getEmail())
            ->subject($newsletter->getName())
            ->htmlTemplate('emails/newsletter.html.twig')
            ->context(compact('newsletter', 'user'))
            ;

        $mailer->send($email);
            }
        }

     $newsletter->setIsSent(true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($newsletter);
         $em->flush();

        return $this->redirectToRoute('newsletters_list');
    }

    /**
     * @Route("/unsubscribe/{id}/{newsletter}/{token}", name="unsubscribe")
     */
    public function unsubscribe(Usersn $user, Newsletters $newsletter, $token): Response
    {
        if($user->getValidationToken() != $token){
            throw $this->createNotFoundException('Page non trouvée');
        }

        $em = $this->getDoctrine()->getManager();

        if(count($user->getCategoriesn()) > 1){
            $user->removeCategory($newsletter->getCategoriesn());
            $em->persist($user);
        }else{
            $em->remove($user);
        }
        $em->flush();

        $this->addFlash('message', 'Newsletter supprimée');

        return $this->redirectToRoute('app_mains');
    }
}
