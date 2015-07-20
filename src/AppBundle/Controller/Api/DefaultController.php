<?php

namespace AppBundle\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class DefaultController extends Controller
{
    /**
     * @Route("/api", name="api_resource")
     * @Method("GET")
     */
    public function indexAction()
    {
        $obj = new \stdClass();
        return new JsonResponse($obj);
    }

    /**
     * @deprecated
     * @ApiDoc(
     *  description="Register a device",
     * )
     * @Route("/api/registerDevice/{id}")
     */
    public function registerDeviceAction($id)
    {
        $user = $this->getUser();
        $user->addDevice($id);

        $em =  $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush($user);

        return new JsonResponse([],200);
    }

    /**
     * @deprecated
     * @ApiDoc(
     *  description="Unregister a device",
     * )
     * @Route("/api/unregisterDevice/{id}")
     */
    public function unregisterDeviceAction($id)
    {
        $user = $this->getUser();
        $user->removeDevice($id);

        $em =  $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush($user);

        return new JsonResponse([],200);
    }

    /**
     * @deprecated
     * @ApiDoc(
     *  resource=true,
     *  description="Returns a collection of List",
     * )
     * @Route("/api/list")
     * @Method("GET")
     */
    public function indexListAction()
    {
        $list = $this->getDoctrine()->getRepository("AppBundle:ItemList")->findBy(['user' => $this->getUser()]);

        if(is_null($list))
            return new JsonResponse([],200);

        $serializer = $this->get("serializer");
        $jsonContent = $serializer->serialize($list, 'json');

        return new JsonResponse(json_decode($jsonContent));
    }

    /**
     * @deprecated
     * @ApiDoc(
     *  resource=true,
     *  description="Creates a List",
     * )
     * @Route("/api/list")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $data = $request->getContent();
        $list = $this->get("serializer")->deserialize($data, 'AppBundle\Entity\ItemList', 'json');
        $list->setUser($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($list);
        $em->flush();

        return new JsonResponse([], 201);
    }

    /**
     * @deprecated
     * @ApiDoc(
     *  resource=true,
     *  description="Deletes a List",
     * )
     * @Route("/api/list/{name}")
     * @Method("DELETE")
     */
    public function deleteAction($name)
    {
        $list = $this->getDoctrine()->getRepository("AppBundle:ItemList")->findOneBy(['user' => $this->getUser(), 'name' => $name]);

        $em = $this->getDoctrine()->getManager();
        $em->remove($list);
        $em->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @deprecated
     * @ApiDoc(
     *  resource=true,
     *  description="Returns a collection of Item",
     * )
     * @Route("/api/list/{name}/items")
     * @Method("GET")
     */
    public function indexItemsAction($name)
    {
        $list = $this->getDoctrine()->getRepository("AppBundle:ItemList")->findOneBy(['user' => $this->getUser(), 'name' => $name]);

        if(is_null($list))
            return new JsonResponse(null,404);

        $serializer = $this->get("serializer");

        $items = $list->getItems();
        $jsonContent = $serializer->serialize($items, 'json');

        return new JsonResponse(json_decode($jsonContent));
    }

    /**
     * @deprecated
     * @ApiDoc(
     *  resource=true,
     *  description="Returns an Item",
     * )
     * @Route("/api/list/{name}/items/{id}")
     * @Method("GET")
     */
    public function showItemsByIdAction($name, $id)
    {
        $item = $this->getDoctrine()->getRepository("AppBundle:Item")->find($id);
        if(is_null($item))
            return new JsonResponse(null,404);

        $serializer = $this->get("serializer");

        $jsonContent = $serializer->serialize($item, 'json');

        return new JsonResponse(json_decode($jsonContent));
    }

    /**
     * @deprecated
     * @ApiDoc(
     *  resource=true,
     *  description="Updates an Item",
     * )
     * @Route("/api/list/{name}/items/{id}")
     * @Method("PUT")
     */
    public function updateItemAction(Request $request, $name, $id)
    {
        $itemBase = $this->getDoctrine()->getRepository("AppBundle:Item")->find($id);

        $data = $request->getContent();
        $item = $this->get("serializer")->deserialize($data, 'AppBundle\Entity\Item', 'json');

        $list = $this->getDoctrine()->getRepository("AppBundle:ItemList")->findOneBy(['user' => $this->getUser(), 'name' => $name]);
        $item->setList($list);
        //@todo so ugly
        $item->setProduct($this->getDoctrine()->getRepository("AppBundle:Product")->findOneBy(['id' => json_decode($data)->productId]));


        $itemBase->setName($item->getName());
        $itemBase->setPicture($item->getPicture());
        $itemBase->setExpirationDate($item->getExpirationDate());
        $itemBase->setQuantity($item->getQuantity());
        $itemBase->setProduct($item->getProduct());
        $itemBase->setList($item->getList());
        $em = $this->getDoctrine()->getManager();
        $em->persist($itemBase);
        $em->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @deprecated
     * @ApiDoc(
     *  resource=true,
     *  description="Creates an Item",
     * )
     * @Route("/api/list/{name}/items")
     * @Method("POST")
     */
    public function createItemsAction(Request $request, $name)
    {

        $data = $request->getContent();

        $item = $this->get("serializer")->deserialize($data, 'AppBundle\Entity\Item', 'json');

        $list = $this->getDoctrine()->getRepository("AppBundle:ItemList")->findOneBy(['user' => $this->getUser(), 'name' => $name]);
        $item->setList($list);
        //@todo so ugly
        $item->setProduct($this->getDoctrine()->getRepository("AppBundle:Product")->findOneBy(['id' => json_decode($data)->productId]));

        $em = $this->getDoctrine()->getManager();
        $em->persist($item);
        $em->flush();
        return new JsonResponse([], 201);
    }

    /**
     * @deprecated
     * @ApiDoc(
     *  resource=true,
     *  description="Deletes an Item",
     * )
     * @Route("/api/list/{name}/items/{id}")
     * @Method("DELETE")
     */
    public function deleteItemAction($id)
    {
        $item = $this->getDoctrine()->getRepository("AppBundle:Item")->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($item);
        $em->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @deprecated
     * @ApiDoc(
     * )
     * @Route("/notification/expiring/{alertThreshold}", defaults={"alertThreshold"=1})
     */
    public function getExpirationNotificationsAction($alertThreshold)
    {

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

        $serializer = $this->get("serializer");
        $jsonContent = $serializer->serialize($items, 'json');

        return new JsonResponse(json_decode($jsonContent));
    }
}
