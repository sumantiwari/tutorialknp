<?php

namespace Yoda\EventBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints\DateTime;
use Yoda\EventBundle\Entity\Event;

class LoadEvents implements FixtureInterface, OrderedFixtureInterface {

    public function getOrder() {
        return 20;
    }

    public function load(ObjectManager $manager) {

        $owner = $manager->getRepository('UserBundle:User')
                ->findOneByUsernameOrEmail('darth');

        $event1 = new Event();
        $event1->setName('Darth\'s Birthday Party!');
        $event1->setLocation('Deathstar');
        $event1->setTime(new \DateTime('tomorrow noon'));
        $event1->setDetail('Ha! Darth HATES surprises!!!');
        $manager->persist($event1);

        $event2 = new Event();
        $event2->setName('Rebellion Fundraiser Bake Sale!');
        $event2->setLocation('Endor');
        $event2->setTime(new \DateTime('Thursday noon'));
        $event2->setDetail('Ewok pies! Support the rebellion!');
        $manager->persist($event2);

        $event1->setOwner($owner);
        $event2->setOwner($owner);

        // the queries aren't done until now
        $manager->flush();
    }

}
