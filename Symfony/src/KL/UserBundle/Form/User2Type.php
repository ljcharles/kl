<?php

namespace KL\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class User2Type extends AbstractType
{
  /**
  * {@inheritdoc}
  */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {

    $permissions = array(
      'ROLE_USER'        => 'Aucun droit',
      'ROLE_CUISINIER'     => 'Cuisinier',
      'ROLE_LIVREUR'     => 'Livreur',
      'ROLE_ADMIN' => 'Administrateur'
    );

    $builder->add('nom')->add('prenom')
    ->add(
      'roles',
      ChoiceType::class,
      array(
        'label'   => 'Rôle à attribuer',
        'choices' => $permissions,
        'multiple'=>true,
        'attr'    => array(
          'class' => "select2"
        )
      )
      )
      ->add('save',      SubmitType::class);
    }/**
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
