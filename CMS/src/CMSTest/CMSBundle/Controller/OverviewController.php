<?php
//Made by Remco Beikes


namespace CMSTest\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CMSTest\CMSBundle\Entity\Overview;
use CMSTest\CMSBundle\Overviewphp;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class OverviewController extends Controller
{
    public function newAction()
    {
        //Form generaten
        $task = new Overview();
        $form = $this->createForm(new Overviewphp(), $task);
        $request = $this->get('request');

        $form->handleRequest($request);
        if ($form->get('save')->isClicked())
        {
            $data = $form->getData();
            return $this->updateAction($data->getUserName(), $data->getStatus(), $data->getId(), $data->getScore(), $data->getDateTime());
        }
        //Renderen van de form
        return $this->render('default/overviewform.html.twig', array('form' => $form->createView(),));
    }

    public function updateAction($UserName)
    {   //Pak de query manager erbij en check voor een 1 op 1 overeenkomst
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('CMSTest\CMSBundle\Entity\Overview')->findOneBy(
            array(
                "UserName" => $UserName
            ));
        if (!$task)
        {   //Bij geen 1 op 1 overeenkomst check of een gedeelte van de zoekterm overeen komt met een db entry d.m.v. custom query
            $query = $em->CreateQuery(
                'SELECT p
                FROM CMSTestCMSBundle:Overview p
                WHERE p.UserName LIKE :UserName'
            )->setParameter('UserName', '%' . $UserName . '%');
            $users = $query->getResult();
            if(!$users)
            {   //Bij geen resultaat throw deze exception
                throw $this->createNotFoundException(
                    'No username found with username '.$UserName
                );
            }
            else {//results komen terug als array, dus als array doorgeven aan view omgeving
                return $this->render('default/overview.html.twig', array('Users' => $users));
            }
        }
        else
        {   //enkele result komt terug als object, eerst array van maken en dan doorgeven aan view omgeving
           return $this->render('default/overview.html.twig', array('Users' => array($task)));
        }
    }
}
