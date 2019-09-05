<?php

namespace App\Form;

use App\Entity\Option;
use App\Entity\PropertySearch;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertySearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('maxPrice', IntegerType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Budget max'
                ] 
            ])

            ->add('minSurface', IntegerType::class, [
                'required' => false,
                'label' => false, 
                'attr' => [
                    'placeholder' => 'Surface minimale'          
                ]
            ])

            ->add('option', EntityType::class, [
                'required' => false,
                'label' => false, 
                'class' => Option::class,
                'choice_label' => 'name',  
                'multiple' => true         
            ])
            // il faut eviter de mettre le bouton ici sauf si on a plsr boutton
            //dans ce cas la je decoche cette pârtie et j'ajoute le bouton dans le maison.html.twig
            // ->add('submit', SubmitType::class , [
            //     'label'   => 'Rechercher'
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PropertySearch::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }

    //pour retourner dans l'URL des chemins plus au moins lisible
    public function getBlockPrefix()
    {
        return '';
    }
}
