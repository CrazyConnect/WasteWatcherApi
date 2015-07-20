<?php

namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    protected $deviceIds;

    /**
     * @return mixed
     */
    public function getDeviceIds()
    {
        return $this->deviceIds;
    }

    public function addDevice($id)
    {
        $this->deviceIds[] =  $id;
    }

    public function removeDevice($id)
    {
        $key = array_search($id,$this->deviceIds);
        if($key!==false){
            unset($this->deviceIds[$key]);
        }
    }


    /**
     * @ORM\OneToMany(targetEntity="ItemList", mappedBy="user")
     */
    protected $lists;

    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->lists = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getLists()
    {
        return $this->lists;
    }

    /**
     * @param mixed $lists
     */
    public function setLists($lists)
    {
        $this->lists = $lists;
    }

}
