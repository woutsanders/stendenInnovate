<?php

namespace CurveGame\WebBundle\Controller;

use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CurveGame\ApiBundle\Controller\userController;

class Index2Controller extends Controller {

    /**
     * Displays the index page.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */


    public function indexAction() {



        return $this->render('CurveGameWebBundle:Index:index2.html.twig');
    }
}