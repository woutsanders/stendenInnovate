<?php

namespace CurveGame\ApiBundle\Controller;

use CurveGame\ApiBundle\Exception\ApiException;
use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpFoundation\Request;

class QueueController extends BaseController {

    /**
     * Returns true if user is on turn and sets the corresponding status, this way AJAX can respond to this event.
     *
     * @param null $userId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws ApiException
     */
    public function pollAction($userId = null) {

        $em = $this->getDoctrine()->getManager();
        $playerRepo = $em->getRepository('CurveGameEntityBundle:Player');
        $statusRepo = $em->getRepository('CurveGameEntityBundle:Status');

        $pos = $playerRepo->findPositionInQueue($userId);

        if ($pos === false) {

            throw new ApiException(ApiException::HTTP_BAD_REQUEST, "Invalid userID specified");
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

            $player = $playerRepo->findOneById($userId);

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
     * @param $userId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws ApiException
     */
    public function confirmReadyAction(Request $request, $userId) {

        // Change status to ready if user has confirmed and status is waiting for ready.

        $em = $this->getDoctrine()->getManager();
        $playerRepo = $em->getRepository('CurveGameEntityBundle:Player');
        $statusRepo = $em->getRepository('CurveGameEntityBundle:Status');

        $player = $playerRepo->findOneBy(array(
            'id'    => $userId,
        ));

        if (!$player) {

            throw new ApiException(ApiException::HTTP_BAD_REQUEST, "UserID non-existent or user not waiting anymore");
        }

        // User pressed the ready button too late, kick 'em out!
        if ((time() - $player->getTimestamp()) > 12) {

            $em->remove($player);
            $em->flush();

            throw new ApiException(ApiException::HTTP_NOT_ACCEPTABLE, "You pressed the button too late, bummer!");
        } else {

            if ($player->getStatus()->getName() !== "waiting for ready") {

                throw new ApiException(ApiException::HTTP_BAD_REQUEST, "User has wrong status and cannot be processed this way");
            }

            $status = $statusRepo->findOneBy(array(
                'name'  => 'ready'
            ));

            $player->setStatus($status);
            $em->flush();

            return $this->jsonResponse('{"message": "success"}');
        }
    }

    /**
     * @param null $userId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws ApiException
     */
    public function positionAction($userId = null) {

        $em = $this->getDoctrine()->getManager();
        $playerRepo = $em->getRepository('CurveGameEntityBundle:Player');

        if ($pos = $playerRepo->findPositionInQueue($userId)) {

            $resp = array(
                'userId'    => $userId,
                'position'  => $pos,
            );

            return $this->jsonResponse($resp);
        } else {

            throw new ApiException(ApiException::HTTP_BAD_REQUEST, "UserID non-existent or user is playing");
        }
    }
}