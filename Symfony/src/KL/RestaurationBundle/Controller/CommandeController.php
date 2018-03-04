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
use JMS\Payment\CoreBundle\Form\ChoosePaymentMethodType;
use JMS\Payment\CoreBundle\PluginController\Result;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CommandeController extends Controller
{
  public function indexAction()
  {
    if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
      $user = $this->getUser();

      $commandes = $user->getCommandes();

      foreach ($commandes as $commande) {
        $commande->setEtat($this->verifCommande($commande));
      }

      return $this->render('KLRestaurationBundle:Commande:index.html.twig', array(
          'commandes' => $commandes,
          'user' => $user
      ));
    }
  }

  public function viewAction($id,Request $request)
  {
    if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
      $user = $this->getUser();

      $commande = $this->getDoctrine()->getManager()
      ->getRepository('KLRestaurationBundle:Commande')
      ->findWithUser($id,$user);

      $commande[0]->setEtat($this->verifCommande($commande[0]));

      $config = [
          'paypal_express_checkout' => [
            'return_url' => $this->generateUrl('kl_restauration_paymentcreate', [
                'id' => $commande[0]->getId(),
            ], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('kl_restauration_paymentcancel', [
                'id' => $commande[0]->getId(),
            ], UrlGeneratorInterface::ABSOLUTE_URL),
            'useraction' => 'commit',
          ],
      ];

      $form = $this->createForm(ChoosePaymentMethodType::class, null, [
          'amount'   => $commande[0]->getAmount(),
          'currency' => 'EUR',
          'default_method' => 'payment_paypal',
          'predefined_data' => $config,
      ]);

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
          $ppc = $this->get('payment.plugin_controller');
          $ppc->createPaymentInstruction($instruction = $form->getData());

          $commande[0]->setPaymentInstruction($instruction);

          $em = $this->getDoctrine()->getManager();
          $em->persist($commande[0]);
          $em->flush($commande[0]);

          return $this->redirect($this->generateUrl('kl_restauration_paymentcreate', [
              'id' => $commande[0]->getId(),
          ]));
      }

      return $this->render('KLRestaurationBundle:Commande:view.html.twig', array(
          'commande' => $commande,
          'form'  => $form->createView(),
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

      $commande->setAmount($commande->getPrixTotal());
      $em->persist($commande);
      $em->flush();

      $session->remove('panier');

      return $this->redirectToRoute('kl_restauration_commande_homepage');
    }
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
        if($commandeproduit->getEtat()!=2) return 0;
    	}
    }
    return 1;
  }

  private function createPayment($id)
  {
      $user = $this->getUser();

      $em = $this->getDoctrine()->getManager();
      $commande = $em->getRepository('KLRestaurationBundle:Commande')
      ->findWithUser($id,$user);
      $montant = $commande[0]->getPrixTotal();
      $commande[0]->setAmount($montant);
      $em->persist($commande[0]);
      $em->flush();

      $instruction = $commande[0]->getPaymentInstruction();
      $pendingTransaction = $instruction->getPendingTransaction();

      if ($pendingTransaction !== null) {
          return $pendingTransaction->getPayment();
      }

      $ppc = $this->get('payment.plugin_controller');
      $amount = $instruction->getAmount() - $instruction->getDepositedAmount();

      return $ppc->createPayment($instruction->getId(), $amount);
  }

  public function paymentCreateAction($id,Request $request)
  {

      $payment = $this->createPayment($id);
      $ppc = $this->get('payment.plugin_controller');

      $result = $ppc->approveAndDeposit($payment->getId(), $payment->getTargetAmount());

      if (Result::STATUS_PENDING === $result->getStatus()) {
        $ex = $result->getPluginException();

        if ($ex instanceof ActionRequiredException) {
            $action = $ex->getAction();

            if ($action instanceof VisitUrl) {
                return new RedirectResponse($action->getUrl());
            }

            throw $ex;
        }
      }else if (Result::STATUS_SUCCESS !== $result->getStatus()){
        $request->getSession()->getFlashBag()->add('danger', 'Le paiement ne s\'est pas déroulé comme prévu.');
        // throw new \RuntimeException('Transaction was not successful: '.$result->getReasonCode());
        throw $result->getPluginException();
        return $this->redirectToRoute('kl_restauration_commande_homepage');
      }

      return $this->redirectToRoute('kl_restauration_paymentcomplete',array(
        'id' => $id
      ));
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


}
