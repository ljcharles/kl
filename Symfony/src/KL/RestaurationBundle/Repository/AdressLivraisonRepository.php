<?php

namespace KL\RestaurationBundle\Repository;

/**
*AdressLivraisonRepository
*/

class AdressLivraisonRepository extends \Doctrine\ORM\EntityRepository
{
  public function findWithCommande($id,$commandes)
  {
    $parameters = [
      'id' => $id,
      'user' => $user,
    ];

    $dql =  'SELECT p
             FROM KLRestaurationBundle:Commande p
             WHERE p.id = :id
             AND p.user = :user';

    return $this->getEntityManager()
        ->createQuery($dql)
        ->setParameters($parameters)
        ->getResult();
  }

} ?>
