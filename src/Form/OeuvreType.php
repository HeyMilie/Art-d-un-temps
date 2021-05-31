<?php

namespace App\Form;

use App\Entity\Membre;
use App\Entity\Oeuvre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;



class OeuvreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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

            ->add('annee', DateType::class, [
                "widget" => "single_text",
                "label" => "Réalisée en",
                "required" => false
            ])

            ->add('description')

            ->add('dimension')

            ->add('prix')

            ->add('photo', FileType::class, [
                "mapped" => false,
                "attr" => ["label_attr" => "Parcourir", "lang" => "fr"]
                
            ])
            
            ->add('stock')
            
            ->add('membre', EntityType::class, [
                "class" => Membre::class,
                "choice_label" => "pseudo",
                "placeholder" => "Choisissez parmi les membres..."
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
