<?php
namespace AppBundle\Controller\Api;

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\ItemList;
use JMS\Serializer\SerializerBuilder;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;

class ListController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Return a collection of Items",
     * )
     */
    public function getItemsAction(Request $request, $name)
    {
        $list = $this->getDoctrine()->getRepository("AppBundle:ItemList")->findOneBy(['user' => $this->getUser(), 'name' => $name]);

        $items = $list->getItems();

        $view = $this->view($items, 200);
        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Return an Item",
     * )
     */
    public function getItemAction(Request $request, $name, $id)
    {
        $item = $this->getDoctrine()->getRepository("AppBundle:Item")->find($id);

        $view = $this->view($item, 200);
        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Update an Item",
     *  input="AppBundle\Entity\Item",
     *  output="AppBundle\Entity\Item",
     * )
     */
    public function putItemAction(Request $request, $name, $id)
    {
        $itemBase = $this->getDoctrine()->getRepository("AppBundle:Item")->find($id);

        $data = $request->getContent();
        $item = $this->get("serializer")->deserialize($data, 'AppBundle\Entity\Item', 'json');

        $user = $this->getUser();
        $list = $this->getDoctrine()->getRepository("AppBundle:ItemList")->findOneBy(['user' => $user, 'name' => $name]);
        $item->setList($list);
        //@todo so ugly
        $item->setProduct($this->getDoctrine()->getRepository("AppBundle:Product")->findOneBy(['id' => json_decode($data)->productId]));


        $itemBase->setName($item->getName());
        $itemBase->setPicture($item->getPicture());
        $itemBase->setExpirationDate($item->getExpirationDate());
        $itemBase->setQuantity($item->getQuantity());
        $itemBase->setProduct($item->getProduct());
        $em = $this->getDoctrine()->getManager();
        $em->persist($itemBase);
        $em->flush();

        $view = $this->view($itemBase, 200);
        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Create an Item",
     *  input="AppBundle\Entity\Item",
     * )
     */
    public function postItemAction(Request $request, $name)
    {

        $data = $request->getContent();

        $item = $this->get("serializer")->deserialize($data, 'AppBundle\Entity\Item', 'json');

        $user = $this->getUser();
        $list = $this->getDoctrine()->getRepository("AppBundle:ItemList")->findOneBy(['user' => $user, 'name' => $name]);
        $item->setList($list);
        //@todo so ugly
        $item->setProduct($this->getDoctrine()->getRepository("AppBundle:Product")->findOneBy(['id' => json_decode($data)->product_id]));

        $em = $this->getDoctrine()->getManager();
        $em->persist($item);
        $em->flush();

        $view = $this->view($item, 201);
        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Delete an Item",
     * )
     */
    public function deleteItemAction($name, $id)
    {
        $item = $this->getDoctrine()->getRepository("AppBundle:Item")->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($item);
        $em->flush();

        $view = $this->view($item, 204);
        return $this->handleView($view);
    }
}