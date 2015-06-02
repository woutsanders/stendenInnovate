<?php

namespace CurveGame\ApiBundle\Controller;

use CurveGame\ApiBundle\Exception\ApiException;
use CurveGame\EntityBundle\Entity\Player;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class UserController
 * @package CurveGame\ApiBundle\Controller
 */
class UserController extends BaseController {

    /**
     * Registers the username and returns the username, id and status flag.
     *
     * @param Request $request
     * @return Response
     * @throws ApiException
     */
    public function registerAction(Request $request) {

        // Process raw JSON
        $obj = $this->extractJson($request->getContent());

        $em = $this->getDoctrine()->getManager();

        $playerRepo = $em->getRepository('CurveGameEntityBundle:Player');
        $statusRepo = $em->getRepository('CurveGameEntityBundle:Status');
        $status = $statusRepo->findOneByName('waiting');

        if (!$playerRepo->findOneByUsername($obj->username)) {

            $player = new Player();
            $player
                ->setUsername($obj->username)
                ->setStatus($status)
                ->setTimestamp(time());

            $em->persist($player);
            $em->flush();

            $player = $playerRepo->findOneByUsername($obj->username);
            $resp = array(
                "username"  => $player->getUsername(),
                "userId"    => $player->getId(),
                "color"     => false,
                "status"    => $status->getName(),
            );

            return $this->jsonResponse($resp);

        } else {

            throw new ApiException(ApiException::HTTP_CONFLICT, "This user already exists");
        }
    }
}