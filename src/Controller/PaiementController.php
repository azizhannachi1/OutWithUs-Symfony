<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Paiement;
use App\Repository\PaiementRepository;
use App\Form\PaiementType;
use App\Services\QrcodeService;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Dompdf\Dompdf;
use Dompdf\Options;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Margin\Margin;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class PaiementController extends AbstractController
{
    /**
     * @Route("/paiement", name="app_paiement")
     */
    public function index(): Response
    {
        return $this->render('paiement/index.html.twig', [
            'controller_name' => 'PaiementController',
        ]);
    }


    /**
     * @param PaiementRepository $repository
     * @return \Symfony\Component\\HttpFoundation\Response
     * @Route("/AfficheP", name="AfficheP")
     */
    public function Affiche(PaiementRepository $repository){
       
        $paiement=$repository->findAll();
        return $this->render('paiement/list.html.twig',['paiement'=>$paiement]);
    }

     /**
     * @Route("paiement/Update/{id}",name="u")
     */
    function Update(PaiementRepository $repository, $id, Request $request){
        $paiement=$repository->find($id);
        $form=$this->createForm(PaiementType::class,$paiement);
        $form->add('status',ChoiceType::class,[
            'choices'=> array(
                'Pending'=>'Pending',
                'Vérifié'=>'Vérifié',
                'Annulé'=>'Annulé',
            ),
        ]);
       
        $form->add('Update',SubmitType::class);
       
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();

            $em->flush();
            return $this->redirectToRoute('AfficheP');
        }
        return $this->render('paiement/Update.html.twig',[
            'f'=>$form->createView()
        ]);
    }

      /**
       * @param PaiementRepository $repository
     * @Route("/paiement/data/download", name="paiement_data_download")
     */
    public function paiementDataDownload(PaiementRepository $repository)
    {
       
        //$paiement=$repository->find($id);
        //return $this->render('paiement/list.html.twig');
        $pdfOptions = new Options();

        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);

        $dompdf = new Dompdf($pdfOptions);
        $context = stream_context_create([
        'ssl' => [
           'verify_peer' => FALSE,
           'verify_peer-name' => FALSE,
           'allow_slef_signed' => TRUE
         ]
        ]);

        $dompdf->setHttpContext($context);

        $html = $this->renderView('paiement/download.html.twig', [
            'paiement' => $repository->findAll(),
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'Portrait');
        $dompdf->render();

        $fichier = 'Listes-des-paiements.pdf';

        $dompdf->stream($fichier, [
            'Attachement' => true
        ]);

        return new Response();
    }


 /**
     * @param PaiementRepository $repository    
     * @return \Symfony\Component\\HttpFoundation\Response
     * @Route("paiement/details/{id}", name="details_paiement")
     */
    public function Details(PaiementRepository $repository, $id, Request $request)
    {

        $paiement = $repository->findOneBy(['id' => $id]);
        

        $form=$this->createForm(PaiementType::class,$paiement);       
        $form->add('Generer',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $path = $this->getParameter('kernel.project_dir').'/public';
            $em= $this->getDoctrine()->getManager();
            
            $cli=$em->getRepository(Paiement::class)->findOneBy(["id"=>$request->get('id')]);
    
            $pathqr = $this->getParameter('kernel.project_dir').'/public/asset/img';
    
            $paiement = $repository->findOneBy(['id' => $id]);
    
            $result=Builder::create()
            ->writer(new PngWriter())
            ->data(" | Bonjour Mr/Mrs propiétaire du carte numéro:".$cli->getCarte()." | Avec addresse email: ".$cli->getEmail()." | Votre status de paiement est: ".$cli->getStatus())
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(300)
            ->margin(10)
            ->labelText("")
            ->logoPath($pathqr."/logo1.jpg")
            ->labelAlignment(new LabelAlignmentCenter())
            ->labelMargin(new Margin(15, 5, 5, 5))
            ->logoResizeToWidth('100')
            ->logoResizeToHeight('100')
            ->build();
    
    
        $namePng =uniqid('',''). '.png';
        $result->saveToFile( $pathqr.'/qr-code/'.$namePng);



        }



        if(!$paiement){
            throw new NotFoundHttpException('Pas de paiement trouvée');
        }
        return $this->render('paiement/details.html.twig',[
            'paiement'=>$paiement,
            'f'=>$form->createView()
        ]);
    }


    /**
       * @param PaiementRepository $repository
     * @Route("/paiement/data/telecharger/{id}", name="paiement_download")
     */
    public function paiementDownload(PaiementRepository $repository, $id)
    {
        $pdfOptions = new Options();

        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);

        $dompdf = new Dompdf($pdfOptions);
        $context = stream_context_create([
        'ssl' => [
           'verify_peer' => FALSE,
           'verify_peer-name' => FALSE,
           'allow_slef_signed' => TRUE
         ]
        ]);

        $dompdf->setHttpContext($context);

        $html = $this->renderView('paiement/download2.html.twig', [
            'paiement' => $repository->findOneBy(['id' => $id]),
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'Portrait');
        $dompdf->render();

        $fichier = 'Listes-des-paiements-par-user.pdf';

        $dompdf->stream($fichier, [
            'Attachement' => true
        ]);

        return new Response();
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\\HttpFoundation\Response
     * @Route("paiement/add",name="ajouter_paiement")
     */
    function Add(Request $request){
        $paiement = new Paiement();
        $form=$this->createForm(PaiementType::class,$paiement);
        $form->add('Ajouter',SubmitType::class,
    ['attr'=>['formnovalidate'=> 'formnovalidate']]); 
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($paiement);
            $em->flush();
        return $this->redirectToRoute('app_paiement');
        }
        return $this->render('paiement/add.html.twig',[
            'f'=>$form->createView()
        ]);
            }

}
