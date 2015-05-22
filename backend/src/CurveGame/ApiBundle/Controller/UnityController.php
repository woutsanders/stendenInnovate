<?php

namespace CurveGame\ApiBundle\Controller;

use CurveGame\ApiBundle\Exception\ApiException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UnityController
 * @package CurveGame\ApiBundle\Controller
 */
class UnityController extends BaseController {

    /**
     * Returns relevant user data for Unity to process before the game starts.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws ApiException
     */
    public function userDataAction() {

        $em = $this->getDoctrine()->getManager();
        $playerRepo = $em->getRepository('CurveGameEntityBundle:Player');

        $players = $playerRepo->findByStatus('ready', 'DESC');

        if (!$players) {

            throw new ApiException(ApiException::HTTP_NO_CONTENT, "There are no, or not enough, players available.");
        }

        $player1 = $players[0];
        $player2 = $players[1];

        $respArr = array(
            "player1" => array(
                "username"  => $player1->getUsername(),
                "id"        => $player1->getId(),
            ),
            "player2" => array(
                "username"  => $player2->getUsername(),
                "id"        => $player2->getId(),
            ),
        );

        return $this->jsonResponse($respArr);
    }

    /**
     * Executes the movement command.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \CurveGame\ApiBundle\Exception\ApiException
     */
    public function commandAction(Request $request) {

        $obj = $this->extractJson($request->getContent());
        $unityFile = $this->get('kernel')->getRootDir() . '/../bin/unity.py';
        $pythonPath = shell_exec('which python');

        $output = shell_exec($pythonPath .
                             $unityFile . ' ' .
                             escapeshellarg($obj->userId) . ' ' .
                             escapeshellarg($obj->moveTo)
        );

        return $this->jsonResponse($output);
    }
}