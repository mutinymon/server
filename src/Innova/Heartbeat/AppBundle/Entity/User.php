<?php

namespace Innova\Heartbeat\AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * App
 *
 * @ORM\Table("user")
 * @ORM\Entity
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="prefeeredUID", type="string", length=255)
     */
    private $preferedUID;

    /**
     * @var string
     *
     * @ORM\Column(name="prefeeredGID", type="string", length=255)
     */
    private $preferedGID;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

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
     * Set preferedUID
     *
     * @param string $preferedUID
     * @return Server
     */
    public function setPreferedUID($preferedUID)
    {
        $this->preferedUID = $preferedUID;

        return $this;
    }

    /**
     * Get preferedUID
     *
     * @return string 
     */
    public function getPreferedUID()
    {
        return $this->preferedUID;
    }

    /**
     * Set preferedGID
     *
     * @param string $preferedGID
     * @return Server
     */
    public function setPreferedGID($preferedGID)
    {
        $this->preferedGID = $preferedGID;

        return $this;
    }

    /**
     * Get preferedGID
     *
     * @return string 
     */
    public function getPreferedGID()
    {
        return $this->preferedGID;
    } 
}
