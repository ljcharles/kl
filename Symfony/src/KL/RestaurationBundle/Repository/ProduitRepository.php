<?php

namespace KL\RestaurationBundle\Repository;

/**
*ProduitRepository
*/

class ProduitRepository extends \Doctrine\ORM\EntityRepository
{
  public function findAllOrderedByName()
  {
    return $this->getEntityManager()
        ->createQuery(
          'SELECT p FROM KLRestaurationBundleBundle:Produit p ORDER BY p.name ASC'
          )
        ->getResult();
  }
} ?>
