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


class NotificationController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  filters={
     *      {"name"="alertThreshold", "dataType"="integer"},
     *  }
     * )
     */
    public function cgetAction(Request $request)
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
