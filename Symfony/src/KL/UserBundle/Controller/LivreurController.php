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

class LivreurController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder();
        $commandes = $qb->select('t')
        ->from('KLRestaurationBundle:Commande','t')
        ->where($qb->expr()->isNotNull('t.adressLivraison'))
        ->andWhere("t.etat = 2 OR t.etat = 3")
        ->getQuery()
        ->getResult();

        $list = [];
        foreach ($commandes as $commande) {
          array_push($list, $commande->getAdressLivraison());
        }

        $adressLivraison = $em
          ->getRepository('KLRestaurationBundle:AdressLivraison')
          ->findBy(['id' => $list])
        ;

        $filename = 'bundles/klrestauration/XML/point.xml';

        if (file_exists($filename)) unlink($filename);

        $xml = '<?xml version="1.0" encoding="utf-8" standalone="yes" ?>';
        $xml .= '<markers>';
                  foreach ($adressLivraison as $point) {
                    $xml .= '<marker id="'
                    .$point->getId().'" lng="'
                    .$point->getLongitude().'" lat="'
                    .$point->getLatitude().'" name="'
                    .$point->getRue().'" address="'
                    .$point->getVille().'"/>';
                  }
        $xml .= '</markers>';

        file_put_contents("$filename",$xml);

        return $this->render('KLUserBundle:Livreur:index.html.twig', array(
            'commandes' => $commandes,
            'adressLiv' => $adressLivraison
        ));
    }

    public function enLivraisonAction($id,$id_user,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $commande = $em->getRepository('KLRestaurationBundle:Commande')->find($id);
        $user = $em->getRepository('KLUserBundle:User')->find($id_user);

        if ($commande->getInfoLivreur() == null) {
          $commande->setEtat(3);
          $commande->setInfoLivreur($user->getNom().' '.$user->getPrenom());
          $em->persist($commande);
          $em->flush();
        } else {
          # commande déjà pris en charge
          $request->getSession()->getFlashBag()
          ->add('danger', 'Commande déjà pris en charge par '.$commande->getInfoLivreur());
        }

        if (!isset($commande)) return $this->redirectToRoute('kl_core_error');

        return $this->redirectToRoute('kl_restauration_livreur_homepage');
    }

    public function livrerAction($id,Request $request)
    {
      $em = $this->getDoctrine()->getManager();
      $commande = $em->getRepository('KLRestaurationBundle:Commande')->find($id);
      $user = $this->getUser();

      if ($commande->getInfoLivreur() === ($user->getNom().' '.$user->getPrenom())) {
        $commande->setEtat(4);
        $em->persist($commande);
        $em->flush();
      } else {
        # commande déjà pris en charge
        $request->getSession()->getFlashBag()
        ->add('danger', 'Commande déjà pris en charge par '.$commande->getInfoLivreur());
      }

      if (!isset($commande)) return $this->redirectToRoute('kl_core_error');

      return $this->redirectToRoute('kl_restauration_livreur_homepage');
    }
 }
