<?php

namespace App\Controller;

use App\Entity\User;
use App\Notifications\CreationCompteNotification;
use App\Form\ResetPasswordRequestFormType;
use App\Form\RegistrationType;
use App\Notifications\ActivationCompteNotification;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SecurityController extends AbstractController
{      
    /**
     * @var CreationCompteNotification
     */
    private $notify_creation;

    /**
     * @var ActivationCompteNotification
     */
    private $notify_activation;

    public function __construct(CreationCompteNotification $notify_creation, ActivationCompteNotification $notify_activation )
    {
        $this-> notify_creation = $notify_creation;
        $this-> notify_activation = $notify_activation;
    }
       
    

    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer) {
        
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

           // if($imageFile){
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
                $user->setImage($newFilename);

                $hash = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($hash);

                $user->setActivationToken(md5(uniqid()));

                $manager->persist($user);
                $manager->flush();

                //envoie le mail d'insci au admin
                $this->notify_creation->notify();
                
                //envoie le mail d'activation
                $this->notify_activation->notify($user);

                

                return $this->redirectToRoute('security_login');
          }
        
        return $this->render('security/registration.html.twig',
    [
        'form' => $form->createView()
    ]);
    }

     /**
     * @Route("/activation/{token}", name="activation")
     */
    public function activation($token, UserRepository $usersRepo){
        // On vérifie si un utilisateur a ce token
        $user = $usersRepo->findOneBy(['activation_token' => $token]);

        // Si aucun utilisateur n'existe avec ce token
        if(!$user){
            // Erreur 404
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }

        // On supprime le token
        $user->setActivationToken(null);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // On envoie un message flash
        $this->addFlash('message', 'Vous avez bien activé votre compte');

        // On retoure à login
        return $this->redirectToRoute('security_login');
    }

    /**
     * @Route("/oubli_pass", name="forgotten_password")
     */
    public function forgottenPass(Request $request, UserRepository $usersRepo, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator){
        // On crée le formulaire
        $form = $this->createForm(ResetPasswordRequestFormType::class);

        // On traite le formulaire
        $form->handleRequest($request);

        // Si le formulaire est valide
        if($form->isSubmitted() && $form->isValid()){
            // On récupère les données
            $donnees = $form->getData();

            // On cherche si un utilisateur a cet email
            $user = $usersRepo->findOneByEmail($donnees['email']);

            // Si l'utilisateur n'existe pas
            if(!$user){
                // On envoie un message flash
                $this->addFlash('danger', 'Cette adresse n\'existe pas');

                return $this->redirectToRoute('security_login');
            }

            // On génère un token
            $token = $tokenGenerator->generateToken();

            try{
                $user->setResetToken($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            }catch(\Exception $e){
                $this->addFlash('warning', 'Une erreur est survenue : '. $e->getMessage());
                return $this->redirectToRoute('security_login');
            }

            // On génère l'URL de réinitialisation de mot de passe
            $url = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

            // On envoie le message
            $message = (new \Swift_Message('Mot de passe oublié'))
            ->setFrom('azizhannachi98@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(
                "<p>Bonjour,</p><p>Une demande de réinitialisation de mot de passe a été effectuée pour le site Travel Me. Veuillez cliquer sur le lien suivant : " . $url .'</p>',
                'text/html'
                )
            ;

            // On envoie l'e-mail
            $mailer->send($message);

            // On crée le message flash
            $this->addFlash('message', 'Un e-mail de réinitialisation de mot de passe vous a été envoyé');

            return $this->redirectToRoute('security_login');
        }

        // On envoie vers la page de demande de l'e-mail
        return $this->render('security/forgotten_password.html.twig', ['emailForm' => $form->createView()]);
    }


     /**
     * @Route("/reset_pass/{token}", name="app_reset_password")
     */
    public function resetPassword($token, Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager){
        // On cherche l'utilisateur avec le token fourni
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['reset_token' => $token]);

        if(!$user){
            $this->addFlash('danger', 'Token inconnu');
            return $this->redirectToRoute('security_login');
        }

        
        if($request->isMethod('POST')){
            
            $user->setResetToken(null);

            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('message', 'Mot de passe modifié avec succès');

            return $this->redirectToRoute('security_login');
        }else{
            return $this->render('security/reset_password.html.twig', ['token' => $token]);
        }

    }

    /**
     * @Route("/connexion", name="security_login")
     */
    public function login() {
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout(){}

    

    
}
