<?php

namespace CurveGame\ApiBundle\Controller;

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

        return new Response($request->request->get('username', null), 200);
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