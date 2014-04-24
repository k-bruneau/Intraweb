<?php

namespace Intraweb\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class EmployeeController extends Controller
{
	private function addUserToCompany($user_to_add)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $repository_user = $doctrine->getRepository('IntrawebUserBundle:User');
        $current_user = $this->get('security.context')->getToken()->getUser();
        $current_user_entity = $repository_user->findOneById($current_user->getId());
        # check si l'entreprise existe
        $to_add_user = $repository_user->findOneByEmail($user_to_add->getEmail());
        if (!$to_add_user)
        {
            $this->get('session')->getFlashBag()->add(
                'failemail',
                $this->get('translator')->trans('This email does not exist')
                );
        }
        else
        {
            if (!$to_add_user->getCompany())
            {
                $to_add_user->setCompany($current_user_entity->getCompany());
                $em->persist($to_add_user);
                $em->flush();
            }
            else
            {
              $this->get('session')->getFlashBag()->add(
                'hascompany',
              	$this->get('translator')->trans('Is already in a company')
                );
            }
        }
    }

    public function addUserAction(Request $request)
    {
        $user_to_add = new User();
        $form = $this->createForm(new EmployeeType, $user_to_add);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $this->get('session')->getFlashBag()->add(
                'email',
               	$this->get('translator')->trans('Email has been taken')
                );
            $this->addUserToCompany($user_to_add);
        }
        return $this->render('IntrawebUserBundle:User:add.html.twig', array('form' => $form->createView()));
    }
}
