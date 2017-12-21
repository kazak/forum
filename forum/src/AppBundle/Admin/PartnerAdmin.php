<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 16.12.15
 * Time: 12:24
 */

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class CityAdminextends
 * @package AppBundle\Admin
 */
class PartnerAdmin extends  AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', 'text',[
                'label' => 'название',
            ])
            ->add('phone', 'text',[
                'label' => 'тедефон',
            ])
            ->add('email', 'text',[
                'label' => 'email',
            ])
            ->add('balance', 'text',[
                'label' => 'баланс',
            ])
            ->add('address', 'text',[
                'label' => 'адрес',
            ])
            ->add('visible', 'checkbox',[
                'label' => 'включить',
                'required' => false,
            ])
            ->add('vip', 'checkbox',[
                'label' => 'vip',
                'required' => false,
            ])
            ->add('description','sonata_simple_formatter_type', [
                'format' => 'richhtml',
                'label' => 'Описание',
                'required' => false
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
            ->add('id')
            ->add('visible', 'boolean', [ 'editable' => true ]);
    }
}