<?php


namespace CurveGame\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller {

    /**
     * Shows an edit form for the status of the user.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editStatusAction() {

        //Form generaten
        $task = new Editstatus();
        $form = $this->createForm(new CMSIndex(), $task);
        $request = $this->get('request');

        $form->handleRequest($request);
        if ($form->get('save')->isClicked())
        {
            $data = $form->getData();
            return $this->updateStatusAction($data->getUserName(), $data->getStatus());
        }
        //Form renderen
        return $this->render('default/editstatus.html.twig', array('form' => $form->createView(),));
    }

    /**
     * Updates the user status in the DB.
     *
     * @param $UserName
     * @param $Status
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateStatusAction($UserName, $Status)
    {   //1 op 1 vergelijking met db om te kijken of username bestaat
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('CurveGameEntityBundle:Player')->findOneBy(
            array(
                "username" => $UserName
            ));
        if (!$task)
        {   //Bij geen username throw deze exception
            throw $this->createNotFoundException(
                'No username found with username '.$UserName
            );
        }
        //Bij een bestaande username persist de nieuw opgegeven status naar db
        if ($Status == "ready")
        {
            $task->setStatus(3);
            $em->flush();
            return $this->redirectToRoute('task_success');
        }
        elseif ($Status == "waiting for ready")
        {
            $task->setStatus(2);
            $em->flush();
            return $this->redirectToRoute('task_success');
        }
        elseif ($Status == "waiting")
        {
            $task->setStatus(1);
            $em->flush();
            return $this->redirectToRoute('task_success');
        }
        elseif ($Status == "playing")
        {
            $task->setStatus(4);
            $em->flush();
            return $this->redirectToRoute('task_success');
        }
        elseif ($Status == "finished")
        {
            $task->setStatus(5);
            $em->flush();
            return $this->redirectToRoute('task_success');
        }
        // Bij een bestaande username persist de nieuw opgegeven status naar db
        // $task->setStatus($Status);
        // $em->flush();
        // return $this->redirectToRoute('task_success');
    }
}