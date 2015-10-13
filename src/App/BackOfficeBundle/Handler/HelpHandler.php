<?php

/**
 * Created by PhpStorm.
 * User: dss
 * Date: 29.09.15
 * Time: 13:00
 */

namespace App\BackOfficeBundle\Handler;

use App\BackOfficeBundle\AppBackOfficeBundle;
use App\CoreBundle\Model\Handler\EntityCrudHandler;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Request;
use App\BackOfficeBundle\Entity\HelpMenu;
use App\BackOfficeBundle\Entity\HelpPage;
use App\BackOfficeBundle\Repository\HelpMenuRepository;
use App\BackOfficeBundle\Repository\HelpPageRepository;
use App\BackOfficeBundle\Form\HelpType;

/**
 * Class HelpHandler
 * @package App\BackOfficeBundle\Handler
 */
class HelpHandler extends EntityCrudHandler
{
    /**
     *
     */
    const FORM_TABLE_NAME = 'app_help_type';

    /**
     * @var HelpMenu
     */
    protected $entityMenu;

    /**
     * @var HelpMenuRepository
     */
    protected $repositoryMenu;

    /**
     * @param Container $container
     * @param HelpMenu $entityMenu
     * @param HelpPage $entityHelp
     */
    public function __construct(Container $container, $entityMenu, $entityHelp)
    {
        parent::__construct($container, $entityHelp);

        $this->entityMenu = $entityMenu;
        $this->repositoryMenu = $this->objectManager->getRepository($this->entityMenu);
    }


    /**
     * @return mixed
     */
    public function getOneHelp()
    {
        $menu = $this->repositoryMenu->findBy([], [], 1);
        if(count($menu) > 0){
            return $menu[0]->getHelp()[0];
        }
        return null;
    }

    /**
     * @return mixed
     */
    public function getMenu()
    {
        return $this->repositoryMenu->findAll();
    }

    /**
     * @param $menu
     * @return array
     */
    public function getHelpForms($menu)
    {
        $forms = [];
        /**
         * @var HelpMenu $helpMenu
         */
        foreach($menu as $key => $helpMenu){
            $forms[$key]['menu'] = $helpMenu;

            foreach($helpMenu->getHelp() as $help){

                $forms[$key]['entity'][$help->getId()]['form'] = $this->getFormHelp($help)->createView();
                $forms[$key]['entity'][$help->getId()]['help'] = $help;
            }
            $newHelp = new $this->entityClass;
            $forms[$key]['new_form'] = $this->getFormHelp($newHelp, $helpMenu->getId())->createView();
        }

        return $forms;
    }

    /**
     * @param $help
     * @param null $idMenu
     * @return mixed
     */
    public function getCreateForm($help, $idMenu = null)
    {
        if(is_null($idMenu)){
            $id = $help->getId();
            $action = 'update';
        }else{
            $id = $idMenu;
            $action = 'create';
        }

        $form = $this->createForm($this::FORM_TABLE_NAME, $help, [
            'action' => $this->generateUrl('app_back_office_help_'.$action, ['id' => $id]),
        ])
            ->add('title', 'text')
            ->add('description','ckeditor')
            ->add('id','hidden', ['attr' => ['value' => '0']])
            ->add('submit', 'submit', ['label' => $action]);

        return $form;
    }


    /**
     * @param Request $request
     * @param $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function processUpdateAction(Request $request, $id)
    {
        $entity = $this->getEntity($id);
        $form = $this->getCreateForm($entity);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $this->saveEntity($entity, $request);

            return $this->redirectToRoute('app_back_office_help_show', ['id' => $entity->getId()]);
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * @param Request $request
     * @param $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function CreateAction(Request $request, $id)
    {
        $entity = $this->createEntity();
        $menu = $this->repositoryMenu->find($id);

        $entity->setMenu($menu);

        $form = $this->getCreateForm($entity, $id);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $this->objectManager->persist($entity);
            $this->objectManager->flush();

            return $this->redirectToRoute('app_back_office_help_show', ['id' => $entity->getId()]);
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function processDeleteAction(Request $request, $id)
    {
        $entity = $this->getEntity($id);

        if (!$entity) {
            return $this->redirectToRoute('app_back_office_restaurant_index');
        }

        $this->objectManager->remove($entity);
        $this->objectManager->flush();

        return $this->redirectToRoute('app_back_office_help_add');
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function processCreateMenuAction(Request $request)
    {
        $post = $request->request->all();

        if(isset($post['title'])){
            $entity = new $this->entityMenu;
            $entity->setTitle($post['title']);
            $this->objectManager->persist($entity);
            $this->objectManager->flush();
        }
        return true;
    }

    /**
     * @param Request $request
     * @param $id
     * @return bool
     */
    public function processUpdateMenuAction(Request $request, $id)
    {
        $post = $request->request->all();

        if(isset($post['title'])){
            $entity = $this->repositoryMenu->find($id);
            $entity->setTitle($post['title']);
            $this->objectManager->persist($entity);
            $this->objectManager->flush();
        }
        return true;
    }

}