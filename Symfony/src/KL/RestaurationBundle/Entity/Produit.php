<?php
use Doctrine\ORM\Mapping as ORM;

/**
* ProduitController
*
*@ORM\Table(name=kl_produit")
*@ORM\Entity(repositoryClass="KL\RestaurationBundle\Repository\ProduitRepository")
*/


class Produit
{
  /**
  *@var int
  *
  *@ORM\Column(name="id",type="integer")
  *@ORM\Id
  *@ORM\GeneratedValue(strategy=AUTO)
  */
  private $id;

  /**
  *@var string
  *
  *@ORM\Column(name="nom",type="string",lenght=255)
  */
  private $nom;

  /**
  *@var float
  *
  *@ORM\Column(name="prix",type="decimal", scale=2)
  */
  private $prix;

  /**
  *@var string
  *
  *@ORM\Column(name="description",type="text")
  */
  private $description;

  /**
  *@var int
  *
  *@ORM\Column(name="note",type="integer")
  */
  private $note;

  /**
  *@var FileUpload
  *
  *@ORM\Column(name="image",type="string")
  */
  private $image;

  /**
  *@ORM\OneToMany(targetEntity="KL\RestaurationBundle\Entity\Ingredient",mappedBy="produit",cascade={"all"})
  */
  private $ingredients;

  /**
  *@ORM\ManytoOne(targetEntity="KL\RestaurationBundle\Entity\GammeProduit",inverseBy="produits")
  *@ORM\JoinColumn()
  */
  private $gammeProduit;

  public function __construct()
  {
    $this->ingredients= new ArrayCollection();
  }
}

?>
