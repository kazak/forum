<?php

namespace CoreBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class: AdminAdmin.php
 *
 * @author    Odessa Team (odessateam@ab-soft.net)
 * @category  Ringcentral
 * @copyright Copyright (c) 2012-2016, RingCentral, Inc (http://www.ringcentral.com)
 *
 * @version $Id:$
 */
class AdminAdmin extends  AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', 'text',[
                'label' => 'должность',
            ])
            ->add('organize', 'sonata_type_model', [
                'property'=>'title',
                'label' => 'organize'
            ])
            ->add('owner', 'sonata_type_model',[
                'by_reference' => false,
                'label' => 'Юзер'
            ]);

    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('title');
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {

        $listMapper->addIdentifier('title')
            ->add('organize')
            ->add('owner');
    }
}