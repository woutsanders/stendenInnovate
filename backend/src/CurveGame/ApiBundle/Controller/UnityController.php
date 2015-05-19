<?php

namespace CurveGame\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class UnityController
 * @package CurveGame\ApiBundle\Controller
 */
class UnityController extends BaseController {


    public function userDataAction() {

        
    }

    public function commandAction(Request $request) {

        $obj = $this->extractJson($request->getContent());
        $output = shell_exec('/usr/local/bin/python /path/to/script.py ' . $obj->userId . ' ' . $obj->moveTo);
    }
}