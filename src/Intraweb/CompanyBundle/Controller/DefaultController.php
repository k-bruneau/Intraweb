<?php

namespace Intraweb\CompanyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Intraweb\CompanyBundle\Entity\Company;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('IntrawebCompanyBundle:Default:index.html.twig', array('name' => $name));
    }

    public function createCompany()
    {
    	$company = new Company();
    	$company->setCompanyName('This is a company name');

    	$em = $this->getDoctrine()->getManager();
    	$em->persist($company);
    	$em->flush();
        
        return new Response('Company id created : '.$company->getId());
    }

    public function showCompany($id)
    {
    	$company = $this->getDoctrine()
    	->getRepository('IntrawebCompanyBundle:Company')
    	->find($id);

    	if (!$company)
    	{
    		throw $this->createNotFoundException('Missing company with id : '.$id);
    	}
    }
    
    public function updateCompany($id)
    {
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('IntrawebCompanyBundle:Company')->find($id);
        
        if (!$company)
        {
            throw $this->createNotFoundException('Any company found with id : '.$id);
        }
        
        $company->setCompanyName('New company name');
        $em->flush();
        return $this->redirect($this->generateUrl('homepage'));
    }
    
    public function removeCompany($id)
    {
        $em = $this->getDoctrine()->getManager();
        $company - $em->getRepository('IntrawebCompanyBundle:Company')->find($id);
        
        if (!$company)
        {
             throw $this->createNotFoundException('Any company found with id : '.$id);
        }
        
        $em->remove($company);
        $em->flush();
    }
    
    public function addUserToCompany($id, $user)
    {
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepositery('IntrawebCompanyBundle:Company')->find($id);
        if (!$company)
        {
            throw $this->createNotFoundException('Any company found with id : '.$id);
        }
        array_push($company, $user);
    }
}
