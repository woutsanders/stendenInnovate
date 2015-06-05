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

        // Fetch entity manager and repositories.
        $em = $this->getEm();
        $playerRepo = $em->getRepository('CurveGameEntityBundle:Player');
        $statusRepo = $em->getRepository('CurveGameEntityBundle:Status');

        // Find player position (if given hash is valid).
        $pos = $playerRepo->findPositionInQueue($obj->hash);

        // If there is no such user, throw the request away.
        if ($pos === false) {

            throw new ApiException(ApiException::HTTP_BAD_REQUEST, "Invalid hash specified");
        }

        // If player is next to play in the queue, do some additional checks.
        if ((int) $pos === 1) {

            // Count number of players by the appropriate statuses.
            $waitingForReady = count($statusRepo->findOneByName('waiting for ready')->getPlayers());
            $ready = count($statusRepo->findOneByName('ready')->getPlayers());
            $playing = count($statusRepo->findOneByName('playing')->getPlayers());

            // Do some voodoo to check whether user is NOT allowed to play.
            if ($waitingForReady === 4
                || $ready === 4
                || ($waitingForReady > 0 && $ready > 0)
                || $playing > 0)
            {
                return $this->jsonResponse('{"onTurn": false}');
            }

            // Get the requested user.
            $player = $playerRepo->findOneByHash($obj->hash);

            // Change it's status and update the timestamp (for the 10s timeout).
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

        // Fetch entity manager and repositories
        $em = $this->getEm();
        $playerRepo = $em->getRepository('CurveGameEntityBundle:Player');
        $statusRepo = $em->getRepository('CurveGameEntityBundle:Status');

        // Find requested player object
        $player = $playerRepo->findOneByHash($obj->hash);

        // No such player, kick out!
        if (!$player) {

            throw new ApiException(ApiException::HTTP_BAD_REQUEST, "User non-existent or user not waiting anymore");
        }

        // User pressed the ready button too late, kick 'em out!
        if ((time() - $player->getTimestamp()) > 15) {

            $em->remove($player);
            $em->flush();

            throw new ApiException(ApiException::HTTP_NOT_ACCEPTABLE, "You pressed the button too late, bummer!");
        } else {

            // If user is not already in stage 2, exit to prevent malicious users from joining in.
            if ($player->getStatus()->getName() !== "waiting for ready") {

                throw new ApiException(ApiException::HTTP_BAD_REQUEST, "User has wrong status and cannot be processed this way");
            }

            // Generate a color for the controls to show on user device.
            $color = $this->generateColor();

            // Set the new status for the player and flush to DB.
            $status = $statusRepo->findOneByName('ready');
            $player
                ->setStatus($status)
                ->setColor($color);
            $em->flush();

            // Let client know which color it has gotten.
            $resp = array(
                "message"   => "success",
                "color"     => $color,
            );

            return $this->jsonResponse($resp);
        }
    }

    /**
     * Helper method for the 'confirmReady' action.
     * Method checks for colors that are already taken and
     * returns a color that is not yet in use.
     *
     * @return string
     */
    private function generateColor() {

        // Fetch entity manager and repositories.
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
     * Checks which position the user is in, in the queue.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws ApiException
     */
    public function positionAction(Request $request) {

        // Process raw JSON
        $obj = $this->extractJson($request->getContent());

        $em = $this->getEm();
        $playerRepo = $em->getRepository('CurveGameEntityBundle:Player');

        // Fetch the position of the player, but only if the user exists.
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