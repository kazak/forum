<?php

/**
 * @author      :     aat <aat@norse.digital>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date        : 10 07 2015
 */
namespace App\CoreBundle\Handler;

use App\CoreBundle\Entity\Menu;
use App\CoreBundle\Entity\MenuProducts;
use App\CoreBundle\Form\MenuType;
use App\CoreBundle\Form\SeoType;
use App\CoreBundle\Model\Handler\EntityCrudHandler;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class MenuHandler
 * @package App\CoreBundle\Handler
 */
class MenuHandler extends EntityCrudHandler
{
    use ContainerAwareTrait;

    /**
     * access Admin.
     *
     * @var bool
     */
    private $access = false;

    const ROUTE_PREFIX_BO = 'app_back_office_menu_';

    /**
     * @param Container $container
     * @param $entityClass
     */
    public function __construct(Container $container, $entityClass)
    {
        parent::__construct($container, $entityClass, null);
        $this->setContainer($container);
        $this->access = $this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN');
    }

    /**
     * @param $id
     *
     * @return null|Menu
     */
    public function getEntity($id)
    {
        return parent::getEntity($id);
    }

    /**
     * Fetch menu|menuList.
     *
     * @param null|int $id
     *
     * @return array
     */
    public function menu($id = null)
    {
        if (is_null($id)) {
            $menu = $this->repository->getMenuList();
        } else {
            $menu = $this->repository->getMenu($id);
        }

        return $this->getResponse($menu, 'Menu');
    }

    /**
     * Fetch menu|menuList with products.
     *
     * @param null|int $id
     *
     * @return array
     */
    public function menuWithProducts($id = null)
    {
        if (is_null($id)) {
            $menu = $this->repository->findAll();
        } else {
            $menu = $this->getEntity($id);
        }

        return $this->getResponse($menu, 'Menu');
    }

    /**
     * Fetch products not this menu.
     *
     * @param int $id
     * @param int $page
     * @param array|null $filter
     *
     * @return array
     */
    public function productsNotInMenu($id, $page = 1, $filter = null)
    {
        $menuProductIds = $this->getProductIdsByMenu($id);
        $limit = 10;
        $offset = $limit * ($page - 1);
        $productRepo = $this->objectManager->getRepository('AppCoreBundle:Product');

        if (isset($filter['search'])) {
            $products = $productRepo->createQueryBuilder('p')
                ->where('pt.shortDescription LIKE :search')
                ->orWhere('pt.description LIKE :search')
                ->orWhere('pt.number LIKE :search')
                ->orWhere('pt.name LIKE :search')
                ->andWhere('p.id NOT IN (:ids)')
                ->leftJoin('p.translations', 'pt')
                ->setParameter('ids', $menuProductIds)
                ->setParameter('search', '%' . $filter['search'] . '%')
                ->getQuery()->getResult();
        } else {
            $products = $productRepo
                ->findBy([
                    'id NOT' => $menuProductIds
                ], null, $limit, $offset);
        }

        return $this->getResponse($products, 'Product');
    }

    /**
     * @param $id
     * @param array|null $filter
     * @return int
     */
    public function productsNotInMenuCount($id, $filter = null)
    {
        $menuProductIds = $this->getProductIdsByMenu($id);
        $productRepo = $this->objectManager->getRepository('AppCoreBundle:Product');

        if (isset($filter['search'])) {
            $products = $productRepo->createQueryBuilder('p')
                ->select('COUNT(p.id)')
                ->where('pt.shortDescription LIKE :search')
                ->orWhere('pt.description LIKE :search')
                ->orWhere('pt.number LIKE :search')
                ->orWhere('pt.name LIKE :search')
                ->andWhere('p.id NOT IN (:ids)')
                ->leftJoin('p.translations', 'pt')
                ->setParameter('ids', $menuProductIds)
                ->setParameter('search', '%' . $filter['search'] . '%')
                ->getQuery()->getResult();
            return isset($products[0][1]) ? $products[0][1] : 0;

        } else {
            $products = $this->objectManager->getRepository('AppCoreBundle:Product')
                ->findBy(['id NOT' => $menuProductIds]);
        }

        return count($products);
    }

    /**
     * @param Request $request
     * @param Menu|null $entity
     *
     * @return array
     */
    public function save(Request $request, Menu $entity = null)
    {
        if (!$this->access) {
            return $this->getDenied();
        }

        $menu = $entity ?: new Menu();
        $form = $this->container->get('form.factory')
            ->create(new MenuType(), $menu, [
                'method' => $request->getMethod(),
            ]);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $this->objectManager->persist($menu);
            $this->objectManager->flush();

            return $this->getResponse($menu);
        } else {
            return $this->getResponse($form, null, '400');
        }
    }

    /**
     * @param Menu $menu
     *
     * @return array
     */
    public function delete(Menu $menu)
    {
        if (!$this->access) {
            return $this->getDenied();
        }
        if ($menu) {
            $this->objectManager->remove($menu);
            $this->objectManager->flush();

            return $this->getResponse($menu, null, 204);
        } else {
            return $this->getResponse(null, 'Menu');
        }
    }

    /**
     * @param  $menu_id
     * @param  $product_id
     *
     * @return array
     */
    public function addProduct($menu_id, $product_id)
    {
        if (!$this->access) {
            return $this->getDenied();
        }
        $menu = $this->getEntity($menu_id);
        $product = $this->objectManager->getRepository('AppCoreBundle:Product')->find($product_id);

        if ($menu && $product) {
            $maxPriority = $this->objectManager
                ->getRepository('AppCoreBundle:MenuProducts')
                ->getMaxPriority($menu_id);
            $menu->addProduct($product, $maxPriority + 1);

            $this->objectManager->flush();

            return $this->getResponse($menu);
        } else {
            return $this->getResponse(null, 'Menu');
        }
    }

    /**
     * @param  $menu_id
     * @param  $product_id
     *
     * @return array
     */
    public function removeProduct($menu_id, $product_id)
    {
        if (!$this->access) {
            return $this->getDenied();
        }

        $menu = $this->getEntity($menu_id);
        $product = $this->objectManager->getRepository('AppCoreBundle:Product')->find($product_id);

        if ($menu) {
            $menu->removeProduct($product);
            $this->objectManager->flush();

            return $this->getResponse($menu);
        } else {
            return $this->getResponse(null, 'Product in menu');
        }
    }

    /**
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    public function processCreateAction(Request $request)
    {
        $menu = new Menu();
        $form = $this->getCreateUpdateForm($menu);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->objectManager->persist($menu);
            $this->objectManager->flush();

            return $this->redirectToRoute($this->getBORoute('index'));
        }

        return [
            'menu' => $menu,
            'form' => $form->createView(),
        ];
    }

    /**
     * @param Request $request
     * @param Menu $id
     *
     * @return array|RedirectResponse
     */
    public function processUpdateAction(Request $request, $id)
    {
        $menu = $this->getEntity($id);

        if (!$menu) {
            return $this->redirectToRoute($this->getBORoute('index'));
        }

        $form = $this->getCreateUpdateForm($menu);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->objectManager->merge($menu);
            $this->objectManager->flush();

            return $this->redirectToRoute($this->getBORoute('index'));
        }

        return [
            'menu' => $menu,
            'form' => $form->createView(),
        ];
    }

    /**
     * @param $menu_id
     * @param $product_id
     *
     * @return array
     */
    public function downMenuProductPriority($menu_id, $product_id)
    {
        /**
         * @var Menu $menu
         * @var MenuProducts $currentMenuProducts
         */
        $menu = $this->getEntity($menu_id);

        if ($menu == null) {
            return $this->getResponse(null, 'Menu');
        }

        $this->setRightMenuProductPriority($menu);
        $menuProducts = $menu->getMenuProducts();
        $currentMenuProducts = $menuProducts->get($product_id);
        $currentPriority = $currentMenuProducts ?
            (int)$currentMenuProducts->getPriority() :
            null;

        $maxPriority = $this->objectManager
            ->getRepository('AppCoreBundle:MenuProducts')
            ->getMaxPriority($menu_id);

        if ($maxPriority == $currentPriority) {
            return $this->getResponse(null, 'Menu');
        }

        $upMenu = $this->objectManager
            ->getRepository('AppCoreBundle:MenuProducts')
            ->findOneBy([
                'menu' => $menu->getId(),
                'priority' => $currentPriority + 1
            ]);

        if ($upMenu) {
            $upMenu->setPriority($currentPriority);
        }

        $currentMenuProducts->setPriority($currentPriority + 1);
        $this->objectManager->flush();

        return $this->getResponse($menu, null, 200);
    }

    /**
     * @param $menu_id
     * @param $product_id
     *
     * @return array
     */
    public function upMenuProductPriority($menu_id, $product_id)
    {
        /**
         * @var Menu $menu
         * @var MenuProducts $currentMenuProducts
         */
        $menu = $this->getEntity($menu_id);

        if ($menu == null) {
            return $this->getResponse(null, 'Menu');
        }

        $this->setRightMenuProductPriority($menu);
        $menuProducts = $menu->getMenuProducts();
        $currentMenuProducts = $menuProducts->get($product_id);
        $currentPriority = $currentMenuProducts ?
            (int)$currentMenuProducts->getPriority() :
            0;

        if ($currentPriority == 0 || $currentPriority == 1) {
            return $this->getResponse(null, 'Menu');
        }

        $downMenu = $this->objectManager
            ->getRepository('AppCoreBundle:MenuProducts')
            ->findOneBy([
                'menu' => $menu->getId(),
                'priority' => $currentPriority - 1
            ]);

        if ($downMenu) {
            $downMenu->setPriority($currentPriority);
            $currentMenuProducts->setPriority($currentPriority - 1);
        } else {
            $currentMenuProducts->setPriority($currentPriority - 1);
        }

        $this->objectManager->flush();

        return $this->getResponse($menu, null, 200);
    }


    /**
     * @param Request $request
     * @param string $slug
     *
     * @return array
     *
     * @throws NotFoundHttpException
     * @throws AccessDeniedException
     */
    public function processShowBySlugAction(Request $request, $slug)
    {
        $repository = $this->objectManager->getRepository('AppCoreBundle:Menu');
        $entity = $repository->findOneBy(['slug' => $slug]);

        if (!$entity) {
            throw new NotFoundHttpException('Entity not found', null);
        }

//        if (!$entity->getStatus()) {
//            throw new AccessDeniedException('Entity is not accessible', null);
//        }

        return [
            'entity' => $entity,
            'navigation' => $repository->getMenuList(),
        ];
    }

    /**
     * @param Menu $entity
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    public function getCreateUpdateForm($entity)
    {
        $form = $this->container->get('form.factory')
            ->create(new MenuType(), $entity)
            ->add('seo', new SeoType($this->container), [
                'label' => 'SEO',
            ])
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
     * @return array
     */
    private function getDenied()
    {
        $errorCode = 401;
        $response = [
            'data' => 'Access denied',
            'errorCode' => $errorCode,
        ];

        return $response;
    }

    /**
     * @param $id
     * @return array
     */
    private function getProductIdsByMenu($id)
    {
        $menu = $this->getEntity($id);
        $menuProductIds = [];

        foreach ($menu->getProducts() as $product) {
            if ($product) {
                $menuProductIds[] = $product->getId();
            }
        }
        if (empty($menuProductIds)) {
            return [0];
        }

        return $menuProductIds;
    }

    /**
     * @param Menu $menu
     */
    private function setRightMenuProductPriority(Menu $menu)
    {
        $menuProducts = $menu->getMenuProducts();
        $minPriority = 1;

        foreach ($menuProducts as $menuProductsItem) {
            $menuProductsItem->setPriority($minPriority);
            $minPriority++;
        }
        $this->objectManager->flush();
    }
}
