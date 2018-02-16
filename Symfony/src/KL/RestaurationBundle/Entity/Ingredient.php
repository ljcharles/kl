<?php
namespace KL\RestaurationBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
* IngredientController
*
*@ORM\Table(name="kl_ingredient")
*@ORM\Entity(repositoryClass="KL\RestaurationBundle\Repository\IngredientRepository")
*/


class Ingredient
{
  /**
  *@var int
  *
  *@ORM\Column(name="id",type="integer")
  *@ORM\Id
  *@ORM\GeneratedValue(strategy="AUTO")
  */
  protected $id;

  /**
  *@var string
  *
  *@ORM\Column(name="nom",type="string",length=255)
  */
  protected $nom;

  /**
  *@var integer
  *
  *@ORM\Column(name="quantite",type="integer")
  */
  protected $quantite;

  /**
  *@ORM\ManyToOne(targetEntity="KL\RestaurationBundle\Entity\Produit",inversedBy="ingredients")
  *@ORM\JoinColumn()
  */
  private $produit;

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
     * @return Ingredient
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
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return Ingredient
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return integer
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set produit
     *
     * @param \KL\RestaurationBundle\Entity\Produit $produit
     *
     * @return Ingredient
     */
    public function setProduit(\KL\RestaurationBundle\Entity\Produit $produit = null)
    {
        $this->produit = $produit;

        return $this;
    }

    /**
     * Get produit
     *
     * @return \KL\RestaurationBundle\Entity\Produit
     */
    public function getProduit()
    {
        return $this->produit;
    }
}
