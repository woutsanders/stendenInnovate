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

        $em = $this->getEm();
        $playerRepo = $em->getRepository('CurveGameEntityBundle:Player');

        $players = $playerRepo->findByStatus('ready', 'DESC');

        // Check if there are enough players available.
        if (!$players || count($players) < 4) {

            throw new ApiException(ApiException::HTTP_NO_CONTENT, "There are no, or not enough, players available.");
        }

        // Create response array.
        $resp = array();

        // Put all players in array to send it to Unity...
        for ($i = 1; $i <= count($players); $i++) {

            $player = array(
                "username"  => $players[$i]->getUsername(),
                "hash"      => $players[$i]->getHash(),
                "id"        => $players[$i]->getId(),
                "color"     => $players[$i]->getColor(),
            );

            $resp["player" . $i] = $player;
        }

        return $this->jsonResponse($resp);
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

        // Get the requested JSON obj.
        $obj = $this->extractJson($request->getContent());

        // Get path from vol. root (/) and escape spaces in path...
        $kernelPath = str_replace(' ', '\ ', $this->get('kernel')->getRootDir());

        // Specify file paths.
        $unityFile = $kernelPath . '/../bin/unity.py';
        $wrapperFile = $kernelPath . '/../bin/wrap.sh';

        // If the file has been mysteriously disappeared, return error.
        if (!file_exists($unityFile)) {

            throw new ApiException(ApiException::HTTP_INTERNAL_SERVER_ERROR, "The server screwed up");
        }

        // Build the command and execute it.
        $tStr = escapeshellcmd($wrapperFile) . " " .
                escapeshellarg($unityFile) . " " .
                escapeshellarg($obj->userId) . " " .
                escapeshellarg($obj->moveTo) . " 2>&1";
        $output = shell_exec($tStr);

        // Read output from command.
        if (trim($output) == "1") {

            return $this->jsonResponse('{"message":"success","moveTo":' . $obj->moveTo . '}');
        } else {

            throw new ApiException(ApiException::HTTP_INTERNAL_SERVER_ERROR, "Server screwed up.");
        }
    }
}