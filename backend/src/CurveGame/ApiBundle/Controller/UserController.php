<?php

namespace CurveGame\ApiBundle\Controller;

use CurveGame\ApiBundle\Exception\ApiException;
use CurveGame\EntityBundle\Entity\Player;
use CurveGame\EntityBundle\Entity\Status;
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

        $em = $this->getEm();
        $playerRepo = $em->getRepository('CurveGameEntityBundle:Player');
        $statusRepo = $em->getRepository('CurveGameEntityBundle:Status');

        $status = $statusRepo->findOneByName('waiting');
        $player = $playerRepo->findOneByUsername($obj->username);

        if (!$player) {

            return $this->jsonResponse($this->createUser($status, $obj));

        } else {

            // Perhaps a 'repeating' player??
            if (isset($obj->repeat)
                && isset($obj->hash)
                && $obj->hash === $player->getHash()
                && $obj->repeat === "y")
            {
                return $this->jsonResponse($this->resetUser($player, $status));
            }

            // Nope, not a repeating player, kick out...
            throw new ApiException(ApiException::HTTP_CONFLICT, "This user already exists");
        }
    }

    /**
     * Creates the user.
     *
     * @param Status $status
     * @param $obj
     * @return array
     */
    private function createUser(Status $status, $obj) {

        $em = $this->getEm();
        $playerRepo = $em->getRepository('CurveGameEntityBundle:Player');

        $player = new Player();
        $player
            ->setUsername($obj->username)
            ->setStatus($status)
            ->setTimestamp(time());

        $em->persist($player);
        $em->flush();

        $player = $playerRepo->findOneByUsername($obj->username);

        return array(
            "username"  => $player->getUsername(),
            "hash"      => $player->getHash(),
            "color"     => false,
            "status"    => $status->getName(),
        );
    }

    /**
     * Resets the user to play a new challenge.
     *
     * @param Player $player
     * @param Status $status
     * @return array
     */
    private function resetUser(Player $player, Status $status) {

        $em = $this->getEm();

        $player
            ->setScore(0)
            ->setStatus($status)
            ->setTimestamp(time());

        $em->flush();

        return array(
            "username"  => $player->getUsername(),
            "hash"      => $player->getHash(),
            "color"     => false,
            "status"    => $status->getName(),
        );
    }

    public function deleteAction(Request $request) {

    }
}