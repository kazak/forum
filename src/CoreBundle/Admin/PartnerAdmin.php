<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 30.03.16
 * Time: 13:21
 */

namespace CoreBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class PartnerAdmin
 * @package CoreBundle\Admin
 */
class PartnerAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', 'text', [
            'label' => 'название',
            ])
            ->add('phone', 'integer', [
                'label' => 'телефон',
            ])
            ->add('email', 'email', [
                'label' => 'email',
            ])
            ->add('address','text', [
                'label' => 'email',
                'required' => false
            ])
            ->add('visible', 'checkbox', [
                'label' => 'показывать',
                'required' => false
            ])
            ->add('balance')
            ->add('vip', 'checkbox',[
                'label' => 'vip',
                'required' => false
            ])
            ->add('description','sonata_simple_formatter_type', [
                'format' => 'richhtml',
                'label' => 'Описание',
                'required' => false])
            ->add('image', 'comur_image', [
                'uploadConfig' => [
                    'uploadRoute' => 'comur_api_upload',
                    'uploadUrl' => 'uploads',
                    'webDir' => 'uploads',
                    'fileExt' => '*.jpg;*.gif;*.png;*.jpeg',
                    'libraryDir' => null,
                    'libraryRoute' => 'comur_api_image_library',
                    'showLibrary' => true,
                    'saveOriginal' => 'originalImage',
                    'generateFilename' => true
                ],
                'cropConfig' => [
                    'minWidth' => 100,
                    'minHeight' => 100,
                    'aspectRatio' => true,
                    'cropRoute' => 'comur_api_crop',
                    'forceResize' => false,
                    'thumbs' => [
                        [
                            'maxWidth' => 180,
                            'maxHeight' => 400,
                            'useAsFieldImage' => true
                        ]
                    ]
                ],

                'required' => false,
                'label' => 'Изображение'
            ]);
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