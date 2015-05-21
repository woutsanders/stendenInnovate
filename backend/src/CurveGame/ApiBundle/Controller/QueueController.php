<?php

namespace CurveGame\ApiBundle\Controller;

use CurveGame\ApiBundle\Exception\ApiException;
use Symfony\Component\HttpFoundation\Request;

class QueueController extends BaseController {

    /**
     * Returns true if user is on turn, this way AJAX can respond to this event.
     *
     * @param null $userId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws ApiException
     */
    public function pollAction($userId = null) {

        // Checks if user is on turn (true / false) and set status to waiting for ready if true.
        $em = $this->getDoctrine()->getManager();
        $playerRepo = $em->getRepository('CurveGameEntityBundle:Player');
        $statusRepo = $em->getRepository('CurveGameEntityBundle:Player');

        $pos = $playerRepo->findPositionInQueue($userId);

        if ($pos === false) {

            throw new ApiException(ApiException::HTTP_BAD_REQUEST, "Invalid userID specified");
        }

        if ((int) $pos === 1) {

            $player = $playerRepo->findOneBy(array(
                'id'    => $userId,
            ));

            $player->setStatus($statusRepo->findOneByStatusName('waiting for ready'));
            $player->setTimestamp(time());
            $em->flush();

            $respArr = array(
                'onTurn'    => true,
            );

            return $this->jsonResponse($respArr);
        } else {

            $respArr = array(
                'onTurn'    => false,
            );

            return $this->jsonResponse($respArr);
        }
    }

    public function confirmReadyAction(Request $request, $userId) {

        // Change status to ready if user has confirmed and status is waiting for ready.

        $em = $this->getDoctrine()->getManager();
        $playerRepo = $em->getRepository('CurveGameEntityBundle:Player');

        $player = $playerRepo->findOneBy(array(
            'id' => $userId,
        ));

        if (!$player) {

            throw new ApiException(ApiException::HTTP_BAD_REQUEST, "UserID non-existent or user not waiting anymore");
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