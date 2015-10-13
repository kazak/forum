<?php
/**
 * @author:     pm <pm@nxc.no>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 27 08 2015
 */

namespace App\CoreBundle\Form;

use App\CoreBundle\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ProductType
 * @package App\CoreBundle\Form
 */
class ProductType extends AbstractType
{
    private $product;
    private $config;

    /**
     * @param Product $product
     * @param $config
     */
    public function __construct(Product $product, $config)
    {
        $this->product = $product;
        $this->config = $config;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', 'a2lix_translations', [
                'label' => ' ',
                'fields' => [
                    'name' => [],
                    'number' => [],
                    'image' => [
                        'field_type' => new ImageType(
                            $this->product !== null ?
                                $this->product->getImage() :
                                null
                        ),
                        'label' => 'Image',
                        'required' => false,
                        'data_class' => null,
                        'mapped' => false
                    ],
                    'shortDescription' => [
                        'field_type' => 'ckeditor',
                        'config' => [
                            'toolbar' => $this->config,
                            'filebrowserBrowseRoute' => 'elfinder',
                            'filebrowserBrowseRouteParameters' => ['instance' => 'default'],
                        ],
                        'label' => 'Description in product tile'
                    ],
                    'description' => [
                        'field_type' => 'ckeditor',
                        'config' => [
                            'toolbar' => $this->config,
                            'filebrowserBrowseRoute' => 'elfinder',
                            'filebrowserBrowseRouteParameters' => ['instance' => 'default'],
                        ],
                        'label' => 'Description in product popup'
                    ],
                    'tags' => [
                        'field_type' => 'choice',
                        'choices' => [
                            'veg' => 'Vegetarian',
                            'hot' => 'Hot'
                        ],
                        'multiple' => true,
                        'expanded' => true
                    ]
                ],
                'exclude_fields' => [
                    'slug', 'metaKeywords', 'metaDescription'
                ]
            ])
            ->add('options', 'sylius_product_option_choice', [
                'required' => false,
                'multiple' => true,
                'label'    => 'Options'
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'App\CoreBundle\Entity\Product',
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_product_type';
    }
} 