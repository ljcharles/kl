<?php

namespace KL\RestaurationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande")
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
    *
    *@ORM\Column(name:"date", type="date")
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
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
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

    /**
     * Get date
     *
     * @return date
     */
    public function getDate()
    {
        return $this->id;
    }
}
?>
