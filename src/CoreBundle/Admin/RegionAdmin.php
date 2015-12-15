<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 15.12.15
 * Time: 16:36
 */

namespace CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class RegionAdmin
 * @package CoreBundle\Admin
 */
class RegionAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('name', 'text')
        ->add('lat')
        ->add('lng')
        ->add('description',null,[
            'label' => 'Описание',
            'attr' => ['style' => 'width: 200px']
        ])
        ->add('image');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name')
        ->addIdentifier('id');
    }
}