<?php

namespace AppBundle\Controller\Api;

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Routing\ClassResourceInterface;


class UserController extends FOSRestController implements ClassResourceInterface
{

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Return a collection of Users",
     * )
     */
    public function cgetAction()
    {
        $users = null;
        $users = $this->getDoctrine()->getRepository("AppBundle:User")->findAll();


        $view = $this->view($users, 200);

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Register an User",
     *  parameters={
     *      {"name"="username", "dataType"="string", "required"=true, "description"="username"},
     *      {"name"="email", "dataType"="string", "required"=true, "description"="email"},
     *      {"name"="password", "dataType"="string", "required"=true, "description"="plain password"}
     *  }
     * )
     */
    public function postAction(Request $request)
    {
        $data = $request->getContent();
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->createUser();
        $user->setEnabled(true);
        $user->setUsername(json_decode($data)->username);
        $user->setEmail(json_decode($data)->email);
        $user->setPlainPassword(json_decode($data)->password);
        $userManager->updateUser($user);
        $view = $this->view($user, 200);

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  description="Return a collection of Item list",
     * )
     */
    public function getListsAction(Request $request, $username)
    {
        $products = null;

        $user = $this->getDoctrine()->getRepository("AppBundle:User")->findOneByUsername($username);
        $criteria = ['user' => $user];

        $lists = $this->getDoctrine()->getRepository("AppBundle:ItemList")->findByUser($criteria);

        $view = $this->view($lists, 200);
        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  description="Create new List",
     *  input="AppBundle\Entity\ItemList",
     * )
     */
    public function postListAction(Request $request, $username)
    {
        $data = $request->getContent();
        $list = $this->get("serializer")->deserialize($data, 'AppBundle\Entity\ItemList', 'json');
        $user = $this->getDoctrine()->getRepository("AppBundle:User")->findOneByUsername($username);
        $list->setUser($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($list);
        $em->flush();

        $view = $this->view(null, 201);
        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  description="Delete a List",
     *  statusCodes={
     *         204="Deleted successfully"
     *  }
     *
     * )
     */
    public function deleteListAction($username, $name)
    {
        $user = $this->getDoctrine()->getRepository("AppBundle:User")->findOneByUsername($username);
        $list = $this->getDoctrine()->getRepository("AppBundle:ItemList")->findOneBy(['user' => $user, 'name' => $name]);

        $em = $this->getDoctrine()->getManager();
        $em->remove($list);
        $em->flush();

        $view = $this->view(null, 204);
        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  description="Create a device",
     * )
     */
    public function postDeviceAction(Request $request, $username)
    {

        $data = $request->getContent();
        $user = $this->getDoctrine()->getRepository("AppBundle:User")->findOneByUsername($username);
        $user->addDevice(json_decode($data)->productId);

        $em =  $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush($user);

        $view = $this->view(null, 201);
        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  description="Unregister a device",
     * )
     */
    public function deleteDeviceAction($username, $id)
    {
        $user = $this->getDoctrine()->getRepository("AppBundle:User")->findOneByUsername($username);
        $user->removeDevice($id);

        $em =  $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush($user);

        $view = $this->view(null, 204);
        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  filters={
     *      {"name"="alertThreshold", "dataType"="integer"},
     *  }
     * )
     */
    public function getNotificationsAction(Request $request, $username)
    {

        $alertThreshold = $request->get('alertThreshold') ? $request->get('alertThreshold') : 1;

        $em=$this->getDoctrine()->getManager();

        $today = new \DateTime();
        $limitDate = clone $today;
        $limitDate->add(new \DateInterval('P'.$alertThreshold.'D'));


        $query = $em->createQuery('SELECT i FROM AppBundle\Entity\Item i JOIN i.list  l WHERE l.user = :user AND i.expirationDate >= :today AND i.expirationDate < :limitDate ')
            ->setParameter('user',$this->getUser())
            ->setParameter('today',$today)
            ->setParameter('limitDate',$limitDate)
        ;
        $items = $query->getResult();

        $view = $this->view($items, 200);
        return $this->handleView($view);
    }
}
