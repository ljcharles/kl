<?php

namespace KL\RestaurationBundle\Repository;

/**
*CommandeProduitRepository
*/

class CommandeProduit extends \Doctrine\ORM\EntityRepository
{
    public function findAllOrderedByName()
   {
     return $this->getEntityManager()
         ->createQuery(
           'SELECT i FROM KLRestaurationBundle:CommandeProduit i '
           )
         ->getResult();
   }

   public function findAllOrderedByCommand()
   {
     return $this->getEntityManager()
         ->createQuery(
           'SELECT i FROM KLRestaurationBundle:CommandeProduit i WHERE i.statut=0 ORDER BY i.commande'
           )
         ->getResult();
   }

  //ranger par ordre croisant car se sera l'id_command

   public function findAllOrderedByProduit()
   {
     return $this->getEntityManager()
         ->createQuery(
           'SELECT i FROM KLRestaurationBundle:CommandeProduit i WHERE i.statut=0 ORDER BY i.produit'
           )
         ->getResult();
   }

   public function findCommande($id_commande)
   {
     return $this->getEntityManager()
         ->createQuery(
           'SELECT i FROM KLRestaurationBundle:CommandeProduit i WHERE i.commande=:id_commande ORDER BY i.statut'
           )
         ->setParameters('id_commande', $id_commande)
         ->getResult();
   }

   public function findProduit($id_produit)
   {
     return $this->getEntityManager()
         ->createQuery(
           'SELECT i FROM KLRestaurationBundle:CommandeProduit i WHERE i.produit=:id_produit ORDER BY i.statut'
           )
         ->setParameters('id_produit', $id_produit)
         ->getResult();
   }
} ?>
