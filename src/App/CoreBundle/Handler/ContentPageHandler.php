<?php

/**
 * @author:     pm <pm@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 04 06 2015
 */
namespace App\CoreBundle\Handler;

use App\CoreBundle\Entity\ContentPage;
use App\CoreBundle\Form\SeoType;
use App\CoreBundle\Model\Handler\ContentPageHandlerInterface;
use App\CoreBundle\Model\Handler\EntityCrudHandler;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class ContentPageHandler.
 */
class ContentPageHandler extends EntityCrudHandler implements ContentPageHandlerInterface
{
    const ROUTE_PREFIX_BO = 'app_back_office_content_';

    /**
     * @param $id
     *
     * @return null|ContentPage
     */
    public function getEntity($id)
    {
        return parent::getEntity($id);
    }

    /**
     * @return ContentPage
     */
    public function createEntity()
    {
        return parent::createEntity();
    }

    /**
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    public function processCreateAction(Request $request)
    {
        $entity = $this->createEntity();

        $form = $this->getCreateUpdateForm($entity);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->objectManager->persist($entity);
            $this->objectManager->flush();

            return $this->redirectToRoute($this->getBORoute('show'), ['id' => $entity->getId()]);
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return array|RedirectResponse
     */
    public function processUpdateAction(Request $request, $id)
    {
        $entity = $this->getEntity($id);

        if (!$entity) {
            return $this->redirectToRoute($this->getBORoute('index'));
        }

        $form = $this->getCreateUpdateForm($entity);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->objectManager->merge($entity);
            $this->objectManager->flush();

            return $this->redirectToRoute($this->getBORoute('index'));
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return array
     */
    public function processDeleteAction(Request $request, $id)
    {
        $entity = $this->getEntity($id);

        $form = $this->getDeleteForm($entity);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->objectManager->remove($entity);
            $this->objectManager->flush();

            return $this->redirectToRoute($this->getBORoute('index'));
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return array
     *
     * @throws NotFoundHttpException
     * @throws AccessDeniedException
     */
    public function processShowBySlugAction(Request $request, $id)
    {
        $entity = $this->getRepository()->findOneBy(['slug' => $id]);

        if (!$entity) {
            //            return $this->processShowAction($request, $id);
            throw new NotFoundHttpException('Entity not found', null);
        }

        if (!$entity->getVisible()) {
            throw new AccessDeniedException('Entity is not accessible', null);
        }

        return [
            'entity' => $entity,
        ];
    }

    /**
     * @return string
     */
    public function getBORoutePrefix()
    {
        return self::ROUTE_PREFIX_BO;
    }

    /**
     * @param string $routeIdentifier
     *
     * @return string
     */
    public function getBORoute($routeIdentifier)
    {
        return $this->getBORoutePrefix().$routeIdentifier;
    }

    /**
     * @param ContentPage $entity
     *
     * @return $this|FormInterface
     */
    public function getCreateUpdateForm($entity)
    {
        $form = $this->createForm($this->formName, $entity)
            ->add('seo', new SeoType($this->container), [
                'label' => 'SEO',
            ])
            ->add('submit', 'submit', ['label' => 'Save']);

        return $form;
    }
}
