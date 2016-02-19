<?php

namespace ErImageBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class IndexController extends Controller
{
    public function indexAction(Request $request)
    {              
        return $this->render(
            'ErImageBundle:indexIndex.html.twig',
                array('tab'=>false)
            );
    }
}
