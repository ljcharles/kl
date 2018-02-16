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
          'SELECT p FROM KLRestaurationBundleBundle:Ingredient p ORDER BY p.name ASC'
          )
        ->getResult();
  }
} ?>