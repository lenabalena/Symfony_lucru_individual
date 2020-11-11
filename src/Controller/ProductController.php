<?php

// src/Controller/ProductController.php
namespace App\Controller;

// ...
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="create_product")
     */
     public function createProduct(ValidatorInterface $validator): Response
    { 	   $entityManager = $this->getDoctrine()->getManager();
        $product = new Product();
        // This will trigger an error: the column isn't nullable in the database
        $product->setName('Shoes');
        // This will trigger a type mismatch error: an integer is expected
        $product->setPrice('1999');
 		$product->setDescription('Ergonomic and stylish!');
 		$entityManager->persist($product);
 		 $entityManager->flush();

        return new Response('Saved new product with id '.$product->getId());

        $errors = $validator->validate($product);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }


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
 * @Route("/product/edit/{id}", name="product_update")
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
}