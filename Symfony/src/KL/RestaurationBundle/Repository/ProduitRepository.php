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
          'SELECT p FROM KLRestaurationBundle:Produit p ORDER BY p.name ASC'
          )
        ->getResult();
  }

  public function findArray($array)
  {
    $qb = $this->createQueryBuilder('u')
              ->select('u')
              ->where('u.id in (:array)')
              ->setParameter('array',$array);
    return $qb->getQuery()->getResult();
  }

  public function getLikeQuery($pattern)
  {
     $s = explode(" ",addslashes($pattern));
     $sql = "SELECT p FROM KLRestaurationBundle:Produit p";

     $i = 0;
     foreach ($s as $mot) {
       if (strlen($mot) > 3) {
         if ($i == 0) {
           $sql .= " WHERE ";
         } else {
           $sql .= " OR ";
         }

         $sql .= " CONCAT(p.nom, p.description) LIKE '%$mot%'";
         $i++;
       }
     }
     return $this->getEntityManager()
       ->createQuery($sql)
       ->getResult()
     ;
  }
} ?>
