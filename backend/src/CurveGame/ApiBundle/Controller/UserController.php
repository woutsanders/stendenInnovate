<?php

namespace CurveGame\ApiBundle\Controller;

use CurveGame\EntityBundle\Entity\Player;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class UserController
 * @package CurveGame\ApiBundle\Controller
 */
class UserController extends Controller {

    /**
     * Registers the username and returns the username with status flag.
     *
     * @param Request $request
     * @return Response
     */
    public function registerAction(Request $request) {

        $username = $request->get('username');

        $em = $this->getDoctrine()->getManager();

        $statusRepo = $em->getRepository('CurveGameEntityBundle:Status');
        $status = $statusRepo->findOneByStatusName('waiting');

        $player = new Player();
        $player
            ->setUsername($username)
            ->setStatus($status)
            ->setTimestamp(time());

        $em->persist($player);
        $em->flush();

        $resp = array(
            "username"  => $username,
            "status"    => "OK",
        );

        return new Response(json_encode($resp), 200);
    }

    /**
     * A small test
     *
     * @param mixed $value
     * @return Response
     */
    public function testAction($value) {

        $arr = array(
            "test"  => $value,
        );

        return new Response(json_encode($arr), 200);
    }
}