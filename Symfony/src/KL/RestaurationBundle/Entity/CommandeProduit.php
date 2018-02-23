<?php

namespace KL\RestaurationBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="kl_commande_produit")
 */
class CommandeProduit
{
  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(name="quantity", type="integer")
   */
  private $quantity;

  /**
   * @ORM\ManyToOne(targetEntity="KL\RestaurationBundle\Entity\Commande")
   * @ORM\JoinColumn(nullable=false)
   */
  private $commandes;

  /**
   * @ORM\ManyToOne(targetEntity="KL\RestaurationBundle\Entity\Produit")
   * @ORM\JoinColumn(nullable=false)
   */
  private $produits;


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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return CommandeProduit
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set commandes
     *
     * @param \KL\RestaurationBundle\Entity\Commande $commandes
     *
     * @return CommandeProduit
     */
    public function setCommandes(\KL\RestaurationBundle\Entity\Commande $commandes)
    {
        $this->commandes = $commandes;

        return $this;
    }

    /**
     * Get commandes
     *
     * @return \KL\RestaurationBundle\Entity\Commande
     */
    public function getCommandes()
    {
        return $this->commandes;
    }

    /**
     * Set produits
     *
     * @param \KL\RestaurationBundle\Entity\Produit $produits
     *
     * @return CommandeProduit
     */
    public function setProduits(\KL\RestaurationBundle\Entity\Produit $produits)
    {
        $this->produits = $produits;

        return $this;
    }

    /**
     * Get produits
     *
     * @return \KL\RestaurationBundle\Entity\Produit
     */
    public function getProduits()
    {
        return $this->produits;
    }
}
