<?php

namespace Innova\Heartbeat\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Server
 *
 * @ORM\Table("server")
 * @ORM\Entity
 */
class Server
{
    /**
     * @var int
     *
     * @ORM\Column(name="uid", type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $uid;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=255)
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="os", type="string", length=255)
     */
    private $os;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="Snapshot", mappedBy="server")
     * @ORM\OrderBy({"timestamp" = "ASC"})
     **/
    private $snapshots;

    /**
     * Get id.
     *
     * @return int
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Set ip.
     *
     * @param string $ip
     *
     * @return Server
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip.
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Server
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set os.
     *
     * @param string $os
     *
     * @return Server
     */
    public function setOs($os)
    {
        $this->os = $os;

        return $this;
    }

    /**
     * Get os.
     *
     * @return string
     */
    public function getOs()
    {
        return $this->os;
    }

    /**
     * Set Status.
     *
     * @param string $status
     *
     * @return Server
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get Status.
     *
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->snapshots = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add snapshots
     *
     * @param \Innova\Heartbeat\AppBundle\Entity\Snapshot $snapshots
     * @return Server
     */
    public function addSnapshot(\Innova\Heartbeat\AppBundle\Entity\Snapshot $snapshots)
    {
        $this->snapshots[] = $snapshots;

        return $this;
    }

    /**
     * Remove snapshots
     *
     * @param \Innova\Heartbeat\AppBundle\Entity\Snapshot $snapshots
     */
    public function removeSnapshot(\Innova\Heartbeat\AppBundle\Entity\Snapshot $snapshots)
    {
        $this->snapshots->removeElement($snapshots);
    }

    /**
     * Get snapshots
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSnapshots()
    {
        return $this->snapshots;
    }
}
