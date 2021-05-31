<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Membre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('description')
            // ->add('membre')

            ->add("photo", FileType::class, [
                "mapped" => false,
                "attr" => ["label_attr" => "Parcourir", "lang" => "fr"]
                
            ])

            ->add('auteur')
            // ->add('Auteur', EntityType::class, [
            //     "class" => Membre::class,
            //     "choice_label" => "pseudo",
            //     "placeholder" => "Choisissez parmi les membres..."
            // ])
            
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
            'data_class' => Article::class,
        ]);
    }
}
