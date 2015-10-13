<?php

/**
 * @author:     pm <pm@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 22 05 2015
 */

namespace App\CoreBundle\Model\Handler;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class EntityCrudHandler.
 */
abstract class EntityCrudHandler extends EntityHandler implements EntityCrudHandlerInterface
{
    protected $formName;

    protected $locale_enabled;

    /**
     * @param Container $container
     * @param $entityClass
     * @param $formName
     * @param $location_enabled
     */
    public function __construct(Container $container, $entityClass, $formName, $location_enabled = false)
    {
        parent::__construct($container, $entityClass);

        $this->formName = $formName;
        $this->locale_enabled = $location_enabled;
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function processIndexAction(Request $request)
    {
        return [
            'entities' => $this->getEntities()
        ];
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function processCreateAction(Request $request)
    {
        $entity = $this->createEntity();

        /** @var Form $form */
        $form = $this->getCreateForm($entity);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->saveEntity($entity, $request);
        }

        return [
            'entity' => $entity,
            'form' => $form->createView()
        ];
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return array
     *
     * @throws NotFoundHttpException
     */
    public function processShowAction(Request $request, $id)
    {
        $entity = $this->getEntity($id);

        if (!$entity) {
            throw new NotFoundHttpException('Entity not found', null);
        }

        return [
            'entity' => $entity
        ];
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return array
     *
     * @throws NotFoundHttpException
     */
    public function processUpdateAction(Request $request, $id)
    {
        $entity = $this->getEntity($id);

        if (!$entity) {
            throw new NotFoundHttpException('Entity not found', null);
        }

        /** @var FormInterface $form */
        $form = $this->getUpdateForm($entity);

        $this->handleFormByRequest($request, $form, $entity);

        if ($form->isValid()) {
            $this->saveEntity($entity, $request);
        }

        return [
            'entity' => $entity,
            'form' => $form->createView()
        ];
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return array
     *
     * @throws NotFoundHttpException
     */
    public function processDeleteAction(Request $request, $id)
    {
        $entity = $this->getEntity($id);

        if (!$entity) {
            throw new NotFoundHttpException('Entity not found', null);
        }

        $form = $this->getDeleteForm($entity);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->removeEntity($entity);
        }

        return [
            'entity' => $entity,
            'form' => $form->createView()
        ];
    }

    /**
     * @return FormFactory
     */
    public function getFormFactory()
    {
        return $this->container->get('form.factory');
    }

    /**
     * @param $formName
     * @param $entity
     * @param array $options
     *
     * @return Form|FormInterface
     */
    public function createForm($formName, $entity, array $options = [])
    {
        // in case when service_id passed as a $formName
        //return $this->getFormFactory()->create($this->container->get($this->formName), $entity);
        // in case when service_tag_alias passed as a $formName
        return $this->getFormFactory()->create($formName, $entity, $options);
    }

    /**
     * @param $entity
     *
     * @return $this|FormInterface
     */
    public function getCreateForm($entity)
    {
        $form = $this->createForm($this->formName, $entity)
            ->add('submit', 'submit', ['label' => 'Create']);

        return $form;
    }

    /**
     * @param $entity
     *
     * @return $this|FormInterface
     */
    public function getUpdateForm($entity)
    {
        $form = $this->createForm($this->formName, $entity)
            ->add('submit', 'submit', ['label' => 'Update']);

        return $form;
    }

    /**
     * @param $entity
     *
     * @return $this|FormInterface
     */
    public function getDeleteForm($entity)
    {
        $form = $this->createForm($this->formName, $entity)
            ->add('submit', 'submit', ['label' => 'Delete']);

        return $form;
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->container->get('router');
    }

    /**
     * @param $route
     * @param array $parameters
     *
     * @return string
     */
    public function generateUrl($route, array $parameters = [])
    {
        return $this->getRouter()->generate($route, $parameters);
    }

    /**
     * @param $route
     * @param array $parameters
     * @param int   $status
     *
     * @return RedirectResponse
     */
    public function redirectToRoute($route, array $parameters = [], $status = 302)
    {
        return new RedirectResponse($this->generateUrl($route, $parameters), $status);
    }


    /**
     * @param Request $request
     * @param Form $form
     */
    protected function handleFormByRequest(Request $request, $form)
    {
        $form->handleRequest($request);
    }
}
