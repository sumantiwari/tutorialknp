<?php

namespace Yoda\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Yoda\UserBundle\Entity\User;
use Yoda\UserBundle\Form\RegisterFormType;

class RegisterController extends Controller {

    /**
     * @Route("/register", name="user_register")
     * @Template
     */
    public function registerAction(Request $request) {
        $user = new User();
 

        $form = $this->createForm(new RegisterFormType(), $user);
        

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            
            $user->setUsername($user->getUsername());
            $user->setEmail($user->getEmail());
            
            $user->setPassword($this->encodePassword($user, $user->getPlainPassword()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

//            $url = $this->generateUrl('event_index');
//            return $this->redirect($url);
            return $this->redirectToRoute('event_index');
        }

        return array('form' => $form->createView());
    }

    private function encodePassword(User $user, $plainPassword) {

        $encoder = $this->container->get('security.encoder_factory')
                ->getEncoder($user)
        ;

        return $encoder->encodePassword($plainPassword, $user->getSalt());
    }

}
