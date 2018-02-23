<?php

namespace KL\RestaurationBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
* ProduitController
*
*@ORM\Table(name="kl_produit")
*@ORM\Entity(repositoryClass="KL\RestaurationBundle\Repository\ProduitRepository")
*/


class Produit
{
  /**
  *@var int
  *
  *@ORM\Column(name="id",type="integer")
  *@ORM\Id
  *@ORM\GeneratedValue(strategy="AUTO")
  */
  private $id;

  /**
  *@var string
  *
  *@ORM\Column(name="nom",type="string",length=255)
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
  *@ORM\ManyToMany(targetEntity="KL\RestaurationBundle\Entity\Ingredient",cascade={"persist"})
  */
  private $ingredients;

  /**
  *@ORM\ManyToOne(targetEntity="KL\RestaurationBundle\Entity\GammeProduit",inversedBy="produits",cascade={"all"})
  *@ORM\JoinColumn()
  */
  private $gammeProduit;

  /**
  *@ORM\ManyToOne(targetEntity="KL\RestaurationBundle\Entity\Commande",inversedBy="produits")
  *@ORM\JoinColumn()
  */
  private $commande;

  public function __construct()
  {
    $this->ingredients= new ArrayCollection();
  }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Produit
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prix
     *
     * @param string $prix
     *
     * @return Produit
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return string
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Produit
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set note
     *
     * @param integer $note
     *
     * @return Produit
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return integer
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Produit
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add ingredient
     *
     * @param \KL\RestaurationBundle\Entity\Ingredient $ingredient
     *
     * @return Produit
     */
    public function addIngredient(\KL\RestaurationBundle\Entity\Ingredient $ingredient)
    {
        $this->ingredients[] = $ingredient;

        return $this;
    }

    /**
     * Remove ingredient
     *
     * @param \KL\RestaurationBundle\Entity\Ingredient $ingredient
     */
    public function removeIngredient(\KL\RestaurationBundle\Entity\Ingredient $ingredient)
    {
        $this->ingredients->removeElement($ingredient);
    }

    /**
     * Get ingredients
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIngredients()
    {
        return $this->ingredients;
    }

    /**
     * Set gammeProduit
     *
     * @param \KL\RestaurationBundle\Entity\GammeProduit $gammeProduit
     *
     * @return Produit
     */
    public function setGammeProduit(\KL\RestaurationBundle\Entity\GammeProduit $gammeProduit = null)
    {
        $this->gammeProduit = $gammeProduit;

        return $this;
    }

    /**
     * Get gammeProduit
     *
     * @return \KL\RestaurationBundle\Entity\GammeProduit
     */
    public function getGammeProduit()
    {
        return $this->gammeProduit;
    }

    /**
    * Set commande
    *
    * @param \KL\RestaurationBundle\Entity\Commande $commande
    *
    * @return Produit
    */
    public function setCommande(\KL\RestaurationBundle\Entity\Commande $commande = null)
    {
       $this->commande = $commande;

       return $this;
    }

    /**
    * Get commande
    *
    * @return \KL\RestaurationBundle\Entity\Commande
    */
    public function getCommande()
    {
       return $this->commande;
    }

    public function myUpload()
    {
      if (null === $this->image) return;

      $name = $this->image->getClientOriginalName();
      $this->image->move($this->getUploadRootDir(), $name);
      $url = '/Restauration/Symfony/web/'.$this->getUploadDir().'/'.$name;
      $this->setImage($url);
    }

    public function getUploadDir()
    {
      return 'bundles/klrestauration/img/uploads';
    }

    protected function getUploadRootDir()
    {
      return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }
}
