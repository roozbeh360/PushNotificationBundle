<?php

namespace Rth\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="notification_devices")
 */
class Device
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
   
    /**
     * @ORM\Column(type="text")
     */
    protected $token;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $os;

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
     * Set token
     *
     * @param string $token
     * @return Device
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set os
     *
     * @param string $os
     * @return Device
     */
    public function setOs($os)
    {
        $this->os = $os;

        return $this;
    }

    /**
     * Get os
     *
     * @return string 
     */
    public function getOs()
    {
        return $this->os;
    }
}
