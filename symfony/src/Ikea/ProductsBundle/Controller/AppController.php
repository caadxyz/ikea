<?php

namespace Ikea\ProductsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ikea\ProductsBundle\Entity\Product;

class AppController extends Controller
{
    public function indexAction()
    {
    	$repo = $this->getDoctrine()->getManager()->getRepository('IkeaProductsBundle:Product');
        $productsList = $repo->findAll();
        if ($productsList === null) {
            return $this->render('IkeaProductsBundle:404:404.html.twig');
        }
        else{
        	return $this->render('IkeaProductsBundle:Home:home.html.twig',array('productsList' => $productsList));
        }       
        
    }

    public function productAction($name)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository('IkeaProductsBundle:Product');
        $productDetails = $repo->find($name);
        if ($productDetails === null) {
            return $this->render('IkeaProductsBundle:404:404.html.twig');
        }
        else{
        	return $this->render('IkeaProductsBundle:Product:product.html.twig',array('productDetails' => $productDetails));
        }    
    }
}
