<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mine\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use App\Form\ForgotPasswordType;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SecurityController extends AbstractController
{
    /**
     * @Route("/security", name="app_security")
     */
    public function index(): Response
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): \Symfony\Component\HttpFoundation\RedirectResponse
    {
         return $this->redirectToRoute('app_login'); 
    }

     /**
     * @Route("/forgot", name="forgot")
     */
    public function forgotPassword(Request $request, UserRepository $repository, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator)
    {
                // On crée le formulaire
                $form = $this->createForm(ForgotPasswordType::class);

                // On traite le formulaire
                $form->handleRequest($request);
        
                // Si le formulaire est valide
                if($form->isSubmitted() && $form->isValid()){
                    // On récupère les données
                    $donnees = $form->getData();
        
                    // On cherche si un utilisateur a cet email
                    $user = $repository->findOneByEmail($donnees['email']);
        
                    // Si l'utilisateur n'existe pas
                    if(!$user){
                        // On envoie un message flash
                        $this->addFlash('danger', 'Cette adresse n\'existe pas');
        
                        return $this->redirectToRoute('app_login');
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
                        return $this->redirectToRoute('app_login');
                    }
        
                    // On génère l'URL de réinitialisation de mot de passe
                    $url = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
        
                    // On envoie le message
                    $message = (new \Swift_Message('Mot de passe oublié'))
                    ->setFrom('azizhannachi98@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                        "<p>Bonjour,</p><p>Une demande de réinitialisation de mot de passe a été effectuée pour le site OutWithUs. Veuillez cliquer sur le lien suivant : " . $url .'</p>',
                        'text/html'
                        )
                    ;
        
                    // On envoie l'e-mail
                    $mailer->send($message);
        
                    // On crée le message flash
                    $this->addFlash('message', 'Un e-mail de réinitialisation de mot de passe vous a été envoyé');
        
                    return $this->redirectToRoute('app_login');
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
            return $this->redirectToRoute('app_login');
        }

        
        if($request->isMethod('POST')){
            
            $user->setResetToken(null);

            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('message', 'Mot de passe modifié avec succès');

            return $this->redirectToRoute('app_login');
        }else{
            return $this->render('security/reset_password.html.twig', ['token' => $token]);
        }

    }
   

  /**
     * @Route("user/edit/{id}", name="user_edit")
     */
    public function editUser(Request $request, User $user, EntityManagerInterface $entityManager, $id, UserRepository $repository, UserPasswordEncoderInterface $encoder): Response
    {
        $user=$repository->find($id);
        $form = $this->createForm(UserType::class, $user);
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
                  $user->setImage($newFilename);
                  $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
                  $entityManager->persist($user);
                  $entityManager->flush();
                 
                  return $this->redirectToRoute('app_main');
              } else {
                $hash = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($hash);
                  $entityManager->persist($user);
                  $entityManager->flush();
               
                  return $this->redirectToRoute('app_main');
              }
        }

        return $this->render('security/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    

    /**
     * @Route("/user/detail/{id}", name="user_detail")
     */
    public function DetaillUser(Request $request, UserRepository $userrepo, $id): Response
    {
        $user = $userrepo->findOneBy(['id' => $id]);
        if(!$user){
            throw new NotFoundHttpException('Pas d utilisateur trouvée');
        }
        return $this->render('security/details.html.twig',[
            'user'=>$user
        ]);
    }

     /**
     * @param UserRepository $repository
     * @return \Symfony\Component\\HttpFoundation\Response
     * @Route("/AfficheUser", name="AfficheUser")
     */
    public function Affiche(UserRepository $repository, Request $request, PaginatorInterface $paginator){
         
        $user=$repository->findAll();
        
        $e = $paginator->paginate(
            $user,
            $request->query->getInt('page', 1),
            3
        );
      

       
        return $this->render('security/Affiche.html.twig', [
            'user' => $e,

        ]);
    }
}
