<?php
// src/Controller/ProductController.php
namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Form\Type\ProductType;
use App\Utils\UploadHelper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/list", name="product_list")
     */
    public function list(Request $request)
    {
        $tagName = null;

        $form = $this->createFormBuilder()
            ->add('name', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Search'])
            ->getForm();    
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $tagName = $data['name'];
        }
        
        $products = $this->getDoctrine()->getRepository('App:Product')->findByTagName($tagName);
           
        return $this->render('product/list.html.twig', [
            'products' => $products, 'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/product/create", name="product_create")
     */
    public function create(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);    
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $product = $this->processForm($form);
            // set a nice success message
            $this->addFlash('success', sprintf('Product "%s" successfully created.', $product->getName()));
            return $this->redirectToRoute('product_list');
        }

        return $this->render('product/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/{slug}/edit", name="product_edit")
     */
    public function edit(Request $request, Product $product)
    {
        $form = $this->createForm(ProductType::class, $product);    
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $this->processForm($form);
            // set a nice success message
            $this->addFlash('success', sprintf('Product "%s" successfully saved.', $product->getName()));
            return $this->redirectToRoute('product_list');
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    protected function processForm($form){
        $product = $form->getData();
        
        $uploadedFile = $form['imageFile']->getData();
        if($uploadedFile){
            // UploadHelper manages the logic to move the uploaded file
            $uploaderHelper = new UploadHelper($this->getParameter('kernel.project_dir'));
            // returns the fresh filename, so we set it
            $newFilename = $uploaderHelper->uploadImage($uploadedFile);
            $product->setImage($newFilename);
        }

        // we need to manually check for Tags entity already in the db, to avoid INSERT of a duplicate unique name field
        // since those added in this submit are missing the id field
        $tags = $product->getTag();
        foreach ($tags as $submitted) {
            if(!$submitted->getId()){
                $tag = $this->getDoctrine()->getRepository('App:Tag')->findOneBy(array('name' => $submitted->getName()));
                // it's already in the Tag table, so we substitute the submitted entity
                if ($tag) {
                    $product->removeTag($submitted);
                    $product->addTag($tag);
                }
            }    
        }
        
        // save to db
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($product);
        $entityManager->flush();

        // return the updated entity in case we need its values
        return $product;
    }
}

?>