<?php

namespace KL\RestaurationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use KL\RestaurationBundle\Entity\CommandeProduit;
use KL\RestaurationBundle\Repository\CommandeProduitRepository;
use KL\UserBundle\Entity\User;
use KL\UserBundle\Repository\UserRepository;

class CuisinierController extends Controller
{
  public function indexAction()
  {
      $em = $this->getDoctrine()->getManager();
      $listproduit = $em
        ->getRepository('KLRestaurationBundle:CommandeProduit')
        ->findAll()
      ;

      return $this->render('KLRestaurationBundle:Cuisinier:index.html.twig', array(
          'listproduit' => $listproduit
      ));
  }

  public function vueparcommandeAction()
  {
      $em = $this->getDoctrine()->getManager();
      $listproduit = $em
        ->getRepository('KLRestaurationBundle:CommandeProduit')
        ->findBylesCommande()
      ;

      return $this->render('KLRestaurationBundle:Cuisinier:index.html.twig', array(
          'listproduit' => $listproduit
      ));
  }

  public function vueparproduitAction()
  {
      $em = $this->getDoctrine()->getManager();
      $listproduit = $em
        ->getRepository('KLRestaurationBundle:CommandeProduit')
        ->findBylesProduit()
      ;

      return $this->render('KLRestaurationBundle:Cuisinier:index.html.twig', array(
          'listproduit' => $listproduit
      ));
  }

  public function faireproduitAction($id,$id_cuisinier)
  {
      $em = $this->getDoctrine()->getManager();
      $commandeProduit = $em
        ->getRepository('KLRestaurationBundle:CommandeProduit')
        ->find($id)
      ;

      $commandeProduit->setEtat(1);
      $commandeProduit->setCuisinier($id_cuisinier);
      $em->persist($commandeProduit);
      $em->flush();

      if (null === $commandeProduit) {
        throw new NotFoundHttpException("Le produit d'id ".$produit."de la commande d'id".$commande." n'existe pas.");
      }
      // Si la requête est en POST

      return $this->render('KLRestaurationBundle:Cuisinier:view.html.twig', array(
          'commandeProduit' => $commandeProduit
      ));

  }

  public function monhistoriqueAction($id_cuisinier,Request $request)
  {
      $em = $this->getDoctrine()->getManager();
      $listproduit = $em
        ->getRepository('KLRestaurationBundle:CommandeProduit')
        ->findMonhistorique($id_cuisinier)
      ;
      if (null === $listproduit) {

        $request->getSession()->getFlashBag()->add('success', 'Vous n\'avez pas encore fait de produit');

        return $this->redirectToRoute('kl_restauration_cuisinier_homepage',array(
            'listproduit' => $listproduit
        ));
      }
      return $this->render('KLRestaurationBundle:Cuisinier:index.html.twig', array(
          'listproduit' => $listproduit
      ));

  }

  public function produitTerminerAction($id)
  {
      $em = $this->getDoctrine()->getManager();
      $commandeProduit = $em
        ->getRepository('KLRestaurationBundle:CommandeProduit')
        ->find($id)
      ;

      $commandeProduit->setEtat(2);
      $em->persist($commandeProduit);
      $em->flush();
      $listproduit = $em
        ->getRepository('KLRestaurationBundle:CommandeProduit')
        ->findAll()
      ;

      return $this->redirectToRoute('kl_restauration_cuisinier_homepage',array(
        'listproduit' => $listproduit
      ));

  }

  public function PreparationAnnulerAction($id,Request $request)
  {
      $em = $this->getDoctrine()->getManager();
      $commandeProduit = $em
        ->getRepository('KLRestaurationBundle:CommandeProduit')
        ->find($id)
      ;

      $commandeProduit->setEtat(0);
      $commandeProduit->setCuisinier(0);

      if ($request->isMethod('POST')) {
        $em->persist($commandeProduit);
        $em->flush();

        $request->getSession()->getFlashBag()->add('info', "Préparation Annuler.");

        return $this->redirectToRoute('kl_restauration_cuisinier_homepage');
      }


      return $this->redirectToRoute('kl_restauration_cuisinier_homepage');

  }

}
