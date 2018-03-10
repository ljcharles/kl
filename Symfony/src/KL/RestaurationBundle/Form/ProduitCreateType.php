<?php

namespace KL\RestaurationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProduitCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder->remove('image')
      ->remove('nom')
      ->remove('prix')
      ->remove('description')
      ->remove('gammeProduit')
      ->remove('note')
      ;
    }

    public function getParent()
    {
      return ProduitType::class;
    }
}
