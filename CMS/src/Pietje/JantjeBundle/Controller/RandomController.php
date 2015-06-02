<?php
namespace Pietje\JantjeBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RandomController extends Controller
{
    public function indexAction($limit)
    {
        $number = rand(1, $limit);

        return $this->render('PietjeJantjeBundle:Default:index.html.twig', array('number' => $number));
    }
}

?>