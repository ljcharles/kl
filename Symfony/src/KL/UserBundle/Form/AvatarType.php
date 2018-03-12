<?php

namespace KL\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class AvatarType extends AbstractType
{
  /**
  * {@inheritdoc}
  */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {

    $builder->add('avatar', FileType::class, array(
          'label' => 'Mettre à jour ma photo de profil',
          'data_class' => null
      ))
    ->add('save', SubmitType::class);

  }

    /**
    * {@inheritdoc}
    */
    public function configureOptions(OptionsResolver $resolver)
    {
      $resolver->setDefaults(array(
        'data_class' => 'KL\UserBundle\Entity\User'
      ));
    }

    /**
    * {@inheritdoc}
    */
    public function getBlockPrefix()
    {
      return 'kl_userbundle_user';
    }

  }
