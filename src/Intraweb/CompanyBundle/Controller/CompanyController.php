<?php

namespace Intraweb\CompanyBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Intraweb\CompanyBundle\Entity\Company;
use Intraweb\CompanyBundle\Form\CompanyType;

/**
 * Company controller.
 *
 */
class CompanyController extends Controller {

    /**
     * Lists all Company entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $userId = $user->getId();

        $entity = $em->getRepository('IntrawebCompanyBundle:Company')->findOneByOwner($userId);
        if ($entity) {
            $alreadyOwnCompany = true;
        } else {
            $alreadyOwnCompany = false;
        }

        return $this->render('IntrawebCompanyBundle:Company:index.html.twig', array(
                    'entity' => $entity,
                    'alreadyOwnCompany' => $alreadyOwnCompany,
        ));
    }

    /**
     * Creates a new Company entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new Company();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        $user = $this->get('security.context')->getToken()->getUser();

        $entity->setOwner($user);
        if (!$this->isOwner($user)) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('company_show', array('id' => $entity->getId())));
            }

            return $this->render('IntrawebCompanyBundle:Company:new.html.twig', array(
                        'entity' => $entity,
                        'form' => $form->createView(),
            ));
        }
        
        $this->get('session')->getFlashBag()->add(
            'notice',
            'You cannot create another Company. Please delete the previous one.'
        );
        return $this->forward('IntrawebCompanyBundle:Company:index');
    }

    /**
     * Creates a form to create a Company entity.
     *
     * @param Company $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Company $entity) {
        $form = $this->createForm(new CompanyType(), $entity, array(
            'action' => $this->generateUrl('company_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Company entity.
     *
     */
    public function newAction() {
        $entity = new Company();
        $form = $this->createCreateForm($entity);
        
        return $this->render('IntrawebCompanyBundle:Company:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Company entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntrawebCompanyBundle:Company')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IntrawebCompanyBundle:Company:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing Company entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntrawebCompanyBundle:Company')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IntrawebCompanyBundle:Company:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Company entity.
     *
     * @param Company $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Company $entity) {
        $form = $this->createForm(new CompanyType(), $entity, array(
            'action' => $this->generateUrl('company_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Company entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntrawebCompanyBundle:Company')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('company_edit', array('id' => $id)));
        }

        return $this->render('IntrawebCompanyBundle:Company:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Company entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('IntrawebCompanyBundle:Company')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Company entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('company'));
    }

    /**
     * Creates a form to delete a Company entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('company_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

    private function IsOwner($user) {
        $userId = $user->getId();
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('IntrawebCompanyBundle:Company')->findOneByOwner($userId);
        if ($entity)
            return true;
        return false;
    }

}
