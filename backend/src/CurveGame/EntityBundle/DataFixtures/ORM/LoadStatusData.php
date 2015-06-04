<?php

namespace CurveGame\EntityBundle\DataFixtures\ORM;

use CurveGame\EntityBundle\Entity\Status;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadStatusData implements FixtureInterface
{
    /**
     * Loads all status objects in the database
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager) {

        $waiting = new Status();
        $waiting
            ->setName('waiting')
            ->setNumber(1);
        $manager->persist($waiting);

        $waitingForReady = new Status();
        $waitingForReady
            ->setName('waiting for ready')
            ->setNumber(2);
        $manager->persist($waitingForReady);

        $ready = new Status();
        $ready
            ->setName('ready')
            ->setNumber(3);
        $manager->persist($ready);

        $playing = new Status();
        $playing
            ->setName('playing')
            ->setNumber(4);
        $manager->persist($playing);

        $finished = new Status();
        $finished
            ->setName('finished')
            ->setNumber(5);
        $manager->persist($finished);

        $manager->flush();
    }
}
