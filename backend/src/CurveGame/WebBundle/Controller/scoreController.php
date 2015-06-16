<?php

namespace CurveGame\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ScoreController extends Controller {

    /**
     * Shows a page with the ten players with the highest scores of the current day.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction() {

        //custom query voor het ophalen van de score
        $em = $this->getDoctrine()->getManager();
        $playerRepo = $em->getRepository('CurveGameEntityBundle:Player');
        $players = $playerRepo->findByTopTenScore();

        return $this->render('default/highscore.html.twig', array(
            'Users' => $players,
        ));
    }
}