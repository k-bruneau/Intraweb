<?php

namespace Intraweb\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('IntrawebAppBundle:Default:index.html.twig');
    }
}
