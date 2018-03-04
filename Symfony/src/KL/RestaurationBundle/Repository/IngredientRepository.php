<?php

namespace KL\RestaurationBundle\Repository;

/**
*IngredientRepository
*/

class IngredientRepository extends \Doctrine\ORM\EntityRepository
{
  public function findAllOrderedByName()
  {
    return $this->getEntityManager()
        ->createQuery(
          'SELECT i FROM KLRestaurationBundle:Ingredient i ORDER BY i.nom ASC'
          )
        ->getResult();
  }

  // public function find($produit_id)
  // {
  //   return $this->getEntityManager()
  //       ->createQuery(
  //         'SELECT i FROM KLRestaurationBundle:Ingredient i WHERE i.produits = :pattern ORDER BY i.nom ASC'
  //         )
  //       ->setParameter('pattern', $produit_id)
  //       ->getResult();
  // }
} ?>
