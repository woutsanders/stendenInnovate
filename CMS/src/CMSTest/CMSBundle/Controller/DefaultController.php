<?php

namespace CMSTest\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CMSTest\CMSBundle\Entity\Task;
use CMSTest\CMSBundle\CMS;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function newAction()
    {
        $task = new Task();
        $form = $this->createForm(new CMS(), $task);
        $request = $this->get('request');

        $form->handleRequest($request);
        if ($form->get('save')->isClicked())
        {
            $data = $form->getData();
            return $this->updateAction($data->getUserName(), $data->getStatus());
        }
        return $this->render('default/new.html.twig', array('form' => $form->createView(),));
    }

    public function updateAction($UserName, $Status)
    {
    $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('CMSTest\CMSBundle\Entity\Task')->findOneBy(
            array(
                "UserName" => $UserName
        ));
        if (!$task)
        {
            throw $this->createNotFoundException(
                'No username found with username '.$UserName
            );
        }

        $task->setStatus($Status);
        $em->flush();
        return $this->redirectToRoute('task_success');
    }
}
