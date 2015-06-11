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

        // Fetch entity manager and repositories
        $em = $this->getEm();
        $playerRepo = $em->getRepository('CurveGameEntityBundle:Player');
        $statusRepo = $em->getRepository('CurveGameEntityBundle:Status');

        // Load some database objects
        $status = $statusRepo->findOneByName('waiting');
        $player = $playerRepo->findOneByUsername($obj->username);

        if (!$player) {

            // Create a new player if it doesn't exist
            return $this->jsonResponse($this->createUser($status, $obj));

        } else {

            // Check for a player that wants to play again (respawn).
            if (isset($obj->repeat)
                && isset($obj->hash)
                && $obj->hash === $player->getHash()
                && $obj->repeat === "y")
            {
                return $this->jsonResponse($this->resetUser($player, $status));
            }

            // If it's not a player that wants to be respawn and username exists, throw exception.
            throw new ApiException(ApiException::HTTP_CONFLICT, "This user already exists");
        }
    }

    /**
     * Creates the new user.
     *
     * @param Status $status
     * @param $obj
     * @return array
     */
    private function createUser(Status $status, $obj) {

        // Fetch entity manager and repository
        $em = $this->getEm();
        $playerRepo = $em->getRepository('CurveGameEntityBundle:Player');

        // Set up a new player entity object.
        $player = new Player();
        $player
            ->setUsername($obj->username)
            ->setStatus($status)
            ->setTimestamp(time());

        // Persist and flush changes to DB.
        $em->persist($player);
        $em->flush();

        // Retrieve the just inserted player (for the hash and AI ID).
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

        // Fetch entity manager.
        $em = $this->getEm();

        // Adjust the player object.
        $player
            ->setScore(0)
            ->setStatus($status)
            ->setTimestamp(time());

        // Flush changes to DB.
        $em->flush();

        return array(
            "username"  => $player->getUsername(),
            "hash"      => $player->getHash(),
            "color"     => false,
            "status"    => $status->getName(),
        );
    }

    /**
     * Removes a players' game profile.
     *
     * @param Request $request
     * @return Response
     * @throws ApiException
     */
    public function deleteAction(Request $request) {

        $obj = $this->extractJson($request->getContent());

        $em = $this->getEm();
        $playerRepo = $em->getRepository('CurveGameEntityBundle:Player');
        $player = $playerRepo->findOneByHash($obj->hash);

        $em->remove($player);
        $em->flush();

        return $this->jsonResponse('{message:"success."}');
    }
}