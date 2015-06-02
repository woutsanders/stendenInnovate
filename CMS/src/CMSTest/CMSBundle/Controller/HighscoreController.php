<?php

namespace CMSTest\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CMSTest\CMSBundle\Entity\Task3;
use CMSTest\CMSBundle\Overview;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use \DateTime;

class HighscoreController extends Controller
{
    public function newAction()
    {
        $time = time();
        $dt = new DateTime("@$time");
        $day = $dt->format('Y-m-d');
        $startTime = strtotime($day);
        $dayTime = 86400;
        $endTime = $startTime + $dayTime;


        $em = $this->getDoctrine()->getManager();
        $query = $em->CreateQuery(
            "SELECT p
                FROM CMSTestCMSBundle:Task3 p
                WHERE p.DateTime > $startTime AND p.DateTime < $endTime"
        );
        $highscore = $query->getResult();

        //$task = $em->getRepository('CMSTest\CMSBundle\Entity\Task3')->findAll();
        return $this->render('default/highscore.html.twig', array('Users' => $highscore));
    }
}
