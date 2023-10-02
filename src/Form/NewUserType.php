<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class NewUserType extends AbstractType
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
        ->add('plainPassword', PasswordType::class, [
            // instead of being set onto the object directly,
            // this is read and encoded in the controller
            'mapped' => false,
            'label' => 'Mot de passe',
            'attr' => ['autocomplete' => 'new-password'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuiller entre un mot de passe',
                    ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Votre mot de passe doit contenir au moins 6 caractères',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                    ]),
                ],
            ])
            ->add('pseudo')
            ->add('nom')
            ->add('prenom', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('adresse', TextType::class, [
                'required' => false
            ])
            ->add('cp', TextType::class, [
                'label' => 'Code Postal',
                'required' => false
            ])
            ->add('ville', TextType::class, [
                'required' => false
            ])
            
            ->add("photo", FileType::class, [
                "mapped" => false,
                "attr" => ["label_attr" => "Parcourir", "lang" => "fr", "class" => "form-control", "placeholder" => "Aucun fichier sélectionné"]
                
            ])
            ->add("agreeTerms", CheckboxType::class, [
                "label" => "J'accepte les Conditions Générales d'Utilisation",
                "mapped" => false,
                "constraints" => [
                    new IsTrue([
                        "message" => "Vous devez accepter les C.G.U.",
                    ]),
                ],
                "attr" => ["class" => "form-check-input"]
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
