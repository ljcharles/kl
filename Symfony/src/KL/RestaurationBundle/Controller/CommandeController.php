<?php

namespace KL\RestaurationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use KL\RestaurationBundle\Entity\CommandeProduit;
use KL\RestaurationBundle\Entity\Commande;

class CommandeController extends Controller
{
  public function indexAction()
  {

  }

  public function viewAction($id)
  {

  }


  public function addAction(Request $request)
  {
    $session = $request->getSession();

    if (!$session->has('panier')) $session->set('panier',array());
    $panier = $session->get('panier');

    $commande = new Commande();
    $commande->setEtat(0);

    dump($panier);
    $em = $this->getDoctrine()->getManager();

    foreach ($panier as $product_id => $product_quantity) {
      $produit = $em->getRepository('KLRestaurationBundle:Produit')
          ->find($product_id);

      for ($i=0; $i < $product_quantity; $i++) {
        // Ajoute un Commande produit
        $commandeProduit = new CommandeProduit();
        $commandeProduit->setCommande($commande);
        $commandeProduit->setProduit($produit);
        $commandeProduit->setEtat(0);

        $commande->addCommandeProduit($commandeProduit);
      }

      dump($produit);

    }

    $em->persist($commande);
    $em->flush();

    /*

    $produits = $em->getRepository('KLRestaurationBundle:Produit')
    ->findArray(array_keys($panier));


    foreach ($produits as $produit) {
      $commande->addProduit($produit);

      $quantity = $produit
    }
    */
    return $this->render('KLRestaurationBundle:Commande:add.html.twig');
  }


  public function editAction($id, Request $request)
  {

  }


  public function deleteAction($id)
  {

  }

}
