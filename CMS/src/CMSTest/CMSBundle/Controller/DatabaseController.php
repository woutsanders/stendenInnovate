<?php

namespace CMSTest\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CMSTest\CMSBundle\Entity\Task2;
use CMSTest\CMSBundle\Overview;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use \DateTime;

class DatabaseController extends Controller
{
    public function newAction()
    {
        $task = new Task2();
        $form = $this->createForm(new Overview(), $task);
        $request = $this->get('request');

        $form->handleRequest($request);
        if ($form->get('save')->isClicked())
        {
            $data = $form->getData();
            return $this->updateAction($data->getUserName(), $data->getStatus(), $data->getId(), $data->getScore(), $data->getDateTime());
        }
        return $this->render('default/hello.html.twig', array('form' => $form->createView(),));
    }

    public function updateAction($UserName)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('CMSTest\CMSBundle\Entity\Task2')->findOneBy(
            array(
                "UserName" => $UserName
            ));
        if (!$task)
        {
            $query = $em->CreateQuery(
                'SELECT p
                FROM CMSTestCMSBundle:Task2 p
                WHERE p.UserName LIKE :UserName'
            )->setParameter('UserName', '%' . $UserName . '%');
            $users = $query->getResult();
            if(!$users)
            {
                throw $this->createNotFoundException(
                    'No username found with username '.$UserName
                );
            }
            else {
                return $this->render('default/index.html.twig', array('Users' => $users));
            }
        }
        else
        {
           return $this->render('default/index.html.twig', array('Users' => array($task)));
        }
    }
}
