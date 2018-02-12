<?php

namespace KL\RestaurationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProduitController extends Controller
{
    public function indexAction()
    {
        return $this->render('KLRestaurationBundle:Produit:index.html.twig', array(
            // ...
        ));
    }

    public function viewAction()
    {
        return $this->render('KLRestaurationBundle:Produit:view.html.twig', array(
            // ...
        ));
    }

    public function editAction()
    {
        return $this->render('KLRestaurationBundle:Produit:edit.html.twig', array(
            // ...
        ));
    }

    public function addAction()
    {
        return $this->render('KLRestaurationBundle:Produit:add.html.twig', array(
            // ...
        ));
    }

    public function deleteAction()
    {
        return $this->render('KLRestaurationBundle:Produit:delete.html.twig', array(
            // ...
        ));
    }

}
