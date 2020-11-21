<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ProductType;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
class FormController extends AbstractController
{
    /**
     * @Route("/form", name="form")
     */
    public function index(Request $request): Response
   {	$product=new Product();
   	
   	$form=$this->createForm(ProductType::class,$product,
      ['action'=> $this->generateUrl('form')
      ]);

   	//handle the request
    $form->handleRequest($request);
      if($form->isSubmitted() && $form->isValid())
      { 
      //storing to the database
        $entityManager=$this->getDoctrine()->getManager();
        $entityManager->persist($product);
        $entityManager->flush();
      }
        return $this->render('form/index.html.twig', [
            'product_form' => $form->createView()
        ]);
    }
      /**
      * @Route("/form/remove/{id}", name="remove")
      */
    public function remove(Request $request, $id):Response
    {
       $entityManager=$this->getDoctrine()->getManager();
       $product=$entityManager->getRepository(Product::class)->findOneBy(
        [ 'id'=> $id
        ]);
       $form=$this->createForm(ProductType::class, $product,[
        'action'=>$this->generateUrl('form')
       ]);
       $form->handleRequest($request);
       if($form->isSubmitted() && $form->isValid())
       {
        //$entityManager->remove($product);
        //$entityManager->flush();
        $entityManager=$this->getDoctrine()->getManager();
        $entityManager->persist($product);
        $entityManager->flush();
       }
       $entityManager->remove($product);
      $entityManager->flush();
       
       return $this->render('form/index.html.twig', [
            'product_form' => $form->createView()]);
    }
    /**
    * @Route("/showProduct/{id}", name="show_product")
    */
    public function showProduct(Request $request, Product $product)
    {
       return $this->render('form/show_product.html.twig', [
            'product' => $product]);
    }
}
