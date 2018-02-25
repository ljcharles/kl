<?php

namespace KL\RestaurationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class PanierController extends Controller
{
  public function indexAction(Request $request)
  {
      $session = $request->getSession();

      if (!$session->has('panier')) $session->set('panier',array());
      $panier = $session->get('panier');

      $em = $this->getDoctrine()->getManager();
      $produits = $em->getRepository('KLRestaurationBundle:Produit')
      ->findArray(array_keys($panier));

      return $this->render('KLRestaurationBundle:Panier:index.html.twig', array(
          'produits' => $produits,
          'panier' => $panier
      ));
  }

  public function addAction($id,Request $request)
  {
    $session = $request->getSession();

    if (!$session->has('panier')) $session->set('panier',array());
    $panier = $session->get('panier');

    $qte = $request->query->get('qte');
    if (array_key_exists($id,$panier)) {
      if ($qte != null) $panier[$id] = $qte;
      $request->getSession()->getFlashBag()
      ->add('notice', 'Quantité modifiée.');
    }else {
      if ($qte != null) $panier[$id] = $qte;
      else $panier[$id] = 1;
      $request->getSession()->getFlashBag()
      ->add('notice', 'Article ajouté.');
    }

    $session->set('panier',$panier);

    return $this->redirectToRoute('kl_restauration_panier_homepage');
  }

  public function deleteAction(Request $request, $id)
  {
    $session = $request->getSession();
    $panier = $session->get('panier');

    if (array_key_exists($id,$panier)) {
      unset($panier[$id]);
      $session->set('panier',$panier);
      $request->getSession()->getFlashBag()
      ->add('notice', 'Article supprimé.');
    }

    return $this->redirectToRoute('kl_restauration_panier_homepage');
  }
}
