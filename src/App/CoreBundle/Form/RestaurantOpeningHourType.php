<?php

namespace App\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RestaurantOpeningHourType.
 */
class RestaurantOpeningHourType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('service')
            ->add('date')
            ->add('openingTime')
            ->add('closingTime')
            ->add('reason')
            ->add('restaurant')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'App\CoreBundle\Entity\RestaurantOpeningHour',
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'restaurantopeninghour';
    }
}
