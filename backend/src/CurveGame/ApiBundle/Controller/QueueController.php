<?php

namespace CurveGame\ApiBundle\Controller;

use CurveGame\ApiBundle\Exception\ApiException;
use Symfony\Component\HttpFoundation\Request;

class QueueController extends BaseController {

    public function pollAction($userId = null) {

        //
    }

    public function confirmReadyAction(Request $request, $userId) {

        //
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