<?php

namespace KL\RestaurationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CommandeEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder
      ->remove('date')
      ->remove('commandeProduits')
      ->remove('user');
    }

    public function getParent()
    {
      return CommandeType::class;
    }
}
