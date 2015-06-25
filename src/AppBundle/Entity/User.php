<?php

namespace AppBundle\Entity;


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

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\Expose
     */
    protected $username;

    /**
     * @var float
     *
     * @ORM\Column(name="lon", type="float")
     * @Serializer\Type("float")
     * @Serializer\Expose
     */
    private $lon;

    /**
     * @var float
     *
     * @ORM\Column(name="lat", type="float")
     * @Serializer\Type("float")
     * @Serializer\Expose
     */
    private $lat;

    /**
     * @var string
     *
     * @ORM\Column(name="hashtag", type="string", length=255)
     * @Serializer\Type("string")
     * @Serializer\Expose
     */
    private $hashtag;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set lon
     *
     * @param float $lon
     * @return User
     */
    public function setLon($lon)
    {
        $this->lon = $lon;

        return $this;
    }

    /**
     * Get lon
     *
     * @return float 
     */
    public function getLon()
    {
        return $this->lon;
    }

    /**
     * Set lat
     *
     * @param float $lat
     * @return User
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return float 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set hashtag
     *
     * @param string $hashtag
     * @return User
     */
    public function setHashtag($hashtag)
    {
        $this->hashtag = $hashtag;

        return $this;
    }

    /**
     * Get hashtag
     *
     * @return string 
     */
    public function getHashtag()
    {
        return $this->hashtag;
    }
}
