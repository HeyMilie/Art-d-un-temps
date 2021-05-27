<?php

namespace App\Form;

use App\Entity\Oeuvre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OeuvreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reference', TextType::class)
            ->add('categorie', ChoiceType::class, [
               "choices" => [
                   "Peinture" => "Peinture",
                   "Scupture" => "Sculpture",
                   "Céramique" => "Céramique",
                   "Gravure" => "Gravure",
                   "Illustration" => "Illustration",
                   "Verre" => "Verre"   
               ],
               "multiple" => false,
                "expanded" => true  
            ])
            ->add('nom_oeuvre', TextType::class)
            ->add('annee', TextType::class, [
                "mapped" => false,
                "label" => "Réalisée en"
            ])
            ->add('dimension', TextType::class, [
                "mapped" => false,
                "label" => "Dimmension en cm (largeur x hauteur)"
            ])
            ->add('prix', NumberType::class, [
                "mapped" => false,
                "constraints" => [
                    new Length([
                        "max" => 7,
                        "maxMessage" => "Le prix ne peut pas dépasser 7 chiffres"
                    ]),
                    new NotBlank([
                        "message" => "Le prix ne peut pas être vide"
                    ])
                ]
            ])
            //->add('photo')
            ->add('stock')
            ->add('membre', TextType::class, [
                "label" => "Nom de l'artiste",
                "mapped" => false,
            ])

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
            'data_class' => Oeuvre::class,
        ]);
    }
}
