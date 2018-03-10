<?php

namespace KL\RestaurationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use KL\RestaurationBundle\Entity\GammeProduit;
use KL\RestaurationBundle\Entity\Produit;
use KL\RestaurationBundle\Form\GammeProduitType;
use KL\RestaurationBundle\Form\ProduitType;
use KL\RestaurationBundle\Form\ProduitEditType;
use KL\RestaurationBundle\Form\ProduitCreateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;

class ProduitController extends Controller
{
    public function indexAction(Request $request)
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

        $session = $request->getSession();
        $panier = $session->get('panier');

        return $this->render('KLRestaurationBundle:Produit:index.html.twig', array(
            'listGammeProduits' => $listGammeProduits,
            'listProduits' => $listProduits,
            'panier' => $panier
        ));
    }

    public function viewAction($id,Request $request)
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

      $session = $request->getSession();
      $panier = $session->get('panier');

      return $this->render('KLRestaurationBundle:Produit:view.html.twig', array(
          'listGammeProduits' => $listGammeProduits,
          'produit' => $produit,
          'panier' => $panier
      ));
    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $produit = $em->getRepository('KLRestaurationBundle:Produit')->find($id);

        if (null === $produit) {
          throw new NotFoundHttpException("Le produit d'id ".$id." n'existe pas.");
        }

        $form = $this->createForm(ProduitEditType::class, $produit);
        // Si la requête est en POST
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Produit bien modifiée.');

            return $this->redirectToRoute('kl_restauration_produit_view',array(
              'id' => $produit->getId()
            ));
        }

        return $this->render('KLRestaurationBundle:Produit:edit.html.twig', array(
          'form' => $form->createView(),
          'produit' => $produit
        ));
    }

    public function createAction($gamme,Request $request)
    {
      if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
        $em = $this->getDoctrine()->getManager();
        $GammeProduit = $em
          ->getRepository('KLRestaurationBundle:GammeProduit')
          ->find($gamme)
        ;

        $OneProduit = $em
          ->getRepository('KLRestaurationBundle:Produit')
          ->findByGammeProduit($gamme)
        ;

        $user = $this->getUser();
        date_default_timezone_set("UTC");

        $jour = date('d-m-Y');

        $produit = new Produit();
        $produit->setNom($GammeProduit->getNom().'_'.date('H:i:s'));
        $produit->setGammeProduit($GammeProduit);
        $produit->setDescription('Produit créer le '.$jour.' par '.$user->getUsername());
        $produit->setNote(3);
        $produit->setPrix(5 + $OneProduit[0]->getPrix());

        if ($GammeProduit->getNom() == 'Hamburger') {
          $produit->setImage('/bundles/klrestauration/img/uploads/hamburger1.jpg');
        } elseif ($GammeProduit->getNom() == 'Tarte') {
          $produit->setImage('/bundles/klrestauration/img/uploads/tarte1.jpg');
        }else {
          $produit->setImage('/bundles/klrestauration/img/uploads/pizza1.jpg');
        }

        $form = $this->get('form.factory')->create(ProduitCreateType::class, $produit);

         if ($request->isMethod('POST')) {
           $form->handleRequest($request);

           if ($form->isValid()) {

             // $produit->myUpload();

             $em = $this->getDoctrine()->getManager();
             $em->persist($produit);
             $em->flush();

             $request->getSession()->getFlashBag()->add('notice', 'Produit bien enregistrée.');

             return $this->redirectToRoute('kl_restauration_produit_view',array(
               'id' => $produit->getId()
             ));
           }
         }

         return $this->render('KLRestaurationBundle:Produit:create.html.twig', array(
             'form' => $form->createView(),
             'typeProduit' => $GammeProduit->getNom()
         ));
      }

      return $this->redirectToRoute('fos_user_security_login');
    }

    public function addAction(Request $request)
    {
      $produit = new Produit();
      $form = $this->get('form.factory')->create(ProduitType::class, $produit);

       if ($request->isMethod('POST')) {
         $form->handleRequest($request);

         if ($form->isValid()) {

           $produit->myUpload();

           $em = $this->getDoctrine()->getManager();
           $em->persist($produit);
           $em->flush();

           $request->getSession()->getFlashBag()->add('notice', 'Produit bien enregistrée.');

           return $this->redirectToRoute('kl_restauration_produit_view',array(
             'id' => $produit->getId()
           ));
         }
       }

       return $this->render('KLRestaurationBundle:Produit:add.html.twig', array(
           'form' => $form->createView(),
       ));
    }

    public function deleteAction(Request $request, $id)
    {
      $em = $this->getDoctrine()->getManager();

      $produit= $em->getRepository('KLRestaurationBundle:Produit')->find($id);

      if (null === $produit) {
        throw new NotFoundHttpException("Le produit d'id ".$id." n'existe pas.");
      }

      // On crée un formulaire vide, qui ne contiendra que le champ CSRF
      // Cela permet de protéger la suppression d'annonce contre cette faille
      $form = $this->get('form.factory')->create();

      if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        $em->remove($produit);
        $em->flush();

        $request->getSession()->getFlashBag()->add('info', "Ce produit a bien été supprimée.");

        return $this->redirectToRoute('kl_restauration_produit_homepage');
      }

      return $this->render('KLRestaurationBundle:Produit:delete.html.twig', array(
        'produit' => $produit,
        'form'   => $form->createView(),
      ));
    }

    public function addGammeAction(Request $request)
    {
       $gamme = new GammeProduit();
       $form = $this->get('form.factory')->create(GammeProduitType::class, $gamme);

       $em = $this->getDoctrine()->getManager();
       $listGammeProduits = $em
         ->getRepository('KLRestaurationBundle:GammeProduit')
         ->findAllOrderedByName()
       ;

        if ($request->isMethod('POST')) {
          $form->handleRequest($request);

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($gamme);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Gamme bien enregistrée.');

            return $this->redirectToRoute('kl_restauration_gamme_view',array(
              'id' => $gamme->getId()
            ));
          }
        }

        return $this->render('KLRestaurationBundle:Produit:addGamme.html.twig', array(
            'form' => $form->createView(),
            'listGammeProduits' => $listGammeProduits,
        ));
    }

    public function viewGammeAction($id, Request $request)
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

      $session = $request->getSession();
      $panier = $session->get('panier');

      return $this->render('KLRestaurationBundle:Produit:index.html.twig', array(
          'listGammeProduits' => $listGammeProduits,
          'listProduits' => $listProduits,
          'panier' => $panier
        ));
    }

    public function editGammeAction($id, Request $request)
    {
      $em = $this->getDoctrine()->getManager();

      $gamme = $em->getRepository('KLRestaurationBundle:GammeProduit')->find($id);

      $listGammeProduits = $em
        ->getRepository('KLRestaurationBundle:GammeProduit')
        ->findAllOrderedByName()
      ;

      if (null === $gamme) {
        throw new NotFoundHttpException("La gamme d'id ".$id." n'existe pas.");
      }

      $form = $this->createForm(GammeProduitType::class, $gamme);
      // Si la requête est en POST
      if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
      {
          $em->flush();

          $request->getSession()->getFlashBag()->add('success', 'Gamme bien modifiée.');

          return $this->redirectToRoute('kl_restauration_gamme_add',array(
            'id' => $gamme->getId()
          ));
      }

      return $this->render('KLRestaurationBundle:Produit:editGamme.html.twig', array(
        'form' => $form->createView(),
        'listGammeProduits' => $listGammeProduits
      ));
    }

    public function deleteGammeAction(Request $request, $id)
    {
      $em = $this->getDoctrine()->getManager();

      $gamme = $em->getRepository('KLRestaurationBundle:GammeProduit')->find($id);

      if (null === $gamme) {
        throw new NotFoundHttpException("La gamme d'id ".$id." n'existe pas.");
      }

      // On crée un formulaire vide, qui ne contiendra que le champ CSRF
      // Cela permet de protéger la suppression d'annonce contre cette faille
      $form = $this->get('form.factory')->create();

      if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        $em->remove($gamme);
        $em->flush();

        $request->getSession()->getFlashBag()->add('info', "Cette gamme a bien été supprimée.");

        return $this->redirectToRoute('kl_restauration_produit_homepage');
      }

      return $this->render('KLRestaurationBundle:Produit:deleteGamme.html.twig', array(
        'gamme' => $gamme,
        'form'   => $form->createView(),
      ));
    }

    public function searchBarAction()
    {
      $form = $this->createFormBuilder(null)
        ->add('search', SearchType::class)
        ->getForm();
      return $this->render('KLRestaurationBundle:Produit:searchBar.html.twig', array(
        'form'   => $form->createView(),
      ));
    }

    public function searchAction(Request $request)
    {
      $term = $request->request->get('form')['search'];

      if (strlen($term) < 3) {
        $listProduits=[];
      } else {
        $em = $this->getDoctrine()->getManager();
        $listProduits= $em->getRepository('KLRestaurationBundle:Produit')
        ->getLikeQuery($term);
      }

      $session = $request->getSession();
      $panier = $session->get('panier');

      return $this->render('KLRestaurationBundle:Produit:search.html.twig', array(
        'listProduits'   => $listProduits,
        'panier' => $panier
      ));
    }
}
