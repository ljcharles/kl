<?php

namespace KL\RestaurationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IngredientController extends Controller
{
    public function indexAction()
    {
        return $this->render('KLRestaurationBundle:Ingredient:index.html.twig', array(
            // ...
        ));
    }

    public function viewAction()
    {
        return $this->render('KLRestaurationBundle:Ingredient:view.html.twig', array(
            // ...
        ));
    }

    public function editAction()
    {
        return $this->render('KLRestaurationBundle:Ingredient:edit.html.twig', array(
            // ...
        ));
    }

    public function addAction()
    {
        return $this->render('KLRestaurationBundle:Ingredient:add.html.twig', array(
            // ...
        ));
    }

    public function deleteAction()
    {
        return $this->render('KLRestaurationBundle:Ingredient:delete.html.twig', array(
            // ...
        ));
    }
}
