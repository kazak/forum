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
 * Class RestaurantType.
 */
class SeoType extends AbstractType
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
            ->add('seoTitle')
            ->add('seoDescription')
            ->add('seoKeywords');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'App\CoreBundle\Entity\Seo',
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_restaurant_seo_type';
    }
}
