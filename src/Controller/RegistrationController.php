<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mine\Email;
use App\Notifications\ActivationCompteNotification;
use App\Notifications\CreationCompteNotification;
use App\Repository\UserRepository;

class RegistrationController extends AbstractController
{
    private $passwordEncoder;

     /**
     * @var CreationCompteNotification
     */
    private $notify_creation;

    /**
     * @var ActivationCompteNotification
     */
    private $notify_activation;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, CreationCompteNotification $notify_creation, ActivationCompteNotification $notify_activation)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this-> notify_creation = $notify_creation;
        $this-> notify_activation = $notify_activation;
    }

   /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer) {
        
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
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
                $user->setRoles(['ROLE_USER']);
                $manager->persist($user);
                $manager->flush();

                //envoie le mail d'insci au admin
                $this->notify_creation->notify();
                
                //envoie le mail d'activation
                $this->notify_activation->notify($user);

                

                return $this->redirectToRoute('app_login');
          }

        
        return $this->render('registration/register.html.twig',
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
        return $this->redirectToRoute('app_login');
    }
}