<?php


namespace CurveGame\CmsBundle\Controller;

use CurveGame\EntityBundle\Entity\Player;
use CurveGame\EntityBundle\Form\PlayerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PlayerController extends Controller {

    /**
     * Gives an overview of all the players.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction() {

        $em = $this->getDoctrine()->getManager();
        $playerRepo = $em->getRepository('CurveGameEntityBundle:Player');
        $players = $playerRepo->findAll();

        return $this->render('CurveGameCmsBundle:player:index.html.twig', array(
            'players'   => $players,
        ));
    }

    /**
     * Shows the edit form with populated data.
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id) {

        $em = $this->getDoctrine()->getManager();
        $playerRepo = $em->getRepository('CurveGameEntityBundle:Player');
        $player = $playerRepo->findOneById($id);

        if (!$player)
            throw new NotFoundHttpException('The user could not be found');

        $editForm = $this->createEditForm($player);

        return $this->render('player/edit.html.twig', array(
            'form'  => $editForm->createView(),
        ));
    }

    /**
     * Updates the player.
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $playerRepo = $em->getRepository('CurveGameEntityBundle:Player');
        $player = $playerRepo->findOneById($id);

        if (!$player)
            throw new NotFoundHttpException('Player does not exist');

        $editForm = $this->createEditForm($player);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            return $this->redirectToRoute('curve_game_cms_index');
        }

        // Show error message
        $this->addFlash('error', 'The form was not filled correctly.');
        return $this->redirectToRoute('curve_game_cms_index');
    }

    /**
     * Helper to create the form.
     *
     * @param Player $player
     * @return \Symfony\Component\Form\Form
     */
    private function createEditForm(Player $player) {

        $form = $this->createForm(new PlayerType(), $player, array(
            'action' => $this->generateUrl('curve_game_cms_update', array('id' => $player->getId())),
            'method' => 'PUT',
            'attr' => array(
                'class' => 'form-horizontal',
            ),
        ));

        return $form;
    }

    public function deleteConfirmAction() {

        // To be written???
    }

    /**
     * Deletes a player object from the DB.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction() {

        $player = new Player();
        $form = $this->createForm(new PlayerType(), $player);
        $request = $this->get('request');

        $form->handleRequest($request);
        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $player = $form->getData();

            $em->remove($player);
            $em->flush();
        }

        return $this->render('player/delete.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}