<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 16.12.15
 * Time: 13:09
 */

namespace CoreBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

/**
 * Class OrganizeAdmin
 * @package CoreBundle\Admin
 */
class OrganizeAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {

        $formMapper->add('title', 'text',[
            'label' => 'название организации',
            ])
            ->add('address','text', [
                'label' => 'адрес',
                'required' => false
            ])
            ->add('description','sonata_simple_formatter_type', [
                'format' => 'richhtml',
                'label' => 'Описание',
                'required' => false
            ])
            ->add('image', 'comur_image', array(
                'uploadConfig' => array(
                    'uploadRoute' => 'comur_api_upload',
                    'uploadUrl' => 'uploads',
                    'webDir' => 'uploads',
                    'fileExt' => '*.jpg;*.gif;*.png;*.jpeg',
                    'libraryDir' => null,
                    'libraryRoute' => 'comur_api_image_library',
                    'showLibrary' => true,
                    'saveOriginal' => 'originalImage',
                    'generateFilename' => true
                ),
                'cropConfig' => array(
                    'minWidth' => 100,
                    'minHeight' => 100,
                    'aspectRatio' => true,
                    'cropRoute' => 'comur_api_crop',
                    'forceResize' => false,
                    'thumbs' => array(
                        array(
                            'maxWidth' => 180,
                            'maxHeight' => 400,
                            'useAsFieldImage' => true
                        )
                    )
                ),

                'required' => false, 'label' => 'Изображение'
            ))
            ->add('latlng', 'oh_google_maps', [
                'label' => 'Карта',
                'default_lat'    => 50.44241983384863,
                'default_lng'    => 30.52722930908203, 
                'required' => false])

            ->add('users', 'sonata_type_model',[
                'by_reference' => false,
                'multiple' => true,
                'label' => 'Жители дома',
                'required' => false
            ])
            ->add('admin', 'sonata_type_model',[
                'by_reference' => false,
                'label' => 'администрация дома',
                'multiple' => true,
                'required' => false
            ])
            ->add('visible', 'checkbox',[
                'label' => 'администрация дома',
                'required' => false
            ])
            ->add('city', 'sonata_type_model',[
                'by_reference' => false,
                'label' => 'город',
                'multiple' => true,
                'required' => false
            ])
            ->add('info','sonata_simple_formatter_type', [
                'format' => 'richhtml',
                'label' => 'состояние организации, показание счетчиков и т.д.',
                'required' => false
            ])
            ->add('message','text', [
                'label' => 'сообщение - Внимание!',
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
            ->add('slug');
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('title')
            ->add('slug')
        ;
    }
}