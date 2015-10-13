<?php

/**
 * @author:     pm <pm@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 05 06 2015
 */
namespace App\CoreBundle\Form;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ContentPageType.
 */
class ContentPageType extends AbstractType
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
            ->add('title')
            ->add('visible', 'choice', [
                'label' => 'Visible on Web',
                'choices' => ['Hidden', 'Visible'],
            ])
            ->add('body', 'ckeditor', ['config' => [
                'toolbar' => $this->getCKEditorToolbarConfig(),
                'filebrowserBrowseRoute' => 'elfinder',
                'filebrowserBrowseRouteParameters' => ['instance' => 'default'],
            ]])
            ->add('siteMap','checkbox',['label' => 'Show page from search engines']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'App\CoreBundle\Entity\ContentPage',
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_content_page_type';
    }


    /**
     * @return array
     */
    private function getCKEditorToolbarConfig()
    {
        $boConfig = $this->container->getParameter('app_back_office_config');

        return $boConfig['appearance']['ckeditor']['toolbar'];
    }
}
