<?php

namespace KL\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use KL\RestaurationBundle\Entity\Commande;
use KL\RestaurationBundle\DQL\DQLDate;
use KL\UserBundle\Form\User2Type;
use KL\UserBundle\Form\AvatarType;
use KL\RestaurationBundle\Form\CommandeEditType;
use KL\RestaurationBundle\Form\CommandeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GerantController extends Controller
{
    public function indexlistecommandeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $listcommande = $em
          ->getRepository('KLRestaurationBundle:Commande')
          ->findAll()
        ;

        $q = $em->createQueryBuilder()
           ->select('DATE(e.date) AS day, COUNT(e.id) AS amount')
           ->from('KLRestaurationBundle:Commande', 'e')
           ->groupBy('day');
        $nb = $q->getQuery()->getResult();

        return $this->render('KLRestaurationBundle:Commande:liste.html.twig', array(
            'commandes' => $listcommande,
            'nbCommande' => $nb,
        ));
    }


     public function deleteCommandeAction(Request $request, $id)
     {
       $em = $this->getDoctrine()->getManager();

       $commande= $em->getRepository('KLRestaurationBundle:Commande')->find($id);

       if (null === $commande) {
         throw new NotFoundHttpException("La Commande d'id ".$id." n'existe pas.");
       }

       $form = $this->get('form.factory')->create();

       if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
         $em->remove($commande);
         $em->flush();

         $request->getSession()->getFlashBag()->add('info', "Cette commande a bien été supprimée.");

         return $this->redirectToRoute('kl_restauration_listecommande_homepage');
       }

       return $this->render('KLRestaurationBundle:Commande:delete.html.twig', array(
         'commande' => $commande,
         'form'   => $form->createView(),
       ));
     }

    public function editCommandeAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();

        $commande = $em->getRepository('KLRestaurationBundle:Commande')->find($id);

        $form = $this->createForm(CommandeEditType::class, $commande);

        $form->handleRequest($request);
        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();

            return $this->redirectToRoute('kl_restauration_listecommande_homepage');
        }

        return $this->render(
            'KLRestaurationBundle:Commande:edit.html.twig',
            array(
                'form' => $form->createView(),'commande'=>$commande
           )
        );
    }

    public function viewadminAction($id)
    {

        $commande = $this->getDoctrine()->getManager()
        ->getRepository('KLRestaurationBundle:Commande')
        ->find($id);

        return $this->render('KLRestaurationBundle:Commande:viewAdmin.html.twig', array(
            'commande' => $commande
        ));
    }

 }
