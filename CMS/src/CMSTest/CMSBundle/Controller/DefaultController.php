<?php

namespace CMSTest\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CMSTest\CMSBundle\Entity\Task;
use CMSTest\CMSBundle\CMS;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function newAction()
    {
        return $this->render('default/index.html.twig');
    }
}
