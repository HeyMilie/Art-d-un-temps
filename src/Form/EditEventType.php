<?php

namespace App\Form;

use App\Entity\Events;
use App\Repository\UsersRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EditEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('dateDebut', DateType::class, [
                "widget" => "single_text",
                "label" => "Début de l'événement",
                "required" => false
            ])
            ->add('dateFin', DateType::class, [
                "widget" => "single_text",
                "label" => "Fin de l'événement",
                "required" => false
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
            ->add('users', EntityType::class, array(
                'class' => 'App\Entity\Users',
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
                'label' => "Artiste(s) concerné(s) par l'événement",
                'query_builder' => function (UsersRepository $userRepo) {
                    return $userRepo->findByArtiste('ROLE_ARTISTE');
                },
                ))
            
            
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
            'data_class' => Events::class,
        ]);
    }
}
