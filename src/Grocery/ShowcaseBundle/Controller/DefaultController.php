<?php

namespace Grocery\ShowcaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ShowcaseBundle:Default:index.html.twig');
    }
}
