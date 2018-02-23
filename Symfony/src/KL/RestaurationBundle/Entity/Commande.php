<?php

namespace KL\RestaurationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Commande
 *
 * @ORM\Table(name="kl_commande")
 * @ORM\Entity(repositoryClass="KL\RestaurationBundle\Repository\CommandeRepository")
 */
class Commande
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date", type="datetime")
     */
    private $date;

    /**
    * @ORM\OneToMany(targetEntity="KL\RestaurationBundle\Entity\Produit", mappedBy="commande", cascade={"all"})
    */
    private $produits;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->produits = new \Doctrine\Common\Collections\ArrayCollection();
        $this->date = new \Datetime();
    }

    /**
     * Get prixTotal
     *
     * @return int
     */
    public function getPrixTotal()
    {

      $prix = 0;

      foreach($this->getProduits() as $produit) {

        $prix += $produit->getPrix();

      }

      return $prix;

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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Commande
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Add produit
     *
     * @param \KL\RestaurationBundle\Entity\Produit $produit
     *
     * @return Commande
     */
    public function addProduit(\KL\RestaurationBundle\Entity\Produit $produit)
    {
        $this->produits[] = $produit;

        return $this;
    }

    /**
     * Remove produit
     *
     * @param \KL\RestaurationBundle\Entity\Produit $produit
     */
    public function removeProduit(\KL\RestaurationBundle\Entity\Produit $produit)
    {
        $this->produits->removeElement($produit);
    }

    /**
     * Get produits
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProduits()
    {
        return $this->produits;
    }
}
