<?php

namespace CurveGame\WebBundle\Controller;

use \Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller {

    public function indexAction() {

        return $this->render('CurveGameWebBundle:Index:index.html.twig');
    }
}