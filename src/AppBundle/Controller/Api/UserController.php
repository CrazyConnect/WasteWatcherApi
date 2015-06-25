<?php

namespace AppBundle\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api")
 */
class UserController extends Controller
{

    /**
     * @Route("/users")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $debugQueryString = $request->getQueryString();

        $user = new \stdClass();
        $user->username = "coucou";
        $obj = new \stdClass();
        $obj->data = json_decode($this->get("serializer")->serialize($this->getDoctrine()->getRepository("AppBundle:User")->findAll(), 'json'));
        $obj->debugQueryString = $debugQueryString;
        return new JsonResponse($obj);
    }

    /**
     * @Route("/users/{username}")
     * @Method("GET")
     */
    public function showAction($username)
    {

        $user = new \stdClass();
        $user->username = $username;
        $user->lon = "1.333";
        $user->lat = "42.4242";
        $obj = new \stdClass();
        $obj->data = $user;
        return new JsonResponse($obj);
    }
}
