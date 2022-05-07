<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CategoriesResType;
use App\Entity\CategorieRes;
use App\Repository\CategorieResRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Histogram;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\CalendarChart;
use Symfony\Component\Validator\Constraints\DateTime;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ColumnChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\CandlestickChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Map;

class CategorieResController extends AbstractController
{
    /**
     * @Route("/categorieres", name="indexCategorires")
     */
    public function index(): Response
    {
        return $this->render('categorieres/index.html.twig');
    }

  
   /**
     * @param CategorieResRepository $repository
     * @return \Symfony\Component\\HttpFoundation\Response
     * @Route("/AfficheCat", name="AfficheCat")
     */
    public function Affiche(CategorieResRepository $repository){
        //$repo=$this->getDoctrine()->getRepository(Classroom::class);
        $categorieres=$repository->findAll();
        return $this->render('categorieres/Affiche.html.twig',['categorieres'=>$categorieres]);
    }

        /**
     * @Route("/Delete/{id}", name="s")
     */
    public function Delete($id, CategorieResRepository $repository){
        $categorieres=$repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($categorieres);
        $em->flush();
        
        return $this->redirectToRoute('AfficheCat');
            }


    /**
     * @Route("categorieres/modifier/{id}",name="upd")
     */
    function Update(CategorieResRepository $repository, $id, Request $request){
        $categorieres=$repository->find($id);
        $form=$this->createForm(CategoriesResType::class,$categorieres);
        $form->add('Update',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('AfficheCat');
        }
        return $this->render('categorieres/Update.html.twig',[
            'f'=>$form->createView()
        ]);
    }

 /**
     * @param Request $request
     * @return \Symfony\Component\\HttpFoundation\Response
     * @Route("categorieres/Add",name="ajouter_Categorie")
     */
    function Add(Request $request){
        $categorieres = new CategorieRes();
        $form=$this->createForm(CategoriesResType::class,$categorieres);
        $form->add('Ajouter',SubmitType::class,
    ['attr'=>['formnovalidate'=> 'formnovalidate']]); 
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($categorieres);
            $em->flush();
        return $this->redirectToRoute('AfficheCat');
        }
        return $this->render('categorieres/Add.html.twig',[
            'f'=>$form->createView()
        ]);
            }
           
}
