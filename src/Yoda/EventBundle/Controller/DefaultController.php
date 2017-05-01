<?php

namespace Yoda\EventBundle\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Yoda\EventBundle\Entity\Event;

class DefaultController extends Controller {

    public function indexAction($name, $count) {

        return $this->render('EventBundle:Default:index.html.twig', ['name' => $name, 'count' => $count]);
    }

    public function setdatabaseAction() {

        $event = new Event();
        $event->setName('Darth\'s surprise birthday party');
        $event->setLocation('Deathstar');
        $event->setTime(new DateTime('tomorrow noon'));
        $event->setDetail('Ha! Darth HATES surprises!!!!');


        $em = $this->get('doctrine')->getManager();
        $em->persist($event);
        $em->flush();

        return new Response('done');
    }

    public function getdatabaseAction($name,$count) {

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('EventBundle:Event');

        $event = $repo->findOneBy(array(
            'name' => 'Darth\'s surprise birthday party',
        ));


        return $this->render(
                        'EventBundle:Default:index.html.twig', array(
                    'name' => $name,
                    'count' => $count,
                    'event' => $event,
                        )
        );
    }

}
