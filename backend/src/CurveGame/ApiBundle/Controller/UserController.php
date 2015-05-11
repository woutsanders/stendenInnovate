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

    public function registerAction(Request $request) {

        // Some code here
    }

    public function loginAction(Request $request) {

        // Some code here
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