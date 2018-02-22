<?php

namespace KL\RestaurationBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use KL\RestaurationBundle\Entity\GammeProduit;

class LoadGammeProduit implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $names = array(
      'Hamburger',
      'Pizza',
      'Tarte',
      'Boisson'
    );

    foreach ($names as $name) {
      $category = new GammeProduit();
      $category->setNom($name);

      $manager->persist($category);
    }

    $manager->flush();
  }
}
