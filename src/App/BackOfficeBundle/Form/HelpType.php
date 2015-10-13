<?php

/**
 * Created by PhpStorm.
 * User: dss
 * Date: 30.09.15
 * Time: 14:43
 */
namespace App\BackOfficeBundle\Form;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class HelpType
 * @package App\BackOfficeBundle\Form
 */
class HelpType extends AbstractType
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
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'App\BackOfficeBundle\Entity\HelpPage',
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_help_type';
    }
}


