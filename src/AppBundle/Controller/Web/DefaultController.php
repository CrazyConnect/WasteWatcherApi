<?php

namespace AppBundle\Controller\Web;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/web", name="homepage")
     */
    public function indexAction($message = 'hello')
    {
        return $this->render('default/index.html.twig', ['helloMessage' => $message]);
    }

    /**
     * @Route("/webapp/{message}", name="webapp")
     */
    public function webAppAction($message = 'hello')
    {
        return $this->render('::webapp.html.php', ['helloMessage' => $message]);
    }
}
