<?php

namespace KL\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
//use Symfony\Component\Security\Core\User\UserInterface;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="kl_user")
 * @ORM\Entity(repositoryClass="KL\UserBundle\Repository\UserRepository")
 */

class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
    * @ORM\OneToMany(targetEntity="KL\RestaurationBundle\Entity\Commande", mappedBy="user", cascade={"all"})
    */
    private $commandes;

    /**
    *@var FileUpload
    *
    *@ORM\Column(name="avatar",type="string", nullable=true)
    */
    private $avatar;

    /**
    * @ORM\Column(type="boolean", nullable=true)
    **/
    protected $isLivreur;

    /**
    * @ORM\Column(type="boolean", nullable=true)
    **/
    protected $isCuisinier;

    /**
    * @ORM\Column(type="boolean", nullable=true)
    **/
    protected $isGerant;

    /**
    * @ORM\Column(type="boolean", nullable=true)
    **/
    protected $isUtilisateur;


    public function __construct()
    {
        parent::__construct();
    }

    public function setEmail($email)
    {
        $email = is_null($email) ? '' : $email;
        parent::setEmail($email);
        parent::setUsername($email);
        parent::setRoles(['ROLE_USER']);
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return User
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
     * Set prenom
     *
     * @param string $prenom
     *
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }


    /**
     * Add commande
     *
     * @param \KL\RestaurationBundle\Entity\Commande $commande
     *
     * @return User
     */
    public function addCommande(\KL\RestaurationBundle\Entity\Commande $commande)
    {
        $this->commandes[] = $commande;

        return $this;
    }

    /**
     * Remove commande
     *
     * @param \KL\RestaurationBundle\Entity\Commande $commande
     */
    public function removeCommande(\KL\RestaurationBundle\Entity\Commande $commande)
    {
        $this->commandes->removeElement($commande);
    }

    /**
     * Get commandes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommandes()
    {
        return $this->commandes;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return User
     */
    public function setAvatar($avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    public function myUpload()
    {
      if (null === $this->avatar) return;

      $name = $this->avatar->getClientOriginalName();
      $this->avatar->move($this->getUploadRootDir(), $name);
      $url = '/'.$this->getUploadDir().'/'.$name;
      $this->setAvatar($url);
    }

    public function getUploadDir()
    {
      return 'bundles/klrestauration/img/profile';
    }

    protected function getUploadRootDir()
    {
      return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    /**
     * Set isLivreur
     *
     * @param boolean $isLivreur
     *
     * @return User
     */
    public function setIsLivreur($isLivreur = null)
    {
        $this->isLivreur = $isLivreur;

        if($isLivreur){
          $this->addRole('ROLE_LIVREUR');
        }else{
          $this->removeRole('ROLE_LIVREUR');
        }

        return $this;
    }

    /**
     * Get isLivreur
     *
     * @return boolean
     */
    public function getIsLivreur()
    {
        return $this->isLivreur;
    }

    /**
     * Set isCuisinier
     *
     * @param boolean $isCuisinier
     *
     * @return User
     */
    public function setIsCuisinier($isCuisinier = null)
    {
        $this->isCuisinier = $isCuisinier;

        if($isCuisinier){
          $this->addRole('ROLE_CUISINIER');
        }else{
          $this->removeRole('ROLE_CUISINIER');
        }

        return $this;
    }

    /**
     * Get isCuisinier
     *
     * @return boolean
     */
    public function getIsCuisinier()
    {
        return $this->isCuisinier;
    }

    /**
     * Set isGerant
     *
     * @param boolean $isGerant
     *
     * @return User
     */
    public function setIsGerant($isGerant = null)
    {
        $this->isGerant = $isGerant;

        if($isGerant){
          $this->addRole('ROLE_ADMIN');
        }else{
          $this->removeRole('ROLE_ADMIN');
        }

        return $this;
    }

    /**
     * Get isGerant
     *
     * @return boolean
     */
    public function getIsGerant()
    {
        return $this->isGerant;
    }

    /**
     * Set isUtilisateur
     *
     * @param boolean $isUtilisateur
     *
     * @return User
     */
    public function setIsUtilisateur($isUtilisateur = null)
    {
        $this->isUtilisateur = $isUtilisateur;
        if($isUtilisateur){
          $this->addRole('ROLE_USER');
        }else{
          $this->removeRole('ROLE_USER');
        }

        return $this;
    }

    /**
     * Get isUtilisateur
     *
     * @return boolean
     */
    public function getIsUtilisateur()
    {
        return $this->isUtilisateur;
    }
}
