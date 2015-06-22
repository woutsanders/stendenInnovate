<?php

namespace CurveGame\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller {

    public function indexAction() {

        return $this->render('player/index.html.twig');
    }
}