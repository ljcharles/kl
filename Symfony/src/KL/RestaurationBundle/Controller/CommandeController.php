<?php

namespace KL\RestaurationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use KL\RestaurationBundle\Entity\CommandeProduit;
use KL\RestaurationBundle\Entity\Commande;
use KL\RestaurationBundle\Entity\AdressLivraison;
use KL\RestaurationBundle\Form\AdressLivraisonType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CommandeController extends Controller
{
  public function indexAction()
  {
    if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
      $user = $this->getUser();
      $em = $this->getDoctrine()->getManager();
      $commandes = $user->getCommandes();

      foreach ($commandes as $commande) {
        $etat = $this->verifCommande($commande);
        $commande->setEtat($etat);
        $em->persist($commande);
        $em->flush();
      }

      return $this->render('KLRestaurationBundle:Commande:index.html.twig', array(
          'commandes' => $commandes,
          'user' => $user
      ));
    }

    return $this->redirectToRoute('fos_user_security_login');
  }

  public function viewAction($id,Request $request)
  {
    if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
      $user = $this->getUser();

      $commande = $this->getDoctrine()->getManager()
      ->getRepository('KLRestaurationBundle:Commande')
      ->findWithUser($id,$user);

      if (!isset($commande[0])) {
        return $this->redirectToRoute('kl_core_error');
      }



      return $this->render('KLRestaurationBundle:Commande:view.html.twig', array(
          'commande' => $commande,
          'user' => $user
      ));
    }

    return $this->redirectToRoute('fos_user_security_login');
  }


  public function addAction(Request $request)
  {
    if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {

      $session = $request->getSession();
      $user = $this->getUser();

      if (!$session->has('panier')) $session->set('panier',array());
      $panier = $session->get('panier');

      $commande = new Commande();
      $commande->setEtat(0);
      $commande->setUser($user);

      $em = $this->getDoctrine()->getManager();

      foreach ($panier as $product_id => $product_quantity) {
        $produit = $em->getRepository('KLRestaurationBundle:Produit')
        ->find($product_id);

        for ($i=0; $i < $product_quantity; $i++) {

          $commandeProduit = new CommandeProduit();
          $commandeProduit->setCommande($commande);
          $commandeProduit->setProduit($produit);
          $commandeProduit->setEtat(0);

          $commande->addCommandeProduit($commandeProduit);
        }

      }

      $commande->setAmount($commande->getPrixTotal());
      $em->persist($commande);
      $em->flush();

      $session->remove('panier');

      return $this->redirectToRoute('kl_restauration_commande_homepage');
    }

    return $this->redirectToRoute('fos_user_security_login');
  }

  public function adressAddAction($id,Request $request)
  {
    $adressLivraison = new AdressLivraison();
    $form = $this->get('form.factory')->create(AdressLivraisonType::class, $adressLivraison);

     if ($request->isMethod('POST')) {
       $form->handleRequest($request);

       if ($form->isValid()) {

         $em = $this->getDoctrine()->getManager();
         $em->persist($adressLivraison);

         $commande = $em->getRepository('KLRestaurationBundle:Commande')->find($id);
         $commande->setAdressLivraison($adressLivraison);

         $em->flush();

         $request->getSession()->getFlashBag()->add('notice', 'Adresse de livraison bien enregistrée.');

         return $this->redirectToRoute('kl_restauration_commande_homepage');
       }
     }

     return $this->render('KLRestaurationBundle:AdressLivraison:add.html.twig', array(
         'form' => $form->createView(),
     ));
  }

  public function verifCommande($commande)
  {
    if($commande->getEtat()==0)
    {
      $listCommandeProduits = $commande->getCommandeProduits();

    	foreach ($listCommandeProduits as $commandeproduit) {
        if($commandeproduit->getEtat()!=2){
          if ($commandeproduit->getEtat() == 1) {
            return 1;
          }else {
            return 0;
          }
        }
    	}
    }
    return 1;
  }

  public function paymentCompleteAction($id, Request $request)
  {
      $request->getSession()->getFlashBag()->add('notice', 'Le paiement s\'est déroulé avec succès.');

      $user = $this->getUser();

      $em = $this->getDoctrine()->getManager();
      $commande = $em->getRepository('KLRestaurationBundle:Commande')
      ->findWithUser($id,$user);

      $commande[0]->setPayer(1);
      $commande[0]->setDatePaiement(new \Datetime());
      $em->persist($commande[0]);
      $em->flush();

      return $this->redirectToRoute('kl_restauration_commande_view',array(
        'id' => $id
      ));
  }

  public function paymentCancelAction($id, Request $request)
  {
      $request->getSession()->getFlashBag()->add('notice', 'Le paiement à été annulé par l\'utilisateur.');
      return $this->redirectToRoute('kl_restauration_commande_view',array(
        'id' => $id
      ));
  }

  public function paymentErrorAction(Request $request)
  {
      $request->getSession()->getFlashBag()->add('notice', 'Une erreur s\'est produite durant la transaction.');
      return $this->redirectToRoute('kl_restauration_commande_homepage');
  }


}
