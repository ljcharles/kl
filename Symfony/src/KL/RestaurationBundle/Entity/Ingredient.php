<?php
use Doctrine\ORM\Mapping as ORM;

/**
* IngredientController
*
*@ORM\Table(name=kl_ingredient")
*@ORM\Entity(repositoryClass="KL\RestaurationBundle\Repository\IngredientRepository")
*/


class Ingredient
{
  /**
  *@var int
  *
  *@ORM\Column(name="id",type="integer")
  *@ORM\Id
  *@ORM\GeneratedValue(strategy=AUTO)
  */
  protected $id;

  /**
  *@var string
  *
  *@ORM\Column(name="nom",type="string",lenght=255)
  */
  protected $nom;

  /**
  *@var integer
  *
  *@ORM\Column(name="quantite",type="integer")
  */
  protected $quantite;

  /**
  *@ORM\ManytoOne(targetEntity="KL\RestaurationBundle\Entity\Produit",inverseBy="ingredients")
  *@ORM\JoinColumn()
  */
  private $produit;
}

?>
