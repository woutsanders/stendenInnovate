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
        $player3 = $players[2];
        $player4 = $players[3];

        $respArr = array(
            "player1"   => array(
                "username"  => $player1->getUsername(),
                "id"        => $player1->getId(),
            ),
            "player2"   => array(
                "username"  => $player2->getUsername(),
                "id"        => $player2->getId(),
            ),
            "player3"   => array(
                "username"  => $player3->getUsername(),
                "id"        => $player3->getId(),
            ),
            "player4"   => array(
                "username"  => $player4->getUsername(),
                "id"        => $player4->getId(),
            ),
        );

        return $this->jsonResponse($respArr);
    }

    /**
     * Deprecated!!! Use WebSockets instead.
     *
     * Executes the movement command.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \CurveGame\ApiBundle\Exception\ApiException
     */
    public function commandAction(Request $request) {

        $obj = $this->extractJson($request->getContent());

        // Get path from vol. root (/) and escape spaces in path...
        $kernelPath = str_replace(' ', '\ ', $this->get('kernel')->getRootDir());

        $unityFile = $kernelPath . '/../bin/unity.py';
        $wrapperFile = $kernelPath . '/../bin/wrap.sh';

        if (!file_exists($unityFile)) {

            throw new ApiException(ApiException::HTTP_INTERNAL_SERVER_ERROR, "The server screwed up");
        }

        $tStr = escapeshellcmd($wrapperFile) . " " .
                escapeshellarg($unityFile) . " " .
                escapeshellarg($obj->userId) . " " .
                escapeshellarg($obj->moveTo) . " 2>&1";
        $output = shell_exec($tStr);

        if (trim($output) == "1") {

            return $this->jsonResponse('{"message":"success","moveTo":' . $obj->moveTo . '}');
        } else {

            throw new ApiException(ApiException::HTTP_INTERNAL_SERVER_ERROR, "Server screwed up.");
        }
    }
}