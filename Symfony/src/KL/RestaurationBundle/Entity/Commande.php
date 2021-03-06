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
    * @ORM\OneToMany(targetEntity="KL\RestaurationBundle\Entity\CommandeProduit", mappedBy="commande", cascade={"all"})
    */
    private $commandeProduits;

    /**
    *@ORM\ManyToOne(targetEntity="KL\UserBundle\Entity\User",inversedBy="commandes")
    *@ORM\JoinColumn()
    */
    private $user;

    /**
     * @var integer
     *
     * @ORM\Column(name="Etat", type="integer")
     */
    private $etat;

    /**
   * @ORM\OneToOne(targetEntity="KL\RestaurationBundle\Entity\AdressLivraison", cascade={"persist"})
   * @ORM\JoinColumn(name="adressLivraison", nullable=true)
   */
    private $adressLivraison;

   /**
    * @ORM\Column(type="decimal", precision=10, scale=5, nullable=true)
    */
    private $amount;

    /**
    * @var integer
    * @ORM\Column(type="integer", nullable=true)
    */
     private $payer;

     /**
      * @var \DateTime
      *
      * @ORM\Column(name="datePaiement", type="datetime", nullable=true)
      */
     private $datePaiement;

     /**
     *@var string
     *
     *@ORM\Column(name="infoLivreur",type="string",length=255, nullable=true)
     */
     private $infoLivreur;


    /**
     * Constructor
     */
    public function __construct()
    {
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

      $commandeProduits = $this->getCommandeProduits();
      foreach ($commandeProduits as $commandeProduit ) {
        $produits = $commandeProduit->getProduit();
        $prix += $produits->getPrix();
      }
      return $prix;
    }

    public function getNbProduitTotal()
    {
      $nb = 0;
      $commandeProduits = $this->getCommandeProduits();
      foreach ($commandeProduits as $commandeProduit ) {
        $produits = $commandeProduit->getProduit();
        $nb += sizeof($produits);
      }
      return $nb;
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
     * Set etat
     *
     * @param integer $etat
     *
     * @return Commande
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
     * Add commandeProduit
     *
     * @param \KL\RestaurationBundle\Entity\CommandeProduit $commandeProduit
     *
     * @return Commande
     */
    public function addCommandeProduit(\KL\RestaurationBundle\Entity\CommandeProduit $commandeProduit)
    {
        $this->commandeProduits[] = $commandeProduit;

        return $this;
    }

    /**
     * Remove commandeProduit
     *
     * @param \KL\RestaurationBundle\Entity\CommandeProduit $commandeProduit
     */
    public function removeCommandeProduit(\KL\RestaurationBundle\Entity\CommandeProduit $commandeProduit)
    {
        $this->commandeProduits->removeElement($commandeProduit);
    }

    /**
     * Get commandeProduits
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommandeProduits()
    {
        return $this->commandeProduits;
    }

    /**
     * Set user
     *
     * @param \KL\UserBundle\Entity\User $user
     *
     * @return Commande
     */
    public function setUser(\KL\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \KL\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set adressLivraison
     *
     * @param \KL\RestaurationBundle\Entity\AdressLivraison $adressLivraison
     *
     * @return Commande
     */
    public function setAdressLivraison(\KL\RestaurationBundle\Entity\AdressLivraison $adressLivraison = null)
    {
        $this->adressLivraison = $adressLivraison;

        return $this;
    }

    /**
     * Get adressLivraison
     *
     * @return \KL\RestaurationBundle\Entity\AdressLivraison
     */
    public function getAdressLivraison()
    {
        return $this->adressLivraison;
    }

    /**
     * Set amount
     *
     * @param string $amount
     *
     * @return Commande
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set payer
     *
     * @param integer $payer
     *
     * @return Commande
     */
    public function setPayer($payer)
    {
        $this->payer = $payer;

        return $this;
    }

    /**
     * Get payer
     *
     * @return integer
     */
    public function getPayer()
    {
        return $this->payer;
    }

    /**
     * Set datePaiement
     *
     * @param \DateTime $datePaiement
     *
     * @return Commande
     */
    public function setDatePaiement($datePaiement)
    {
        $this->datePaiement = $datePaiement;

        return $this;
    }

    /**
     * Get datePaiement
     *
     * @return \DateTime
     */
    public function getDatePaiement()
    {
        return $this->datePaiement;
    }

    /**
     * Set infoLivreur
     *
     * @param string $infoLivreur
     *
     * @return Commande
     */
    public function setInfoLivreur($infoLivreur = null)
    {
        $this->infoLivreur = $infoLivreur;

        return $this;
    }

    /**
     * Get infoLivreur
     *
     * @return string
     */
    public function getInfoLivreur()
    {
        return $this->infoLivreur;
    }
}
