<?php

namespace App\Form;

use App\Entity\Publication;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Validator\Constraints\File;

class PublicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('titre')
        ->add('text', CKEditorType::class)
        ->add('userid', HiddenType::class)
        ->add('image', FileType::class,[
            'label' => 'image',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new File([
                    'maxSize' => '2Mi',
                    'mimeTypesMessage' => 'Please upload a valid image file',
                ])
            ],
        ])
        ->add('useremail', HiddenType::class)
      /* ->add('date',DateType::class, array(
            'widget' => 'single_text',            
            'format' => 'yyyy-MM-dd',
            'data' => new \DateTime(),
            'attr' => [
                'disabled' => true,
                'hidden' => true

            ]
            
        ))*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Publication::class,
        ]);
    }
}
