<?php

namespace App\Form;

use App\Entity\Menu;
use App\Entity\Starter;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class StarterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextareaType::class)
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('allergens', ChoiceType::class, [
                'choices'  => [
                    'oeuf' => 'oeuf',
                    'lactose' => 'lactose',
                    'arachadides' => 'arachadides',
                    'crustacés' => 'crustacés',
                    'pectine' => 'pectine',
                    'gluten' => 'gluten',
                    'poisson' => 'poisson',
                ],
                'required' => false,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('pictureFile', VichFileType::class, [
                'required'      => false,
                'label' => 'Image',
                'allow_delete'  => true,
                'download_uri' => true,
            ])
            ->add('menu', EntityType::class, [
                'class' => Menu::class,
                'choice_label' => 'title',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Starter::class,
        ]);
    }
}
