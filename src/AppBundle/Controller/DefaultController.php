<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Product;
use AppBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }
    
    public function createAction(Request $request)
    {
        $product = new Product();
        $product->setName($request->get('name','A Foo Bar'));
        $product->setPrice($request->get('price',19.99));
        $product->setDescription($request->get('description','Lorem ipsum dolor'));

        $em = $this->getDoctrine()->getManager();
        
        $category = $this->getDoctrine()
        ->getRepository('AppBundle:Category')
        ->find(1);
        
        $product->setCategory($category);

        $em->persist($product);
        $em->flush();

        return new Response('Created product id '.$product->getId());
    }
    
    public function showAction(Product $product)
    {
        
      /*
       * $product = $this->getDoctrine()
            ->getRepository('AppBundle:Product')
            ->find($id);
      */
        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $em = $this->getDoctrine()->getManager();
        $this->products = $em->getRepository('AppBundle:Product')
                       ->findAllOrderedById();

        $this->product = $product;
        
        foreach ($this as $var => $val) {
           $template_vars[$var] = $val;
        }

        return $this->render(
            'app/showProduct.html.twig',
            $template_vars
        );
    }
    
    public function updateAction(Request $request, Product $product)
{
    $em = $this->getDoctrine()->getManager();
    //$product = $em->getRepository('AppBundle:Product')->find($id);

    if (!$product) {
        throw $this->createNotFoundException(
            'No product found for id '.$id
        );
    }

    $product->setName($request->get('name','New product name!'))
            ->setPrice($request->get('price',0.01))
            ->setDescription($request->get('description','New product description!'));
    
    $category = $this->getDoctrine()
        ->getRepository('AppBundle:Category')
        ->find(1);
        
    $product->setCategory($category);
    
    $em->flush();

    return $this->redirectToRoute('show_product',array('id' => $product->getId()));
}
}
