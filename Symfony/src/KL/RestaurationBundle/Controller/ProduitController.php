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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

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
        ->add('search', TextType::class)
        ->add('Rechercher', SubmitType::class)
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

      return $this->render('KLRestaurationBundle:Produit:search.html.twig', array(
        'listProduits'   => $listProduits
      ));
    }
}
