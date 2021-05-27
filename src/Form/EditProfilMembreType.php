<?php

namespace App\Form;

use App\Entity\Membre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EditProfilMembreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $membre = $options["data"];
        $builder
            ->add('pseudo')
            ->add('password', TextType::class, [
                "label" => "Mot de passe",
                "mapped" => false,
                "constraints" => [
                    new Regex([
                        "pattern" => "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{6,10})$/",
                        "message" => "Le mot de passe doit contenir au moins 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial parmi -+!*$@%_ et doit faire entre 6 et 10 caractères."
                    ])
                ],
                "help" => "Le mot de passe doit contenir au moins 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial parmi -+!*$@%_ et doit faire entre 6 et 10 caractères.", 
                "required" => $membre->getId() ? false : true
            ])
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('ville')
            ->add('cp')
            ->add('adresse')
            
            //->add('photo')

            ->add('enregistrer', SubmitType::class,[
                "attr" => 
                ["class" => "btn"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Membre::class,
        ]);
    }
}
