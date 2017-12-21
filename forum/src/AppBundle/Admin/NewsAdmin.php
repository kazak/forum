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
class NewsAdmin extends  AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', 'text',[
            'label' => 'название',
        ])
            ->add('city', 'sonata_type_model_autocomplete', [
                'property'=>'title',
                'label' => 'город',
                'required' => false,
            ])
            ->add('region', 'sonata_type_model_autocomplete', [
                'property'=>'title',
                'label' => 'регион',
                'required' => false,
            ])
            ->add('visible', 'checkbox',[
                'label' => 'включить',
                'required' => false,
            ])
            ->add('startPage', 'checkbox',[
                'label' => 'на главной',
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