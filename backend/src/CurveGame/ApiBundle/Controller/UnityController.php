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

    /**
     * Executes the movement command.
     *
     * @param Request $request
     * @return Response
     * @throws \CurveGame\ApiBundle\Exception\ApiException
     */
    public function commandAction(Request $request) {

        $obj = $this->extractJson($request->getContent());
        $consolePath = $this->get('kernel')->getRootDir() . '/../console';
        $pythonPath = shell_exec('which python');

        $output = shell_exec($pythonPath .
                             $consolePath . ' ' .
                             escapeshellarg($obj->userId) . ' ' .
                             escapeshellarg($obj->moveTo)
        );

        return $this->jsonResponse($output);
    }
}