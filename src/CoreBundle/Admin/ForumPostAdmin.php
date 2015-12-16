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
class ForumPostAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('description',null,[
            'label' => 'Описание',
            'attr' => ['style' => 'width: 200px']
        ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('owner');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('team')
        ->addIdentifier('id');
    }
}