<?php
// src/AppBundle/Controller/HelloController.php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HelloController  extends Controller
{
    /**
     * Route("/hello/{name}", name="hello")
     */
    public function indexAction($firstName, $lastName,  Request $request)
    {
      $page = $request->query->get('page', 1);
      return new Response('<html><body>Hello '.ucwords($firstName . ' ' . $lastName).'!</body></html>');
    }
}
