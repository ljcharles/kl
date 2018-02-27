<?php

namespace KL\RestaurationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use KL\RestaurationBundle\Form\IngredientType;
use KL\RestaurationBundle\Entity\Ingredient;

class IngredientController extends Controller
{
  public function indexAction()
  {
      $em = $this->getDoctrine()->getManager();
      $listIngredients = $em
        ->getRepository('KLRestaurationBundle:Ingredient')
        ->findAllOrderedByName()
      ;

      return $this->render('KLRestaurationBundle:Ingredient:index.html.twig', array(
          'listIngredients' => $listIngredients
      ));
  }

  public function viewAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $listIngredients = $em
      ->getRepository('KLRestaurationBundle:Ingredient')
      ->findAllOrderedByName()
    ;

    $ingredient = $em
      ->getRepository('KLRestaurationBundle:Ingredient')
      ->find($id)
    ;

    return $this->render('KLRestaurationBundle:Ingredient:view.html.twig', array(
        'listIngredients' => $listIngredients,
        'ingredient' => $ingredient
    ));
  }

  public function editAction($id, Request $request)
  {
      $em = $this->getDoctrine()->getManager();

      $ingredient = $em->getRepository('KLRestaurationBundle:Ingredient')->find($id);

      if (null === $ingredient) {
        throw new NotFoundHttpException("L'ingrédient d'id ".$id." n'existe pas.");
      }

      $form = $this->createForm(IngredientType::class, $ingredient);
      // Si la requête est en POST
      if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
      {
          $em->flush();

          $request->getSession()->getFlashBag()->add('success', 'Ingrédient bien modifiée.');

          return $this->redirectToRoute('kl_restauration_ingredient_view',array(
            'id' => $ingredient->getId()
          ));
      }

      return $this->render('KLRestaurationBundle:Ingredient:edit.html.twig', array(
        'form' => $form->createView(),
        'ingredient' => $ingredient
      ));
  }

  public function addAction(Request $request)
  {
    $ingredient = new Ingredient();
    $form = $this->get('form.factory')->create(IngredientType::class, $ingredient);
    // dump($request);

     if ($request->isMethod('POST')) {
       $form->handleRequest($request);

       if ($form->isValid()) {

         $em = $this->getDoctrine()->getManager();
         $em->persist($ingredient);
         $em->flush();

         $request->getSession()->getFlashBag()->add('notice', 'Ingrédient bien enregistrée.');
         // dump($request);
         // dump($request->request->get('kl_restaurationbundle_ingredient')['quantite']);
         // die();

         return $this->redirectToRoute('kl_restauration_ingredient_view',array(
           'id' => $ingredient->getId()
         ));
       }
     }

     return $this->render('KLRestaurationBundle:Ingredient:add.html.twig', array(
         'form' => $form->createView(),
     ));
  }

  public function deleteAction(Request $request, $id)
  {
    $em = $this->getDoctrine()->getManager();

    $ingredient= $em->getRepository('KLRestaurationBundle:Ingredient')->find($id);

    if (null === $ingredient) {
      throw new NotFoundHttpException("L'ingrédient d'id ".$id." n'existe pas.");
    }

    // On crée un formulaire vide, qui ne contiendra que le champ CSRF
    // Cela permet de protéger la suppression d'annonce contre cette faille
    $form = $this->get('form.factory')->create();

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      $em->remove($ingredient);
      $em->flush();

      $request->getSession()->getFlashBag()->add('info', "Cette ingrédient a bien été supprimée.");

      return $this->redirectToRoute('kl_restauration_ingredient_homepage');
    }

    return $this->render('KLRestaurationBundle:Ingredient:delete.html.twig', array(
      'ingredient' => $ingredient,
      'form'   => $form->createView(),
    ));
  }
}
