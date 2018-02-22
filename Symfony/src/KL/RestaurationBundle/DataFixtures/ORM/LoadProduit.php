<?php

namespace KL\RestaurationBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use KL\RestaurationBundle\Entity\Produit;
use KL\RestaurationBundle\Entity\GammeProduit;

class LoadProduit implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Liste des noms et prix de produit à ajouter
    $namesAndPrices = array(
      0 => array(
        'Hamburger de luxe',
        24.99,
        "Son pain moelleux, sa générosité… Qu’il soit classique, végétarien ou exotique,
        ce burger à la cote et on ne s’en lasse pas. Vous aussi vous l’adulez n’est-ce pas ?",
        4,
        'Hamburger',
        "https://images.unsplash.com/photo-1428660386617-8d277e7deaf2"
      ),
      1 => array(
        'Pizza italienne',
        34.99,
        "L'Italie vient à vous au travers de cette alléchante spécialité que nous vous proposons !
        Nos pizzas faites maison sont confectionnées dans le plus grand respect d'un art
        qui se veut traditionnel.",
        3,
        'Pizza',
        "https://images.unsplash.com/photo-1510693206972-df098062cb71"
      ),
      2 => array(
        'Tarte aux fruits',
        18.00,
        "Sa texture onctieuse et savoureuse, vous fera retomber en enfance,
        n'hésitez pas à le déguster avec son nectare de premier choix !
        Vous m'en donnerez des nouvelles.",
        4,
        'Tarte',
        "https://images.unsplash.com/photo-1476887334197-56adbf254e1a"
      ),
      3 => array(
        'Mojito',
        5.00,
        "Très parfumé, légèrement sucré et avec une pointe d'acidité, ce Cocktail élégant
        et cosmopolite a fait sa place parmi les grands classiques et c'est aujourd'hui
        le Cocktail le plus célèbre !",
        4,
        'Boisson',
        "https://images.unsplash.com/photo-1517361442478-09786261a17c"
      ),
    );

    foreach ($namesAndPrices as $nameAndPrix => $nameAndPrix_value) {

      $product = new Produit();

      foreach ($nameAndPrix_value as $key => $value) {
        $product->setNom($nameAndPrix_value[0]);
        $product->setPrix($nameAndPrix_value[1]);
        $product->setDescription($nameAndPrix_value[2]);
        $product->setNote($nameAndPrix_value[3]);

        $gammeProduit = $manager
          ->getRepository('KLRestaurationBundle:GammeProduit')
          ->findOneByNom($nameAndPrix_value[4])
        ;

        $product->setGammeProduit($gammeProduit);
        $product->setImage($nameAndPrix_value[5]);

      }

      $manager->persist($product);
    }

    $manager->flush();
  }
}
