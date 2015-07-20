<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Item
 *
 * @ORM\Table()
 * @ORM\Entity()
 * @Serializer\ExclusionPolicy("all")
 */
class Item {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Type("integer")
     * @Serializer\Expose
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="ItemList", inversedBy="items")
     * @ORM\JoinColumn(name="list_id",referencedColumnName="id")
     */
    protected $list;

    /**
     * @ORM\ManyToOne(targetEntity="Product")
     */
    protected $product;
    /**
     * @ORM\Column(type="integer")
     * @Serializer\Type("integer")
     * @Serializer\Expose
     */
    protected $quantity;
    /**
     * @ORM\Column(type="date")
     * @Serializer\Type("DateTime")
     * @Serializer\Expose
     */
    protected $expirationDate;
    /**
     * @ORM\Column(type="date")
     * @Serializer\Type("DateTime")
     * @Serializer\Expose
     */
    protected $creationDate;
    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    protected $picture;
    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Type("string")
     * @Serializer\Expose
     */
    protected $name;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param mixed $picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /**
     * @return mixed
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @param mixed $creationDate
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    /**
     * @return mixed
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * @param mixed $expirationDate
     */
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return mixed
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @param mixed $list
     */
    public function setList($list)
    {
        $this->list = $list;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}

