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

    //ManyToOne
    private $user;

    /**
     * @var integer
     *
     * @ORM\Column(name="Etat", type="integer")
     */
    private $etat;

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
}
