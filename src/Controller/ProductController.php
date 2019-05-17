<?php
// src/Controller/ProductController.php
namespace App\Controller;

use App\Entity\Product;
use App\Form\Type\ProductType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/create", name="product_create")
     */
    public function create(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);    
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $product = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_list');
        }

        return $this->render('product/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/list", name="product_list")
     */
    public function list()
    {
        return $this->render('product/list.html.twig');
    }

    /**
     * @Route("/product/{slug}/edit", name="product_edit")
     */
    public function edit($slug)
    {
        return $this->render('product/edit.html.twig');
    }
}

?>