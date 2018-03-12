<?php

namespace KL\RestaurationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * AdressLivraison
 *
 * @ORM\Table(name="kl_adressLivraison")
 * @ORM\Entity(repositoryClass="KL\RestaurationBundle\Repository\AdressLivraisonRepository")
 */
class AdressLivraison
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
    * @ORM\Column(type="string")
    */
    private $rue;

    /**
    * @ORM\Column(type="string")
    */
    private $ville;

    /**
    * @ORM\Column(type="string")
    */
    private $pays;

    /**
    * @ORM\Column(type="string")
    */
    private $codePostal;

    /**
    * @ORM\Column(type="decimal", precision=8, scale=6, nullable=true)
    */
    private $latitude;

    /**
    * @ORM\Column(type="decimal", precision=8, scale=6, nullable=true)
    */
    private $longitude;

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
     * Set rue
     *
     * @param string $rue
     *
     * @return AdressLivraison
     */
    public function setRue($rue)
    {
        $this->rue = $rue;

        return $this;
    }

    /**
     * Get rue
     *
     * @return string
     */
    public function getRue()
    {
        return $this->rue;
    }

    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return AdressLivraison
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set pays
     *
     * @param string $pays
     *
     * @return AdressLivraison
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set codePostal
     *
     * @param string $codePostal
     *
     * @return AdressLivraison
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return string
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return AdressLivraison
     */
    public function setLatitude($latitude = null)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return AdressLivraison
     */
    public function setLongitude($longitude = null)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
}
