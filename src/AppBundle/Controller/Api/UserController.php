<?php

namespace AppBundle\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function usersAction()
    {

        $user = new \stdClass();
        $user->username = "coucou";
        $obj = new \stdClass();
        $obj->data = [$user, clone $user];
        return new JsonResponse($obj);
    }
}
