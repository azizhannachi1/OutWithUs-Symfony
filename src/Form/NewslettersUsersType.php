<?php

namespace App\Form;

use App\Entity\Newsletters\Usersn;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Newsletters\Categoriesn;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\IsTrue;

class NewslettersUsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            ->add('categoriesn', EntityType::class, [
                'class' => Categoriesn::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true, 
               
            ])
            ->add('is_rgpd', CheckboxType::class, [
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter la collecte de vos données personnelles'
                    ])
                ],
                'label' => 'J\'accepte la collecte de mes données personnelles'
            ])
            ->add('envoyer', SubmitType::class)
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Usersn::class,
        ]);
    }
}
