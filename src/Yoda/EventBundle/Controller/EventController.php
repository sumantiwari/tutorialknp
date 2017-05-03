<?php

namespace Yoda\EventBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Yoda\EventBundle\Controller\Controller;
use Yoda\EventBundle\Entity\Event;

/**
 * Event controller.
 *
 */
class EventController extends Controller {

    /**
     * Lists all event entities.
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $events = $em->getRepository('EventBundle:Event')->findAll();

//        return $this->render('EventBundle:Event:index.html.twig', array(
//            'entities' => $events,
//        ));
        return array(
            'entities' => $events,
        );
    }

    /**
     * Creates a new event entity.
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request) {


        $event = new Event();
        $form = $this->createForm('Yoda\EventBundle\Form\EventType', $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();
            $event->setOwner($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('event_show', array('slug' => $entity->getSlug()));
        }

        return $this->render('EventBundle:Event:new.html.twig', array(
                    'event' => $event,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a event entity.
     *
     */
    public function showAction($slug) {
//        $deleteForm = $this->createDeleteForm($event);
        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('EventBundle:Event')
                ->findOneBy(array('slug' => $slug));

//        $deleteForm = $this->createDeleteForm($entity->getId());
        return $this->render('EventBundle:Event:show.html.twig', array(
                    'entity' => $event,
//            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing event entity.
     *
     */
    public function editAction(Request $request, Event $event) {
        $this->enforceUserSecurity();

        $this->enforceOwnerSecurity($event);


        $deleteForm = $this->createDeleteForm($event);
        $editForm = $this->createForm('Yoda\EventBundle\Form\EventType', $event);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('event_edit', array('id' => $event->getId()));
        }

        return $this->render('EventBundle:Event:edit.html.twig', array(
                    'entity' => $event,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a event entity.
     *
     */
    public function deleteAction(Request $request, Event $event) {
        $form = $this->createDeleteForm($event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($event);
            $em->flush();
        }

        return $this->redirectToRoute('event_index');
    }

    /**
     * Creates a form to delete a event entity.
     *
     * @param Event $event The event entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Event $event) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('event_delete', array('id' => $event->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

    private function enforceUserSecurity($role = 'ROLE_USER') {
        if (!$this->getSecurityContext()->isGranted($role)) {
            // in Symfony 2.5
            // throw $this->createAccessDeniedException('message!');
            throw new AccessDeniedException('Need ' . $role);
        }
    }

//    private function enforceOwnerSecurity(Event $event) {
//        $user = $this->getUser();
//
//        if ($user != $event->getOwner()) {
//            // if you're using 2.5 or higher
//            // throw $this->createAccessDeniedException('You are not the owner!!!');
//            throw new AccessDeniedException('You are not the owner!!!');
//        }
//    }
}
