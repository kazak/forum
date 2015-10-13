<?php

/**
 * @author:     lars <lars@norse.digital>
 *
 * @copyright   Copyright (C) 2015 Norse Digital AS.
 * @date: 09 07 2015
 */
namespace App\DollyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class WebStateService.
 */
class ContactRequestType extends AbstractType
{
    private $translator;

    /**
     * @param $translator
     */
    public function __construct($translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', 'text', $this->addPlaceholderText('First name'))
            ->add('lastName', 'text', $this->addPlaceholderText('Last name'))
            ->add('email', 'email', $this->addPlaceholderText('Your email address'))
            ->add('subject', 'text', $this->addPlaceholderText('Subject'))
            ->add('message', 'textarea', $this->addPlaceholderText('Your message...'));
    }

    /**
     * @param $label
     * @return array
     */
    private function addPlaceholderText($label)
    {
        return array(
            'attr' => array(
                'placeholder' => /* @Ignore */$this->translator->trans($label, array(), 'dolly'),
            ),
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'contact_form';
    }
}
