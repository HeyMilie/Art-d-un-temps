<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir une adresse email'
                    ])
                    ],
                    'required' => true,
                    'attr' => [
                        'class' => 'form-control'
                    ]
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Visiteur' => 'ROLE_USER',
                    'Artiste' => 'ROLE_ARTISTE',
                    'Administrateur' => 'ROLE_ADMIN'
                ],
                'expanded' => true,
                'multiple' => true,
                'label' => 'Rôles'
            ])
            
            ->add('pseudo')
            ->add('nom')
            ->add('prenom', TextType::class, [
                'label' => 'Prénom'
        ])
            ->add('adresse')
            ->add('cp', TextType::class, [
                'label' => 'Code Postal'
            ])
            ->add('ville')
            
            ->add("photo", FileType::class, [
                "mapped" => false,
                "attr" => ["label_attr" => "Parcourir", "lang" => "fr"]
                
            ])
            ->add('Enregistrer', SubmitType::class, [
                "attr" => 
                ["class" => "btn"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
