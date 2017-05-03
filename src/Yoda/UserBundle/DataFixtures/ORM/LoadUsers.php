<?php

namespace Yoda\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Yoda\UserBundle\Entity\User;

class LoadUsers implements FixtureInterface, ContainerAwareInterface {

    public function load(ObjectManager $manager) {
        $user = new User();
        $user->setUsername('darth');
        // todo - fill in this encoded password... ya know... somehow...
        $user->setPassword($this->encodePassword($user, 'darthpass'));
        $user->setEmail('darth@deathstar.com');
        $manager->persist($user);


        $admin = new User();
        $admin->setUsername('wayne');
        $admin->setPassword($this->encodePassword($admin, 'waynepass'));
        $admin->setRoles(array('ROLE_ADMIN'));
        $admin->setEmail('wayne@deathstar.com');
        $manager->persist($admin);


        // the queries aren't done until now
        $manager->flush();
    }

    public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = null) {
        $this->container = $container;
    }

    private function encodePassword(User $user, $plainPassword) {
        $encoder = $this->container->get('security.encoder_factory')
                ->getEncoder($user)
        ;

        return $encoder->encodePassword($plainPassword, $user->getSalt());
    }

}
