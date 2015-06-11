<?php

namespace CMSTest\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CMSTest\CMSBundle\Entity\Delete;
use CMSTest\CMSBundle\Deletephp;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class DeleteController extends Controller
{
    public function newAction()
    {   //Form generaten
        $task = new Delete();
        $form = $this->createForm(new Deletephp(), $task);
        $request = $this->get('request');

        $form->handleRequest($request);
        if ($form->get('save')->isClicked())
        {
            $data = $form->getData();
            return $this->deleteAction($data->getUserName());
        }

        return $this->render('default/delete.html.twig', array('form' => $form->createView(),));
    }

    public function deleteAction($UserName)
    {   //1 op 1 vergelijking met db om te kijken of username bestaat
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('CMSTest\CMSBundle\Entity\Delete')->findOneBy(
            array(
                "UserName" => $UserName
            ));
        if (!$task)
        {   //Bij geen username throw deze exception
            throw $this->createNotFoundException(
                'No username found with username '.$UserName
            );
        }
        //Bij een bestaande username persist de delete
        $em->remove($task);
        $em->flush();
        return $this->redirectToRoute('task_success');
    }
}
