<?php

namespace KL\RestaurationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use KL\RestaurationBundle\Entity\CommandeProduit;
use KL\RestaurationBundle\Entity\Commande;
use KL\RestaurationBundle\Entity\AdressLivraison;
use KL\RestaurationBundle\Form\AdressLivraisonType;

class CommandeController extends Controller
{
  public function indexAction()
  {
    if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
      $user = $this->getUser();

      $commandes = $user->getCommandes();
      return $this->render('KLRestaurationBundle:Commande:index.html.twig', array(
          'commandes' => $commandes,
          'user' => $user
      ));
    }
  }

  public function viewAction($id)
  {
    if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
      $user = $this->getUser();

      $commande = $this->getDoctrine()->getManager()
      ->getRepository('KLRestaurationBundle:Commande')
      ->findWithUser($id,$user);

      return $this->render('KLRestaurationBundle:Commande:view.html.twig', array(
          'commande' => $commande,
          'user' => $user
      ));
    }
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
         $em->flush();

         $commande = $this->getDoctrine()->getManager()
         ->getRepository('KLRestaurationBundle:Commande')
         ->find($id);

         $commande->setAdressLivraison($adressLivraison);

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
        if($commandeproduit->getEtat!=2) return 0;
    	}
    }
    return 1;
  }

}
