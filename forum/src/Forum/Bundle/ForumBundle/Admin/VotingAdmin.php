<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 17.06.16
 * Time: 12:53
 */

namespace Forum\Bundle\ForumBundle\Admin;

use Forum\Bundle\ForumBundle\Entity\VotingParams;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class VotingAdmin
 * @package Forum\Bundle\ForumBundle\Admin
 */
class VotingAdmin extends AbstractAdmin
{
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', 'text', [
            'label' => 'название',
        ])
            ->add('description','sonata_simple_formatter_type', [
                'format' => 'richhtml',
                'label' => 'Описание'
            ])
            ->add('params', 'sonata_type_collection', [
                'required' => false,
                'compound' => true,
                'by_reference' => true,
                'label' => 'параметры',
            ],[
                    'allow_delete' => true,
                    'btn_del' => true,
                    'multiple' => true,
                    'expanded' => true,
                    'edit' => 'inline',
                    'inline' => 'table',
                ]
            );
    }

    /**
     * @param mixed $form
     */
    public function preUpdate($form)
    {
        $params = $this->getForm()->get('params')->getData();

        /** @var VotingParams $param */
        foreach ($params as $param) {
            $param->setVoting($form);
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
            ->addIdentifier('title')
            ->add('forum.id')
            ->add('forum.title');
    }

}