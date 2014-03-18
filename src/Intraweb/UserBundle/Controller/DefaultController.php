<?php

namespace Intraweb\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('IntrawebUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
