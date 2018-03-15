<?php

namespace KL\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use KL\UserBundle\Entity\User;
use KL\UserBundle\Form\User2Type;
use KL\UserBundle\Form\AvatarType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    public function indexuserAction()
    {
        $em = $this->getDoctrine()->getManager();
        $listuser = $em
          ->getRepository('KLUserBundle:User')
          ->findAllOrderedByName()
        ;

        return $this->render('KLUserBundle:User:liste.html.twig', array(
            'listuser' => $listuser
        ));
    }

    public function avatarAction(Request $request)
    {
      $em = $this->getDoctrine()->getManager();
      $user= $this->getUser();

      $form = $this->get('form.factory')->create(AvatarType::class, $user);

      // Si la requête est en POST
      if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
      {
          $user->myUpload();

          $em = $this->getDoctrine()->getManager();
          $em->persist($user);
          $em->flush();

          return $this->redirectToRoute('fos_user_profile_show');
      }

      return $this->render('KLUserBundle:User:avatar.html.twig', array(
        'form'   => $form->createView(),
      ));
    }

     public function deleteProfileAction(Request $request, $id)
     {
       $em = $this->getDoctrine()->getManager();

       $user= $em->getRepository('KLUserBundle:User')->find($id);

       if (null === $user) {
         throw new NotFoundHttpException("L utilisateur d'id ".$id." n'existe pas.");
       }

       $form = $this->get('form.factory')->create();

       if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
         $em->remove($user);
         $em->flush();

         $request->getSession()->getFlashBag()->add('info', "Cet utilisateur a bien été supprimée.");

         return $this->redirectToRoute('kl_restauration_homepage');
       }

       return $this->render('KLUserBundle:User:deleteProfile.html.twig', array(
         'user' => $user,
         'form'   => $form->createView(),
       ));
     }

    public function editUserAction(Request $request,$id_user)
    {
        $editUser = $this->getDoctrine()->getRepository('KLUserBundle:User')->find($id_user);

        $formEditUser = $this->get('form.factory')->create(User2Type::class, $editUser);

        $formEditUser->handleRequest($request);
        if ($formEditUser->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($editUser);
            $em->flush();

            return $this->redirectToRoute('kl_restauration_gerant_homepage');
        }

        return $this->render(
            'KLUserBundle:User:edit.html.twig',
            array(
                'editUserForm' => $formEditUser->createView()
           )
        );
    }

    public function deleteUserAction(Request $request, $id)
    {
      $em = $this->getDoctrine()->getManager();

      $user= $em->getRepository('KLUserBundle:User')->find($id);

      if (null === $user) {
        throw new NotFoundHttpException("L utilisateur d'id ".$id." n'existe pas.");
      }

      // On crée un formulaire vide, qui ne contiendra que le champ CSRF
      // Cela permet de protéger la suppression d'annonce contre cette faille
      $form = $this->get('form.factory')->create();

      if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        $em->remove($user);
        $em->flush();

        $request->getSession()->getFlashBag()->add('info', "Cet utilisateur a bien été supprimée.");

        return $this->redirectToRoute('kl_restauration_gerant_homepage');
      }

      return $this->render('KLUserBundle:User:delete.html.twig', array(
        'user' => $user,
        'form'   => $form->createView(),
      ));
    }

 }
