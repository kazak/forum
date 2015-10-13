<?php
/**
 * @author:     aat <aat@norse.digital>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 25 08 2015
 */
namespace App\CoreBundle\Form;

use App\CoreBundle\Entity\FrontPage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FrontPageType.
 */
class FrontPageType extends AbstractType
{
    private $frontPage = null;

    /**
     * @param FrontPage $fromtPage
     */
    public function __construct(FrontPage $fromtPage = null)
    {
        $this->frontPage = $fromtPage;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', [
                'required' => false,
            ])
            ->add('showDate', 'datetime')
            ->add('hideDate', 'datetime')
            ->add('default', 'hidden', ['data' => 0])
            ->add('hero_image', new ImageType(
                $this->frontPage !== null ? $this->frontPage->getHeroImage() : null
            ), [
                'label' => 'Hero image',
                'mapped' => false
            ])
            ->add('alternativeHeroText')
            ->add('status', 'choice', [
                'choices' => [
                    '0' => 'Not published',
                    '1' => 'Published'
                ],
                'required' => true,
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'App\CoreBundle\Entity\FrontPage',
                'csrf_protection' => false,
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_front_page_type';
    }
}
