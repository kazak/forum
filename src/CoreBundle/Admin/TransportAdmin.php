<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 28.03.16
 * Time: 15:07
 */

namespace CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class NewsAdmin
 * @package CoreBundle\Admin
 */
class TransportAdmin extends  Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', 'text')
            ->add('description','sonata_simple_formatter_type', [
                'format' => 'richhtml',
                'label' => 'Описание'])
            ->add('gallery', 'comur_gallery', [
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
                    'aspectRatio' => false,
                    'cropRoute' => 'comur_api_crop',
                    'forceResize' => false,
                    'thumbs' => [
                        [
                            'maxWidth' => 1200,
                            'maxHeight' => 800,
                            'useAsFieldImage' => true
                        ]
                    ]
                ],
                'required' => false,
                'label' => 'Галерея'
            ])
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
                    'aspectRatio' => false,
                    'cropRoute' => 'comur_api_crop',
                    'forceResize' => false,
                    'thumbs' => [
                        [
                            'maxWidth' => 1200,
                            'maxHeight' => 800,
                            'useAsFieldImage' => true
                        ]
                    ]
                ],
                'required' => false,
                'label' => 'Изображение'
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
            ->addIdentifier('id');
    }
}