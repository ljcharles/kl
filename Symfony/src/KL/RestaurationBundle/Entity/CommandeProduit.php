<?php

namespace KL\RestaurationBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="KL\RestaurationBundle\Repository\CommandeProduitRepository")
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
   * @var integer
   *
   * @ORM\Column(name="Etat", type="integer")
   */
  private $etat;


  /**
   * @ORM\ManyToOne(targetEntity="KL\RestaurationBundle\Entity\Commande",inversedBy="commandeProduits")
   * @ORM\JoinColumn(nullable=false)
   */
  private $commande;

  /**
   * @ORM\ManyToOne(targetEntity="KL\RestaurationBundle\Entity\Produit",inversedBy="commandeProduits")
   * @ORM\JoinColumn(nullable=false)
   */
  private $produit;

  /**
   * @ORM\Column(name="cuisinier", type="integer", nullable=true)
   */
  private $cuisinier;


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
     * Set etat
     *
     * @param integer $etat
     *
     * @return CommandeProduit
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return integer
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set commande
     *
     * @param \KL\RestaurationBundle\Entity\Commande $commande
     *
     * @return CommandeProduit
     */
    public function setCommande(\KL\RestaurationBundle\Entity\Commande $commande)
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

    /**
     * Set produit
     *
     * @param \KL\RestaurationBundle\Entity\Produit $produit
     *
     * @return CommandeProduit
     */
    public function setProduit(\KL\RestaurationBundle\Entity\Produit $produit)
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

    /**
     * Set cuisinier
     *
     * @param integer $cuisinier
     *
     * @return CommandeProduit
     */
    public function setCuisinier($cuisinier = null)
    {
        $this->cuisinier = $cuisinier;

        return $this;
    }

    /**
     * Get cuisinier
     *
     * @return integer
     */
    public function getCuisinier()
    {
        return $this->cuisinier;
    }
}
