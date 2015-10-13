<?php

/**
 * @author      :     aat <aat@norse.digital>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date        : 24 08 2015
 */
namespace App\CoreBundle\Handler;

use App\CoreBundle\Entity\FrontPage;
use App\CoreBundle\Form\FrontPageType;
use App\CoreBundle\Form\SeoType;
use App\CoreBundle\Model\Handler\EntityCrudHandler;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FrontPageHandler.
 */
class FrontPageHandler extends EntityCrudHandler
{
    use ContainerAwareTrait;

    const ROUTE_PREFIX_BO = 'app_back_office_front_page_';
    const FORM_TABLE_NAME = 'app_front_page_type';
    const FORM_FIELD_IMG_NAMES = ['hero_image'];
    const EXTENSION_PATH_TO_FILES = 'frontPage/';

    /**
     * @param Container $container
     * @param $entityClass
     */
    public function __construct(Container $container, $entityClass)
    {
        parent::__construct($container, $entityClass, null);
        $this->setContainer($container);
    }

    /**
     * @param $id
     *
     * @return null|FrontPage
     */
    public function getEntity($id)
    {
        return parent::getEntity($id);
    }

    /**
     * @param array $options
     * @return null|FrontPage
     */
    public function getEntitys(array $options = [])
    {
        return parent::getEntities($options);
    }

    /**
     * @param $id
     * @param $type
     *
     * @return array
     */
    public function delete($id, $type = 'json')
    {
        $pageNode = $this->getEntity($id);

        if ($pageNode && $pageNode->getDefault() != 1) {
            $this->objectManager->remove($pageNode);
            $this->objectManager->flush();

            if ($type != 'json') {
                return $this->redirectToRoute($this->getBORoute('index'));
            }
            return $this->getResponse($pageNode, null, 204);

        } else {
            return $this->getResponse(null, 'FrontPage');
        }
    }

    /**
     * @param FrontPage $pageNode
     *
     * @return array
     */
    public function setDefault(FrontPage $pageNode)
    {
        /**
         * @var FrontPage $page
         */
        if ($pageNode) {
            $pageNodes = $this->getEntities();

            foreach ($pageNodes as $page) {
                $page->setDefault(0);
            }
            $pageNode->setDefault(1);
            $this->objectManager->flush();

            return $this->getResponse($pageNode, null, 200);

        } else {
            return $this->getResponse(null, 'FrontPage');
        }
    }


    /**
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    public function processCreateAction(Request $request)
    {
        $pageNode = new FrontPage();
        $imageHandler = $this->container->get('app_core.image.handler');
        $post = $request->request->all()[self::FORM_TABLE_NAME];
        $image = $imageHandler->getImagesFromRequest($request, $this);
        $form = $this->getCreateUpdateForm($pageNode);

        if ($post !== null) {
            $form->submit($post);
            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute($this->getBORoute('index'));
            }
            if ($form->isValid()) {
                $pageNode->setHeroImage($image['hero_image']);
                $this->objectManager->persist($pageNode);
                $this->objectManager->flush();

                return $this->redirectToRoute($this->getBORoute('index'));
            }
        }
        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param $id
     *
     * @return RedirectResponse
     */
    public function processCopyAction($id)
    {
        $oldPageNode = $this->getEntity($id);

        if ($oldPageNode != null) {
            $pageNode = clone $oldPageNode;
            $this->objectManager->persist($pageNode);
            $this->objectManager->flush();

            return $this->redirectToRoute($this->getBORoute('update'), [
                'id' => $pageNode->getId(),
                'copy' => true
            ]);
        }
        return $this->redirectToRoute($this->getBORoute('index'));
    }

    /**
     * @param $id
     *
     * @return array|RedirectResponse
     */
    public function removeHeroImage($id)
    {
        $pageNode = $this->getEntity($id);

        /**
         * @var FrontPage $pageNode
         */
        if ($pageNode) {
            $pageNode->setHeroImage(null);
            $this->objectManager->flush();

            return $this->getResponse($pageNode, null, 200);
        } else {
            return $this->getResponse(null, 'FrontPage');
        }
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return array|RedirectResponse
     */
    public function processShowAction(Request $request, $id)
    {
        $entity = $this->getEntity($id);

        if (!$entity) {
            return $this->redirectToRoute($this->getBORoute('index'));
        }

        return [
            'pageNode' => $entity,
        ];
    }

    /**
     * @param Request $request
     * @param int $id
     * @param bool $isCopy
     *
     * @return array|RedirectResponse
     */
    public function processUpdateAction(Request $request, $id, $isCopy = false)
    {
        $pageNode = $this->getEntity($id);

        if (!$pageNode) {
            return $this->redirectToRoute($this->getBORoute('index'));
        }

        $default = $pageNode->getDefault();
        $form = $this->getCreateUpdateForm($pageNode);
        $imageHandler = $this->container->get('app_core.image.handler');
        $post = $request->request->all()[self::FORM_TABLE_NAME];

        if ($post != null) {
            $image = $imageHandler->getImagesFromRequest($request, $this);
            $form->submit($post);
            if ($form->get('cancel')->isClicked()) {
                if($isCopy){
                    return $this->delete($id, 'html');
                }
                return $this->redirectToRoute($this->getBORoute('index'));
            }
            if ($form->isValid()) {
                $pageNode->setDefault($default);
                $this->setImages($pageNode, $image);
                $this->objectManager->merge($pageNode);
                $this->objectManager->flush();

                return $this->redirectToRoute($this->getBORoute('index'));
            }
        }

        return [
            'pageNode' => $pageNode,
            'form' => $form->createView(),
            'is_copy' => $isCopy
        ];
    }

    /**
     * @param FrontPage $pageNode
     * @param array $image
     */
    protected function setImages(FrontPage &$pageNode, array $image = null)
    {
        $pageNode->setHeroImage(
            isset($image['hero_image']) ? $image['hero_image'] : null
        );
    }

    /**
     * @return array|mixed|null|object
     */
    public function getCurrentFrontPage()
    {
        $currentDate = new \DateTime();
        $criteria = new Criteria();

        $criteria->where($criteria->expr()->eq('default', 0));
        $criteria->andWhere($criteria->expr()->eq('status', 1));
        $criteria->andWhere($criteria->expr()->lte('showDate', $currentDate));
        $criteria->andWhere($criteria->expr()->gte('hideDate', $currentDate));

        /**
         * @var ArrayCollection $pageNodes
         */
        $pageNodes = $this->repository->matching($criteria);

        if ($pageNodes->count() == 0) {
            $pageNode = $this->findOne([
                'default' => 1
            ]);
        } else {
            $pageNode = $pageNodes->first();
        }

        return $pageNode;
    }

    /**
     * @param FrontPage $entity
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    public function getCreateUpdateForm($entity)
    {
        $form = $this->container->get('form.factory')
            ->create(new FrontPageType($entity), $entity)
            ->add('seo', new SeoType($this->container), [
                'label' => 'SEO',
            ])
            ->add('cancel', 'submit', [
                'label' => 'Cancel',
                'validation_groups' => false,
                'attr' => [
                    'style' => 'float:left;',
                    'class' => 'btn btn-primary'
                ]
            ])
            ->add('submit', 'submit', [
                'label' => 'Save',
                'attr' => [
                    'style' => 'margin-left:30px;',
                    'class' => 'btn btn-success',
                ]
            ]);

        return $form;
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
        return $this->getBORoutePrefix() . $routeIdentifier;
    }

}
