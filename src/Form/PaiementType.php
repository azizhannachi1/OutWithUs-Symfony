<?php

namespace App\Form;

use App\Entity\Paiement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PaiementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('carte',TextType::class)
            ->add('year',TextType::class)
            ->add('month',TextType::class)
            ->add('cvc',TextType::class)
            ->add('prix',TextType::class)
            ->add('email',TextType::class) 
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Paiement::class,
        ]);
    }
}
