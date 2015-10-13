<?php

/**
 * @author      :     aat <aat@norse.digital>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date        : 24 08 2015
 */
namespace App\CoreBundle\Handler;

use App\CoreBundle\Entity\FrontPage;
use App\CoreBundle\Entity\FrontPageBlock;
use App\CoreBundle\Form\FrontPageBlockType;
use App\CoreBundle\Model\Handler\EntityCrudHandler;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FrontPageBlockHandler.
 */
class FrontPageBlockHandler extends EntityCrudHandler
{
    use ContainerAwareTrait;

    const ROUTE_PREFIX_BO = 'app_back_office_front_page_';
    const FORM_TABLE_NAME = 'app_front_page_block_type';
    const FORM_FIELD_IMG_NAMES = ['main_image', 'secondary_image'];
    const EXTENSION_PATH_TO_FILES = 'frontPageBlock/';

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
     * @return null|FrontPageBlock
     */
    public function getEntity($id)
    {
        return parent::getEntity($id);
    }

    /**
     * @param array $options
     * @return null|FrontPageBlock
     */
    public function getEntitys(array $options = [])
    {
        return parent::getEntities($options);
    }


    /**
     * @param $id
     *
     * @return array
     */
    public function delete($id)
    {
        $block = $this->getEntity($id);
        if ($block) {
            $this->objectManager->remove($block);
            $this->objectManager->flush();

            return $this->getResponse($block, null, 204);
        } else {
            return $this->getResponse(null, 'FrontPageBlock');
        }
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function downCurentBlockPriority($id)
    {
        /**
         * @var FrontPageBlock $block
         * @var FrontPageBlock $upBlock
         */
        $block = $this->getEntity($id);
        $currentPriority = $block ? (int)$block->getPriority() : null;

        if ($currentPriority != null) {
            $frontPage = $block->getFrontPage();
            $this->setRightBlocksPriority($frontPage);
            $pageNodeId = $frontPage->getId();
            $maxPriority = $this->repository->getMaxPriority($pageNodeId);

            if ($maxPriority == $currentPriority) {
                return $this->getResponse(null, 'FrontPage');
            }
            $upBlock = $this->repository->findOneBy([
                'frontPage' => $pageNodeId,
                'priority' => $currentPriority + 1
            ]);

            if ($upBlock) {
                $upBlock->setPriority($currentPriority);
            }

            $block->setPriority($currentPriority + 1);
            $this->objectManager->flush();

            return $this->getResponse($block, null, 200);
        } else {
            return $this->getResponse(null, 'FrontPage');
        }
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function upCurentBlockPriority($id)
    {
        /**
         * @var FrontPageBlock $block
         * @var FrontPageBlock $downBlock
         */
        $block = $this->getEntity($id);
        $currentPriority = $block ? (int)$block->getPriority() : 0;

        if ($currentPriority != 0 && !$currentPriority != 1) {
            $frontPage = $block->getFrontPage();
            $this->setRightBlocksPriority($frontPage);
            $downBlock = $this->repository->findOneBy([
                'frontPage' => $frontPage->getId(),
                'priority' => $currentPriority - 1
            ]);

            if ($downBlock) {
                $downBlock->setPriority($currentPriority);
                $block->setPriority($currentPriority - 1);
            } else {
                $block->setPriority($currentPriority - 1);
            }
            $this->objectManager->flush();

            return $this->getResponse($block, null, 200);

        } else {
            return $this->getResponse(null, 'FrontPageBlock');
        }
    }


    /**
     * @param Request $request
     * @param FrontPage $pageNode
     *
     * @return array|RedirectResponse
     */
    public function processCreateForPageAction(Request $request, FrontPage $pageNode)
    {
        $imageHandler = $this->container->get('app_core.image.handler');
        $post = $request->request->all()[self::FORM_TABLE_NAME];
        $images = $imageHandler->getImagesFromRequest($request, $this);
        $block = new FrontPageBlock();

        $block->setFrontPage($pageNode);

        $form = $this->getCreateUpdateForm($block);

        if ($post !== null && $images !== null) {
            $post['priority'] = ((int)$this->repository->getMaxPriority($pageNode)) + 1;
            $form->submit($post);

            if ($form->isValid()) {
                $pageNode->addBlock($block);
                $this->setImages($block, $images);
                $this->objectManager->persist($block);
                $this->objectManager->persist($pageNode);
                $this->objectManager->flush();

                return $this->redirectToRoute($this->getBORoute('show'),
                    ['id' => $pageNode->getId()]
                );
            }
        }
        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param Request $request
     * @param FrontPageBlock $id
     *
     * @return array|RedirectResponse
     */
    public function processUpdateAction(Request $request, $id)
    {
        $block = $this->getEntity($id);

        if (!$block) {
            return $this->redirectToRoute($this->getBORoute('index'));
        }

        $imageHandler = $this->container->get('app_core.image.handler');
        $post = $request->request->all()[self::FORM_TABLE_NAME];
        $images = $imageHandler->getImagesFromRequest($request, $this);
        $form = $this->getCreateUpdateForm($block);

        if ($post != null) {
            $currentPriority = $block->getPriority();
            $form->submit($post);

            if ($form->isValid()) {
                $this->setImages($block, $images);
                $block->setPriority($currentPriority);
                $this->objectManager->merge($block);
                $this->objectManager->flush();

                return $this->redirectToRoute($this->getBORoute('show'), [
                    'id' => $block->getFrontPage()->getId()
                ]);
            }
        }
        return [
            'pageNode' => $block->getFrontPage(),
            'form' => $form->createView(),
        ];
    }


    /**
     * @param FrontPageBlock $entity
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    public function getCreateUpdateForm($entity)
    {
        $form = $this->container->get('form.factory')
            ->create(new FrontPageBlockType($entity), $entity)
            ->add('submit', 'submit', ['label' => 'Save']);

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

    /**
     * @param FrontPageBlock $block
     * @param array $images
     */
    protected function setImages(FrontPageBlock &$block, array $images = null)
    {
        $block->setMainImage(
            isset($images['main_image']) ? $images['main_image'] : null
        );
        $block->setSecondaryImage(
            isset($images['secondary_image']) ? $images['secondary_image'] : null
        );
    }

    /**
     * @param FrontPage $entity
     */
    private function setRightBlocksPriority(FrontPage $entity)
    {
        $blocks = $entity->getBlocks();
        $minPriority = 1;

        foreach ($blocks as $block) {
            $block->setPriority($minPriority);
            $minPriority++;
        }

        $this->objectManager->flush();
    }

}
