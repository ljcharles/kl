<?php
// src/kl/UserBundle/DataFixtures/ORM/LoadKLUser.php

namespace KL\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Collections\ArrayCollection;
use KL\UserBundle\Entity\User;

class LoadUser implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    /*// Les noms d'utilisateurs à créer
    $listNames = array('Alexandre', 'Marine', 'Anna');

    foreach ($listNames as $name) {
      // On crée l'utilisateur
      $user = new KLUser();

      // Le nom d'utilisateur et le mot de passe sont identiques pour l'instant
      $user->setUsername($name);
      $user->setPassword($name);

      // On ne se sert pas du sel pour l'instant
      $user->setSalt('');
      // On définit uniquement le role ROLE_USER qui est le role de base
      $user->setRoles(array('ROLE_USER'));

      // On le persiste
      $manager->persist($user);
    }

    // On déclenche l'enregistrement
    $manager->flush();*/
  }
}
