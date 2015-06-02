<?php

namespace Pietje\JantjeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('PietjeJantjeBundle:Default:index.html.twig', array('name' => $name));
    }
}
