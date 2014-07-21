<?php

namespace Project\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ProjectCommonBundle:Default:index.html.twig', array('name' => $name));
    }
}
