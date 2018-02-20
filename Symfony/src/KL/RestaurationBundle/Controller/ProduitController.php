<?php

namespace KL\RestaurationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProduitController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $listGammeProduits = $em
          ->getRepository('KLRestaurationBundle:GammeProduit')
          ->findAllOrderedByName()
        ;

        $listProduits = $em
          ->getRepository('KLRestaurationBundle:Produit')
          ->findAll()
        ;

        return $this->render('KLRestaurationBundle:Produit:index.html.twig', array(
            'listGammeProduits' => $listGammeProduits,
            'listProduits' => $listProduits
        ));
    }

    public function viewAction($id)
    {
      $em = $this->getDoctrine()->getManager();
      $listGammeProduits = $em
        ->getRepository('KLRestaurationBundle:GammeProduit')
        ->findAllOrderedByName()
      ;

      $produit = $em
        ->getRepository('KLRestaurationBundle:Produit')
        ->find($id)
      ;

      return $this->render('KLRestaurationBundle:Produit:view.html.twig', array(
          'listGammeProduits' => $listGammeProduits,
          'produit' => $produit
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

    public function viewGammeAction($id)
    {
      $em = $this->getDoctrine()->getManager();

      $listGammeProduits = $em
        ->getRepository('KLRestaurationBundle:GammeProduit')
        ->findAllOrderedByName()
      ;

      $gammeProduit = $em
        ->getRepository('KLRestaurationBundle:GammeProduit')
        ->find($id);
      ;

      $listProduits = $gammeProduit->getProduits();

      return $this->render('KLRestaurationBundle:Produit:index.html.twig', array(
          'listGammeProduits' => $listGammeProduits,
          'listProduits' => $listProduits
      ));
    }
}
