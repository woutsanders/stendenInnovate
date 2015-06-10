<?php
//Made by Remco Beikes


namespace CMSTest\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


//DB Entries mappen naar een Doctrine entity
/**
 * @ORM\Entity
 * @ORM\Table(name="player")
 */

class Delete
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="status_id", type="integer")
     */
    protected $Status;

    /**
     * @ORM\Column(name="username", type="string", length=25)
     */
    protected $UserName;

    /**
     * @ORM\Column(name="score", type="integer")
     */
    protected $Score;

    /**
     * @ORM\Column(name="timestamp", type="integer")
     */
    protected $DateTime;

    public function getDateTime()
    {
        return $this->DateTime;
    }

    public function setDateTime($DateTime)
    {
        $this->DateTime = $DateTime;
    }


    public function getScore()
    {
        return $this->Score;
    }

    public function setScore($Score)
    {
        $this->Score = $Score;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }


    public function getStatus()
    {
        return $this->Status;
    }

    public function setStatus($Status)
    {
        $this->Status = $Status;
    }

    public function getUserName()
    {
        return $this->UserName;
    }

    public function setUserName($UserName)
    {
        $this->UserName = $UserName;
    }
}