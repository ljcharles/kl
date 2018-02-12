<?php

namespace KL\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('KLCoreBundle:Default:index.html.twig');
    }
}
