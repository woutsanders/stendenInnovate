<?php

namespace CurveGame\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CurveGameApiBundle:Default:index.html.twig', array('name' => $name));
    }
}
