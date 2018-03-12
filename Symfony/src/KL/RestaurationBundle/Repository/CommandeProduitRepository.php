<?php

namespace KL\RestaurationBundle\Repository;

class CommandeProduitRepository extends \Doctrine\ORM\EntityRepository
{
  public function findAll()
  {
    return $this->getEntityManager()
        ->createQuery(
          'SELECT i FROM KLRestaurationBundle:CommandeProduit i WHERE i.etat=0 ORDER BY i.commande'
          )
        ->getResult();
  }

   public function findBylesCommande()
   {
     return $this->getEntityManager()
         ->createQuery(
           'SELECT i FROM KLRestaurationBundle:CommandeProduit i WHERE i.etat=0 ORDER BY i.commande'
           )
         ->getResult();
   }

  //ranger par ordre croisant car se sera l'id_command

   public function findBylesProduit()
   {
     return $this->getEntityManager()
         ->createQuery(
           'SELECT i FROM KLRestaurationBundle:CommandeProduit i WHERE i.etat=0 ORDER BY i.produit'
           )
         ->getResult();
   }

   public function findCommande($id_commande)
   {
     return $this->getEntityManager()
         ->createQuery(
           'SELECT i FROM KLRestaurationBundle:CommandeProduit i WHERE i.commande=:id_commande ORDER BY i.etat'
           )
         ->setParameters('id_commande', $id_commande)
         ->getResult();
   }

   public function findProduit($id_produit)
   {
     return $this->getEntityManager()
         ->createQuery(
           'SELECT i FROM KLRestaurationBundle:CommandeProduit i WHERE i.produit=:id_produit ORDER BY i.etat'
           )
         ->setParameters('id_produit', $id_produit)
         ->getResult();
   }

   public function findMonhistorique($id_cuisinier)
   {
     return $this->getEntityManager()
         ->createQuery(
           'SELECT i FROM KLRestaurationBundle:CommandeProduit i WHERE i.cuisinier=:id_cuisinier and i.etat=2 ORDER BY i.id'
           )
         ->setParameter('id_cuisinier', $id_cuisinier)
         ->getResult();
   }
} ?>
