<?php

namespace CurveGame\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Player
 */
class Player
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $hash;

    /**
     * @var string
     */
    private $color;

    /**
     * @var integer
     */
    private $score;

    /**
     * @var integer
     */
    private $timestamp;

    /**
     * @var \DateTime
     */
    private $joinDate;

    /**
     * @var string
     */
    private $status;


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
     * @return Player
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Sets a unique hash (only used for registration).
     *
     * @param $hash
     * @return $this
     */
    private function __setHash($hash) {

        $this->hash = $hash;

        return $this;
    }

    /**
     * Returns the unique hash
     *
     * @return string
     */
    public function getHash() {

        return $this->hash;
    }

    public function setColor($color) {

        $this->color = $color;

        return $this;
    }

    public function getColor() {

        return $this->color;
    }

    /**
     * Set score
     *
     * @param $score
     * @return Player
     */
    public function setScore($score) {

        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return int
     */
    public function getScore() {

        return $this->score;
    }

    /**
     * Set timestamp
     *
     * @param integer $timestamp
     * @return Player
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return integer 
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set join date
     *
     * @param \DateTime $dt
     * @return $this
     */
    public function setJoinDate(\DateTime $dt) {

        $this->joinDate = $dt;

        return $this;
    }

    /**
     * Get join date
     *
     * @return \DateTime
     */
    public function getJoinDate() {

        return $this->joinDate;
    }

    /**
     * Set status
     *
     * @param Status $status
     * @return Player
     */
    public function setStatus(Status $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Gets run just before an object is sent to the DB.
     */
    public function prePersist() {

        $this->__setHash(hash('sha1', uniqid(mt_rand() . time())));
        $this->setJoinDate(new \DateTime('now'));
    }
}
