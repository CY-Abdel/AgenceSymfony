<?php

namespace App\Form;

use App\Entity\Maisons;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('surface')
            ->add('rooms')
            ->add('bedrooms')
            ->add('floor')
            /**
             * on peut changer le nom des label de cette façon ou bien de creer un fichier dans translation
             * comme en a  fait pour city par exemple dans la fonction en bas setDefaults
             */
            // ->add('price',null,[
            //     'label' => 'Prix'
            // ])
            ->add('price')
            ->add('heat', ChoiceType::class, [
                // 'choices' => Maisons::HEAT
                'choices' => $this->getChoices()                
            ])
            ->add('city')
            ->add('adresse')
            ->add('postal_code')
            ->add('sold')
            // ->add('created_at')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Maisons::class,
            /**
             * pour traduire les label city en ville price en prix ....
             */
            'translation_domain' => 'forms'
        ]);
    }

    private function getChoices()
    {
       $choices = Maisons::HEAT;
       $output =[];
       foreach ($choices as $key => $value) {
           $output[$value] = $key ;
       }
        return $output;
    }
}
