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
            ->add('carte',TextType::class, [
                'attr' => array('readonly' => true)
            ])
            ->add('year',TextType::class, [
                'attr' => array('readonly' => true)
            ])
            ->add('month',TextType::class, [
                'attr' => array('readonly' => true)
            ])
            ->add('cvc',TextType::class, [
                'attr' => array('readonly' => true)
            ])
            ->add('prix',TextType::class, [
                'attr' => array('readonly' => true)
            ])
            ->add('email',TextType::class, [
                'attr' => array('readonly' => true)
            ]) 
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Paiement::class,
        ]);
    }
}
