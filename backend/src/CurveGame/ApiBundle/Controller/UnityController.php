<?php

namespace CurveGame\ApiBundle\Controller;

use CurveGame\ApiBundle\Exception\ApiException;
use Symfony\Component\Form\Extension\Core\EventListener\ResizeFormListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

        $kernelPath = $this->get('kernel')->getRootDir();
        $unityFile = $kernelPath . '/../bin/unity.py';
        $wrapperFile = $kernelPath . '/../bin/wrap.sh';

        if (!file_exists($unityFile)) {

            throw new ApiException(ApiException::HTTP_INTERNAL_SERVER_ERROR, "The server screwed up");
        }

        $tStr = $wrapperFile . " " . $unityFile . " " . $obj->userId . " " . $obj->moveTo . " 2>&1";
        $output = shell_exec($tStr);

        if ($output == "1") {

            return $this->jsonResponse('{"message":"success","moveTo":' . $obj->moveTo . '}');
        } else {

            throw new ApiException(ApiException::HTTP_INTERNAL_SERVER_ERROR, "Server screwed up.");
        }
    }
}