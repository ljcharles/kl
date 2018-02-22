<?php

namespace KL\RestaurationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use KL\RestaurationBundle\Entity\GammeProduit;
use KL\RestaurationBundle\Entity\Produit;
use KL\RestaurationBundle\Form\GammeProduitType;
use KL\RestaurationBundle\Form\ProduitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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

    public function editAction()
    {
        return $this->render('KLRestaurationBundle:Produit:edit.html.twig', array(
            // ...
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

    public function deleteAction()
    {
        return $this->render('KLRestaurationBundle:Produit:delete.html.twig', array(
            // ...
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

    public function editGammeAction($value='')
    {
      return $this->render('KLRestaurationBundle:Produit:editGamme.html.twig', array(
          // ...
      ));
    }

    public function deleteGammeAction($value='')
    {
      return $this->render('KLRestaurationBundle:Produit:deleteGamme.html.twig', array(
          // ...
      ));
    }
}
