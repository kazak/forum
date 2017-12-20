<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 16.12.15
 * Time: 12:24
 */

namespace Forum\Bundle\ForumBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class ForumAdmin
 * @package Forum\Bundle\ForumBundle\Admin
 */
class ForumAdmin extends  AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', 'text',[
                'label' => 'название',
            ])
            ->add('description','sonata_simple_formatter_type', [
                'format' => 'richhtml',
                'label' => 'Описание',
                'required' => false
            ])
            ->add('organize', 'sonata_type_model',[
                'by_reference' => false,
                'label' => 'организация',
                'multiple' => true,
                'required' => false
            ])
            ->add('city', 'sonata_type_model',[
                'by_reference' => false,
                'label' => 'город',
                'multiple' => true,
                'required' => false
            ])
            ->add('region', 'sonata_type_model',[
                'by_reference' => false,
                'label' => 'регион',
                'multiple' => true,
                'required' => false
            ])
            ->add('visible', 'checkbox',[
                'label' => 'показывать',
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