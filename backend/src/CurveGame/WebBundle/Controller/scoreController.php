<?php

namespace CurveGame\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ScoreController extends Controller {

    public function indexAction() {

        //Bepalen welke dag het vandaag is, zodat alleen de score van vandaag geshowed wordt
        $dt = new \DateTime(time());
        $day = $dt->format('Y-m-d');
        $startTime = strtotime($day);
        $dayTime = 86400;
        $endTime = $startTime + $dayTime;

        //custom query voor het ophalen van de score
        $em = $this->getDoctrine()->getManager();
        $query = $em->CreateQuery(
            "SELECT p
                FROM CurveGameEntityBundle:Highscore p
                WHERE p.DateTime > $startTime AND p.DateTime < $endTime"
        );
        $highscore = $query->getResult();

        //Resultaat query als array naar view omgeving doorgeven
        return $this->render('default/highscore.html.twig', array('Users' => $highscore));
    }
}