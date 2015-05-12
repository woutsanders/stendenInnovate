<?php

namespace CurveGame\ApiBundle\Controller;

use CurveGame\ApiBundle\Exception\ApiException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class QueueController extends Controller {


    public function pollAction($username = null) {

        //
    }

    public function confirmReadyAction(Request $request, $userId) {

        //
    }

    public function positionAction($username = null) {

        throw new ApiException(ApiException::HTTP_NOT_IMPLEMENTED);
    }
}