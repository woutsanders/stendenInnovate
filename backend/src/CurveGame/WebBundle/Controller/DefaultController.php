<?php

namespace CurveGame\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CurveGameWebBundle:Default:index.html.twig', array('name' => $name));
    }
}
