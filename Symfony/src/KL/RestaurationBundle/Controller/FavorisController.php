<?php

namespace KL\RestaurationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class FavorisController extends Controller
{
  public function indexAction(Request $request)
  {
      $session = $request->getSession();

      if (!$session->has('favoris')) $session->set('favoris',array());
      $favoris = $session->get('favoris');

      $em = $this->getDoctrine()->getManager();
      $produits = $em->getRepository('KLRestaurationBundle:Produit')
      ->findArray(array_keys($favoris));

      return $this->render('KLRestaurationBundle:Favoris:index.html.twig', array(
          'produits' => $produits,
          'favoris' => $favoris
      ));
  }

  public function addAction($id,Request $request)
  {
    $session = $request->getSession();

    if (!$session->has('favoris')) $session->set('favoris',array());
    $favoris = $session->get('favoris');

    $favoris[$id] = 1;
    $request->getSession()->getFlashBag()
    ->add('notice', 'Favoris ajouté.');

    $session->set('favoris',$favoris);

    return $this->redirectToRoute('kl_restauration_favoris_homepage');
  }

  public function deleteAction(Request $request, $id)
  {
    $session = $request->getSession();
    $favoris = $session->get('favoris');

    if (array_key_exists($id,$favoris)) {
      unset($favoris[$id]);
      $session->set('favoris',$favoris);
      $request->getSession()->getFlashBag()
      ->add('notice', 'Favoris supprimé.');
    }

    return $this->redirectToRoute('kl_restauration_favoris_homepage');
  }
}
