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
 * Class PhotoAdmin
 * @package CoreBundle\Admin
 */
class PhotoAdmin extends  Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', 'text', [
            'label' => 'название альбома'
        ])
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
                            'maxWidth' => 180,
                            'maxHeight' => 400,
                            'useAsFieldImage' => true
                        ]
                    ]
                ],

                'required' => false,
                'label' => 'Галерея'
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