<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 27.05.16
 * Time: 11:53
 */

namespace CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class VideoAdmin
 * @package CoreBundle\Admin
 */
class VideoAdmin extends  Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', 'text',['label' => 'ссылка с youtub']);
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