<?php

namespace CurveGame\EntityBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Status
 */
class Status
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $number;

    /**
     * @var ArrayCollection
     */
    private $players;


    /**
     * Set some attributes...
     */
    public function __construct() {

        $this->players = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Status
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set number
     *
     * @param integer $number
     * @return Status
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set players
     *
     * @param ArrayCollection $players
     */
    public function setPlayers(ArrayCollection $players) {

        $this->players = $players;
    }

    /**
     * @return ArrayCollection
     *
     * Get all players from this status type
     */
    public function getPlayers() {

        return $this->players;
    }
}
