<?php

/**
 * @author:     aat <aat@norse.digital>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 07 07 2015
 */
namespace App\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RestaurantType.
 */
class MenuType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('status', 'choice', [
                'choices' => ['1' => 'Visible', '0' => 'Hidden'],
                'required' => true,
            ])
            ->add('priority', null, [
                'attr' => [
                    'max' => 100
                ]
            ])
            ->add('hideForSearchEngines', 'checkbox', [
                'label' => 'Hide for search engines',
                'required' => false,
            ])
            ->add('siteMap','checkbox',['label' => 'Show page from search engines']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'App\CoreBundle\Entity\Menu',
                'csrf_protection' => false,
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_menu_type';
    }
}
