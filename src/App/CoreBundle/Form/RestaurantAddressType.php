<?php

/**
 * @author:     pm <pm@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 04 05 2015
 */
namespace App\CoreBundle\Form;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RestaurantAddressType
 * @package App\CoreBundle\Form
 */
class RestaurantAddressType extends AbstractType
{
    private $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address')
            ->add('postCode')
            ->add('postOffice')
            ->add('latitude', 'number', ['precision' => 12])
            ->add('longitude', 'number', ['precision' => 12]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'App\CoreBundle\Entity\RestaurantAddress',
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_restaurant_address_type';
    }
}
