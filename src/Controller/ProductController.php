<?php
// src/Controller/ProductController.php
namespace App\Controller;

use App\Entity\Product;
use App\Form\Type\ProductType;
use App\Utils\UploadHelper;
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
            $this->processForm($form);
            
            return $this->redirectToRoute('product_edit', array('slug'=> $product->getSlug()));
            //TODO add message to show in list that confirms insert
            //return $this->redirectToRoute('product_list');
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
        //TODO ahiahiahi
        return $this->render('product/list.html.twig');
    }

    /**
     * @Route("/product/{slug}/edit", name="product_edit")
     */
    public function edit(Request $request, Product $product)
    {
        $form = $this->createForm(ProductType::class, $product);    
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->processForm($form);
            //TODO add message to show in list that confirms edit
            //return $this->redirectToRoute('product_list');
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    protected function processForm($form){
        $product = $form->getData();
        
        $uploadedFile = $form['imageFile']->getData();
        if($uploadedFile){
            //UploadHelper manages the logic to move the uploaded file
            $uploaderHelper = new UploadHelper($this->getParameter('kernel.project_dir'));
            $newFilename = $uploaderHelper->uploadImage($uploadedFile);
            $product->setImage($newFilename);
        }
        
        $tags = $product->getTag();
        foreach ($tags as $submitted) {
            if(!$submitted->getId()){
                $tag = $this->getDoctrine()->getRepository('App:Tag')->findOneBy(array('name' => $submitted->getName()));
                if ($tag) {
                    $product->removeTag($submitted);
                    $product->addTag($tag);
                }
            }    
        }
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($product);
        $entityManager->flush();
    }
}

?>