<?php

namespace App\Form;

use App\Entity\Membre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('roles', ChoiceType::class, [
                "choices" => [
                    "Artiste" => "ROLE_ARTISTE",
                    "Visiteur" => "ROLE_MEMBRE",
                ],
                "label" => "Je m'inscris en tant que :",
                "mapped" => false,
                "multiple" => false,
                "expanded" => true
                ])

            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'required' => true,
            ])

            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre  mot de passe doit contenir au moins 6 caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                
            ])
            ->add("prenom", TextType::class, [
                "label" => "Prénom",
                "required" => false
            ])

            ->add("nom", TextType::class, [
                "required" => false
            ])

            ->add("email", TextType::class, [
                "required" => false
            ])

            // ->add("ville", TextType::class, [
            //     "required" => false,
            //     'constraints' => [
            //         new IsTrue([
            //             'message' => 'Vous devez saisir une ville',
            //         ]),
            //     ],
            // ])

            // ->add('cp', NumberType::class, [
            //     "required" => false,
            //     'constraints' => [
            //         new IsTrue([
            //             'message' => 'Vous devez saisir un code postal',
            //         ]),
            //     ],
            // ])

            // ->add("adresse", TextType::class, [
            //     "required" => false,
            //     'constraints' => [
            //         new IsTrue([
            //             'message' => 'Vous devez saisir une adresse',
            //         ]),
            //     ],
            // ])

            // ->add('photo', FileType::class, [
            //     "mapped" => false,
            //     "attr" => ["label_attr" => "Parcourir", "lang" => "fr"],
            //     "constraints" => [
            //         new IsTrue([
            //             'message' => 'Vous devez choisir une photo',
            //         ]),
            //     ],
                
            // ])

            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les C.G.U.',
                    ]),
                ],
                'attr' => ["class" => "form-check-input"]
            ])

            ->add('enregistrer', SubmitType::class, ["attr" => ["class" => "btn btn-warning"]]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Membre::class,
        ]);
    }
}
