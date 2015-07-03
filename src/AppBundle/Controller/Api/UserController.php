<?php

namespace AppBundle\Controller\Api;

use Doctrine\Common\Collections\ArrayCollection;
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
        $users = null;
        if ($this->isBoxSearchAction($request)) {
            $users = $this->getDoctrine()->getRepository("AppBundle:User")->findBy(['lon' => $request->get('lon1'), 'lat' => $request->get('lat1')]);
        } else {
            $users = $this->getDoctrine()->getRepository("AppBundle:User")->findAll();
        }
        $obj = new \stdClass();
        $obj->data = json_decode($this->get("serializer")->serialize($users, 'json'));
        $obj->debugQueryString = $debugQueryString;
        return new JsonResponse($obj);
    }

    /**
     * @Route("/users/{username}")
     * @Method("GET")
     */
    public function showAction($username)
    {

        $user = $this->getDoctrine()->getRepository("AppBundle:User")->findOneBy(['username' => $username]);
        $obj = new \stdClass();
        $obj->data = json_decode($this->get("serializer")->serialize($user, 'json'));
        return new JsonResponse($obj);
    }

    protected function isBoxSearchAction(Request $request) {
        return $request->get('lat1') && $request->get('lon1') && $request->get('lat2') && $request->get('lon2');
    }
}
