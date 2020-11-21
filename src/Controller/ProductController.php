<?php

// src/Controller/ProductController.php
namespace App\Controller;

// ...
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController extends AbstractController
{ 
    /**
    *@Route("/index", name="index")
    */
    public function index()
    {
        return $this->render('base.html.twig' ,['controller_name'=> 'HomeController',]);
    }

    /**
    * @Route("/helloUser/{name}", name="helloUser")
    */
    public function helloUser(Request $request, $name)
    { $form = $this->createFormBuilder()
        ->add('fullname')
        ->getForm()
        ;
     $person = [ 'name'=>'marilena',
                'lastname'=>'dobrovolschi',
                'age'=> 23];
       

    	  
        $product = new Product();
        // This will trigger an error: the column isn't nullable in the database
        $product->setName('Coconut Pasta');
        // This will trigger a type mismatch error: an integer is expected
        $product->setPrice(65);
 		$product->setDescription('So good!');
         $entityManager = $this->getDoctrine()->getManager();
         $retrievedProduct =$entityManager->getRepository(Product::class)->findOneBy(
            [   'id'=> 1
            ]);
         var_dump($retrievedProduct);
 		//$entityManager->persist($product);
 		// $entityManager->flush();
 return $this->render('greet.html.twig', [
            'person'=> $person,
            'product' =>$retrievedProduct,
            'user_form' =>$form->createView()   ]);


    }

    /**
 * @Route("/product/{id}", name="product_show")
 */
public function show($id)
{
    $product = $this->getDoctrine()
        ->getRepository(Product::class)
        ->find($id);

    if (!$product) {
        throw $this->createNotFoundException(
            'No product found for id '.$id
        );
    }

    return new Response('Check out this great product: '.$product->getName());

}

/**
 * @Route("/product/edit/{id}", name="product_edit")
 */
public function update($id)
{
    $entityManager = $this->getDoctrine()->getManager();
    $product = $entityManager->getRepository(Product::class)->find($id);

    if (!$product) {
        throw $this->createNotFoundException(
            'No product found for id '.$id
        );
    }

    $product->setName('Incaltaminte!');
    $entityManager->flush();
  

    return $this->redirectToRoute('product_show', [
        'id' => $product->getId()
    ]);
}
 
 /**
  *@Route("/product/delete/{id}", name="product_delete")
  */
 public function delete($id)
 {
 	$product = $this->getDoctrine()->getRepository(Product::class)->find($id);
 	$entityManager=$this->getDoctrine()->getManager();

 	$entityManager->remove($product);
 	$entityManager->flush();
 	return new Response('Deleted product with id '.$id);
 }
 /**
 *@Route("/list", name="product_list")
 */
 public function showAll()
 {
 	$products = $this->getDoctrine() ->getRepository(Product::class)->findAll();
 	return $this->render('product/list.html.twig',['products'=>$products,]);
 }

}