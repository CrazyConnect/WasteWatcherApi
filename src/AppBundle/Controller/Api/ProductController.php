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

class ProductController extends FOSRestController implements ClassResourceInterface
{

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Returns a collection of Product",
     *  filters={
     *      {"name"="barcode", "dataType"="string"},
     *  }
     * )
     */
    public function cgetAction(Request $request)
    {
        $products = null;
        if ($request->get('barcode')) {
            $products = $this->getDoctrine()->getRepository("AppBundle:Product")->findBy(['barcode' => $request->get('barcode')]);
        } else {
            $products = $this->getDoctrine()->getRepository("AppBundle:Product")->findAll();
        }
        $view = $this->view($products, 200);

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Creates new Product",
     *  input="AppBundle\Entity\Product",
     * )
     */
    public function postAction(Request $request)
    {
        $data = $request->getContent();
        $product = $this->get("serializer")->deserialize($data, 'AppBundle\Entity\Product', 'json');
        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        $view = $this->view(null, 201);
        return $this->handleView($view);
    }
}
