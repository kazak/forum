<?php
/**
 * @author:     aat <aat@norse.digital>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 25 08 2015
 */
namespace App\CoreBundle\Form;

use App\CoreBundle\Entity\FrontPageBlock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FrontPageBlockType.
 */
class FrontPageBlockType extends AbstractType
{
    private $frontPageBlock = null;

    /**
     * @param FrontPageBlock $fromtPageBlock
     */
    public function __construct(FrontPageBlock $fromtPageBlock = null)
    {
        $this->frontPageBlock = $fromtPageBlock;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('main_image', new ImageType(
                $this->frontPageBlock !== null ? $this->frontPageBlock->getMainImage() : null
            ), [
                'label'=>'Main image',
                'mapped' => false
            ])
            ->add('secondary_image', new ImageType(
                $this->frontPageBlock !== null ? $this->frontPageBlock->getSecondaryImage() : null
            ), [
                'label'=>'Secondary image',
                'mapped' => false
            ])
            ->add('alternativeText')
            ->add('priority', 'hidden')
            ->add('style', 'choice', [
                'choices' => [
                    '1x1' => 'Standard størrelse',
                    '2x2' => 'Dobbel høyde og bredde',
                    '2x1' => 'Dobbel høyde',
                    '1x2' => 'Dobbel bredde',
                ],
                'required' => true,
            ])
            ->add('url', 'text', [
                'required' => false
            ])
            ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'App\CoreBundle\Entity\FrontPageBlock',
                'csrf_protection' => false,
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_front_page_block_type';
    }
}
