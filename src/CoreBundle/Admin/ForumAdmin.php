<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 15.12.15
 * Time: 16:36
 */

namespace CoreBundle\Admin;

use CoreBundle\Entity\Forum;
use CoreBundle\Entity\ForumPost;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Class ForumAdmin
 * @package CoreBundle\Admin
 */
class ForumAdmin extends AbstractAdmin
{
    /**
     * @var
     */
    protected $container;

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

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', 'text',[
            'label' => 'название поста',
            ])
            ->add('visible', 'checkbox',[
                'label' => 'показывать',
                'required' => false,
            ])
            ->add('description','sonata_simple_formatter_type', [
                'format' => 'richhtml',
                'label' => 'Описание',
                'required' => false,
            ])
            ->add('posts', 'sonata_type_collection', [
                'required' => false,
                'compound' => true,
                'by_reference' => true,
                'label' => 'посты',
            ],[
                    'allow_delete' => true,
                    'btn_del' => true,
                    'multiple' => true,
                    'expanded' => true,
                    'edit' => 'inline',
                    'inline' => 'table',
                ]
            )
            ->add('voting', 'sonata_type_admin', [
                'required' => false,
                'label' => 'голосование',
            ],[
                'admin_code' => 'admin.voting'
            ]);
    }

    /**
     * @param Forum $form
     */
    public function preUpdate($form)
    {
        $formPosts = $this->getForm()->get('posts')->getData();
        $user = $this->container->get('security.context')->getToken()->getUser();

        /** @var ForumPost $post */
        foreach ($formPosts as $post) {
            $post->setForum($form);

            if(is_null($post->getOwner())){
                $post->setOwner($user);
            }
        }

        $voting = $this->getForm()->get('voting')->getData();
        if(!is_null($voting->getTitle())){
            $params = $voting->getParams();
            if($params){
                foreach($params as $param){
                    $param->setVoting($voting);
                }
            }
        }else{
            $form->setVoting(null);
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
        $listMapper->addIdentifier('id')
            ->add('visible', 'boolean', [ 'editable' => true ])
            ->add('voting','boolean')
            ->addIdentifier('title')
        ;
    }
}