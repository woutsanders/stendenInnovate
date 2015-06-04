<?php

namespace CurveGame\ApiBundle\Controller;

use CurveGame\ApiBundle\Exception\ApiException;
use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpFoundation\Request;

class QueueController extends BaseController {

    /**
     * Returns true if user is on turn and sets the corresponding status, this way AJAX can respond to this event.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws ApiException
     */
    public function pollAction(Request $request) {

        // Process raw JSON
        $obj = $this->extractJson($request->getContent());

        $em = $this->getDoctrine()->getManager();
        $playerRepo = $em->getRepository('CurveGameEntityBundle:Player');
        $statusRepo = $em->getRepository('CurveGameEntityBundle:Status');

        $pos = $playerRepo->findPositionInQueue($obj->hash);

        if ($pos === false) {

            throw new ApiException(ApiException::HTTP_BAD_REQUEST, "Invalid hash specified");
        }

        if ((int) $pos === 1) {
            $waitingForReady = count($statusRepo->findOneByName('waiting for ready')->getPlayers());
            $ready = count($statusRepo->findOneByName('ready')->getPlayers());
            $playing = count($statusRepo->findOneByName('playing')->getPlayers());

            if ($waitingForReady === 4
                || $ready === 4
                || ($waitingForReady > 0 && $ready > 0)
                || $playing > 0)
            {
                return $this->jsonResponse('{"onTurn": false}');
            }

            $player = $playerRepo->findOneByHash($obj->hash);

            $player->setStatus($statusRepo->findOneByName('waiting for ready'));
            $player->setTimestamp(time());
            $em->flush();

            return $this->jsonResponse('{"onTurn": true}');
        } else {

            return $this->jsonResponse('{"onTurn": false}');
        }
    }

    /**
     * Sets the player status to ready if player pressed within 10 sec. Else user will be deleted.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws ApiException
     */
    public function confirmReadyAction(Request $request) {

        // Process raw JSON
        $obj = $this->extractJson($request->getContent());

        $em = $this->getDoctrine()->getManager();
        $playerRepo = $em->getRepository('CurveGameEntityBundle:Player');
        $statusRepo = $em->getRepository('CurveGameEntityBundle:Status');

        $player = $playerRepo->findOneByHash($obj->hash);

        if (!$player) {

            throw new ApiException(ApiException::HTTP_BAD_REQUEST, "User non-existent or user not waiting anymore");
        }

        // User pressed the ready button too late, kick 'em out!
        if ((time() - $player->getTimestamp()) > 15) {

            $em->remove($player);
            $em->flush();

            throw new ApiException(ApiException::HTTP_NOT_ACCEPTABLE, "You pressed the button too late, bummer!");
        } else {

            $color = $this->generateColor();

            if ($player->getStatus()->getName() !== "waiting for ready") {

                throw new ApiException(ApiException::HTTP_BAD_REQUEST, "User has wrong status and cannot be processed this way");
            }

            $status = $statusRepo->findOneByName('ready');

            $player
                ->setStatus($status)
                ->setColor($color);

            $em->flush();

            $resp = array(
                "message"   => "success",
                "color"     => $color,
            );

            return $this->jsonResponse($resp);
        }
    }

    private function generateColor() {

        $em = $this->getEm();
        $statusRepo = $em->getRepository('CurveGameEntityBundle:Status');
        $readyPlayers = $statusRepo->findOneByName('ready')->getPlayers();

        $colors = array(
            'red',
            'green',
            'yellow',
            'blue',
            'white',
        );

        // If there are no ready players yet, just return a random color...
        if (count($readyPlayers) < 1) {

            return $colors[mt_rand(0, 4)];
        }

        $usedColors = array();

        // Get all colors that are already in use by ready players.
        foreach ($readyPlayers as $player) {

            $usedColors[] = $player->getColor();
        }

        // Look at the differences...
        $freeColors = array_values(array_diff($colors, $usedColors));

        // Return the first free color available.
        return $freeColors[0];
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws ApiException
     */
    public function positionAction(Request $request) {

        // Process raw JSON
        $obj = $this->extractJson($request->getContent());

        $em = $this->getDoctrine()->getManager();
        $playerRepo = $em->getRepository('CurveGameEntityBundle:Player');

        if ($pos = $playerRepo->findPositionInQueue($obj->hash)) {

            $resp = array(
                'position'  => $pos,
            );

            return $this->jsonResponse($resp);
        } else {

            throw new ApiException(ApiException::HTTP_BAD_REQUEST, "User non-existent or user not in queue");
        }
    }
}