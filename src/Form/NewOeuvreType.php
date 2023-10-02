<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\Oeuvres;
use App\Repository\UsersRepository;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class NewOeuvreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class)

            ->add('categorie', ChoiceType::class, [
                "choices" => [
                    "Peinture" => "Peinture",
                    "Scupture" => "Sculpture",
                    "Céramique" => "Céramique",
                    "Gravure" => "Gravure",
                    "Illustration" => "Illustration",
                    "Vitrail" => "Vitrail"
                ],
                "placeholder" => "Sélectionner une catégorie d'oeuvre...",
                "multiple" => false,
                "expanded" => false
            ])

            ->add('user', EntityType::class, [
                "class" => Users::class,
                "label" => "Artiste de l'oeuvre",
                "choice_label" => "pseudo",
                "query_builder" => function (UsersRepository $userRepo) {
                    return $userRepo->findByArtiste('ROLE_ARTISTE');
                },
                "choice_label" => "pseudo",
                "placeholder" => "Choisissez parmi les artistes...",
                "multiple" => false,
                "expanded" => false
                
            ])
            
            ->add('annee', DateType::class, [
                "widget" => "single_text",
                "label" => "Réalisée en",
                "required" => false
            ])
            ->add('description')
            ->add('dimensions')
            ->add('prix', TextType::class)
            ->add('photo', FileType::class, [
                "mapped" => false,
                "attr" => ["label_attr" => "Parcourir", "lang" => "fr"]
                
            ])
            ->add('stock')
            
            ->add('enregistrer', SubmitType::class,[
                "attr" => 
                ["class" => "btn"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Oeuvres::class,
        ]);
    }
}
