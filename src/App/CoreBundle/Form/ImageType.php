<?php

/**
 * @author:     aat <aat@norse.digital>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 02 10 2015
 */
namespace App\CoreBundle\Form;

use App\CoreBundle\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ImageType.
 */
class ImageType extends AbstractType
{
    private $image = null;

    /**
     * @param Image $image
     */
    public function __construct(Image $image = null)
    {
        $this->image = $image;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file', [
                'mapped' => false,
                'required' => false,
                'label' => ' ',
                'data_class' => null,
                'attr'=>[
                    'class'=>'btn-default btn'
                ]
            ])
            ->add('add_existing_image', 'button', [
                'label'=>'Add existing image',
                'attr' =>[
                    'class' => 'btn-info btn hidden js-add-exist-image'
                ]
            ])
            ->add('id', 'hidden', [
                'mapped' => false,
                'data' => $this->image !== null ? $this->image->getId() : null,
            ])
            ->add('path', 'button', [
                'disabled' => true,
                'attr' => [
                    'class'=>'additional_to_upload',
                    'style' => '
                    background-image: url('.($this->image !== null ? $this->image->getPath() : null).');
                    background-repeat: no-repeat;
                    background-size: contain;
                    '
                ],
                'label' => 'Current image',
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'App\CoreBundle\Entity\Image',
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_restaurant_image_type';
    }
}
