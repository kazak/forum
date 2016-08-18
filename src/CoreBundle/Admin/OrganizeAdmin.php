<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 16.12.15
 * Time: 13:09
 */

namespace CoreBundle\Admin;

use CoreBundle\Entity\Forum;
use CoreBundle\Entity\Organize;
use CoreBundle\Entity\VotingParams;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class OrganizeAdmin
 * @package CoreBundle\Admin
 */
class OrganizeAdmin extends AbstractAdmin
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * ForumAdmin constructor.
     * @param string $code
     * @param string $class
     * @param string $baseControllerName
     * @param $container
     */
    public function __construct($code, $class, $baseControllerName, $container)
    {
        parent::__construct($code, $class, $baseControllerName);

        $this->container = $container;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {

        $formMapper->add('title', 'text',[
            'label' => 'название организации',
            ])
            ->add('visible', 'checkbox',[
                'label' => 'включить',
                'required' => false,
            ])
            ->add('address','text', [
                'label' => 'адрес',
                'required' => false,
            ])
            ->add('description','sonata_simple_formatter_type', [
                'format' => 'richhtml',
                'label' => 'Описание',
                'required' => false,
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
                    'generateFilename' => true,
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
                            'useAsFieldImage' => true,
                        ],
                    ],
                ],

                'required' => false,
                    'label' => 'Изображение',
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
                    'generateFilename' => true,
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
                            'useAsFieldImage' => true,
                        ],
                    ],
                ],

                'required' => false,
                'label' => 'Галерея',
            ])
            ->add('latlng', 'oh_google_maps', [
                'label' => 'Карта',
                'default_lat'    => 50.44241983384863,
                'default_lng'    => 30.52722930908203,
                'required' => false])
            ->add('users', 'sonata_type_model',[
                'by_reference' => false,
                'multiple' => true,
                'label' => 'Жители дома',
                'required' => false,
            ])
            ->add('admin', 'sonata_type_model',[
                'by_reference' => false,
                'label' => 'администрация дома',
                'multiple' => true,
                'required' => false,
            ])
            ->add('city', 'sonata_type_model_autocomplete', [
                'property'=>'title',
                'label' => 'город',
                'required' => false,
            ])
            ->add('info','sonata_simple_formatter_type', [
                'format' => 'richhtml',
                'label' => 'состояние организации, показание счетчиков и т.д.',
                'required' => false,
            ])
            ->add('message','text', [
                'label' => 'сообщение - Внимание!',
                'required' => false,
            ])
            ->add('showMessage', 'checkbox',[
                'label' => 'показывать сообщение',
                'required' => false,
            ])
            ->add('forums', 'sonata_type_collection', [
                'required' => false,
                'compound' => true,
                'by_reference' => true,
                'label' => 'Форумы',
            ],[
                    'allow_delete' => true,
                    'btn_del' => true,
                    'multiple' => true,
                    'expanded' => true,
                    'edit' => 'inline',
                    'inline' => 'table',

                ]
            );
        ;
    }

    /**
     * @param Organize $organize
     */
    public function preUpdate($organize)
    {
        $forms = $this->getForm()->get('forums')->getData();
        //$user = $this->container->get('security.context')->getToken()->getUser();

        /** @var Forum $form */
        foreach ($forms as $form) {

            $voting = $form->getVoting();
            if(!is_null($voting->getTitle())){
                $params = $voting->getParams();
                if($params){
                    /** @var VotingParams $param */
                    foreach($params as $param){
                        $param->setVoting($voting);
                    }
                }
            }else{
                $form->setVoting(null);
            }
        }
    }

    /**
     * @param mixed $form
     */
    public function prePersist($form)
    {
        $this->preUpdate($form);
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
        $showMapper->add('id')
            ->add('title')
            ->add('slug')
            ->add('city.title')
            ->add('address')
            ->add('visible', 'boolean', [ 'editable' => true ])
        ;
    }
}