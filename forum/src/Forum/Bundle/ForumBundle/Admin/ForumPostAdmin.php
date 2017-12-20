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
 * Class ForumPostAdmin
 * @package Forum\Bundle\ForumBundle\Admin
 */
class ForumPostAdmin extends  AbstractAdmin
{
    /**
     * ForumPostAdmin constructor.
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
        $formMapper->add('description','sonata_simple_formatter_type', [
            'format' => 'richhtml',
            'label' => 'Описание',
            'required' => false
        ])
            ->add('visible', 'checkbox',[
                'label' => 'показывать',
                'required' => false
            ]);
    }

    /**
     * @param mixed $formPost
     */
    public function prePersist($formPost)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $formPost->setOwner($user);
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('id')
            ->add('created')
            ->add('owner');
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id')
            ->addIdentifier('created')
            ->add('visible', 'boolean', [ 'editable' => true ])
            ->addIdentifier('owner.username');
    }
}