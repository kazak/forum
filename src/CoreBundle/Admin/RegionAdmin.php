<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 15.12.15
 * Time: 16:36
 */

namespace CoreBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class RegionAdmin
 * @package CoreBundle\Admin
 */
class RegionAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', 'text', [
            'label' => 'имя'
            ])
            ->add('description', 'sonata_simple_formatter_type', [
                'format' => 'richhtml',
                'label' => 'Описание',
                'required' => false])
//            ->add('latlng', 'oh_google_maps', [
//                'label' => 'Карта',
//                'default_lat'    => 50.44241983384863,
//                'default_lng'    => 30.52722930908203,
//                'required' => false])
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
            ])
            ->add('background', 'comur_image', [
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

        return;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('title');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->add('id')
            ->addIdentifier('title')
            ->add('slug');
    }
}