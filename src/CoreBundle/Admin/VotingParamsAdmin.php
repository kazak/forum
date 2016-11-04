<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 17.06.16
 * Time: 13:10
 */

namespace CoreBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class VotingParamsAdmin
 * @package CoreBundle\Admin
 */
class VotingParamsAdmin extends AbstractAdmin
{

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', 'text', [
            'label' => 'название',
        ])->add('users');
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
        $listMapper->addIdentifier('id')
            ->addIdentifier('title')
            ->add('voting.title')
            ->add('voting.id')
            ->add('rating');
    }
}