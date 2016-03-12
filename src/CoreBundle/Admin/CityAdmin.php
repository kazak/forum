<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 16.12.15
 * Time: 12:24
 */

namespace CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class CityAdminextends
 * @package CoreBundle\Admin
 */
class CityAdmin extends  Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', 'text')
            //->add('region')
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
        $datagridMapper->add('title');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('title')
            ->addIdentifier('id');
    }
}