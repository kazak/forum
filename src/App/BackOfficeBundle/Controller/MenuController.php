<?php

/**
 * @author:     aat <aat@norse.digital>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 14 07 2015
 */
namespace App\BackOfficeBundle\Controller;

use App\CoreBundle\Entity\Menu;
use App\CoreBundle\Model\Controller\EntityController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class MenuController
 * @package App\BackOfficeBundle\Controller
 */
class MenuController extends EntityController
{
    /**
     * @var string
     */
    private $role = 'ROLE_FROM_MARKET';

    /**
     * @return mixed
     */
    public function getHandler()
    {
        return $this->container->get('app_core.menu.handler');
    }

    /**
     * @Template()
     *
     * @param Request $request
     *
     * @return array|mixed|RedirectResponse
     */
    public function indexAction(Request $request)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        $response = $this->getHandler()->menu();

        return $this->render(
            'AppBackOfficeBundle:Menu:index.html.twig',
            ['menus' => $response['data']]
        );
    }

    /**
     * @param Request $request
     * @param $id
     * @param $page
     *
     * @return RedirectResponse|Response
     */
    public function productsNotInMenuAction(Request $request, $id, $page)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        $post = $request->request->all();
        $criteria = [];
        if (isset($post['query']) && strlen($post['query']) > 1) {
            $criteria = ['search' => $post['query']];
            $productsCount = 0;
        } else {
            $productsCount = $this->getHandler()->productsNotInMenuCount($id);
        }
        $response = $this->getHandler()->productsNotInMenu($id, $page, $criteria);
        $pageCount = ceil($productsCount / 10);

        return $this->render(
            'AppBackOfficeBundle:Menu:products.html.twig',
            ['products' => $response['data'],
                'page' => $page,
                'menu' => null,
                'pageCount' => $pageCount,
                'searchVal' => isset($post['query']) ? $post['query'] : null,
            ]
        );
    }

    /**
     * @param $id
     *
     * @return RedirectResponse|Response
     */
    public function menuProductsAction($id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }

        /**
         * @var $menu Menu
         */
        $menu = $this->getHandler()->getEntity($id);
        $products = $menu->getProducts();

        return $this->render(
            'AppBackOfficeBundle:Menu:products.html.twig',
            ['menu' => $menu, 'products' => $products]
        );
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse|RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }

        $menu = $this->getHandler()->getEntity($id);
        $data = $this->getHandler()->delete($menu);
        $response = new JsonResponse($data);

        return $response;
    }

    /**
     * @param $menu_id
     * @param $product_id
     *
     * @return RedirectResponse|Response
     */
    public function addProductAction($menu_id, $product_id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }

        $data = $this->getHandler()->addProduct($menu_id, $product_id);
        $response = new JsonResponse($data);

        return $response;
    }

    /**
     * @param $menu_id
     * @param $product_id
     *
     * @return RedirectResponse
     */
    public function removeProductAction($menu_id, $product_id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        $data = $this->getHandler()->removeProduct($menu_id, $product_id);
        $response = new JsonResponse($data);

        return $response;
    }

    /**
     * @param $menu_id
     * @param $product_id
     *
     * @return RedirectResponse|Response
     */
    public function upProductPriorityAction($menu_id, $product_id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        $data = $this->getHandler()->upMenuProductPriority($menu_id, $product_id);
        $response = new JsonResponse($data);

        return $response;
    }

    /**
     * @param $menu_id
     * @param $product_id
     *
     * @return RedirectResponse|Response
     */
    public function downProductPriorityAction($menu_id, $product_id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        $data = $this->getHandler()->downMenuProductPriority($menu_id, $product_id);
        $response = new JsonResponse($data);

        return $response;
    }

    /**
     * @Template()
     *
     * @param Request $request
     *
     * @return array|mixed|RedirectResponse
     */
    public function createAction(Request $request)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        return parent::createAction($request);
    }

    /**
     * @Template()
     *
     * @param Request $request
     * @param $id
     *
     * @return array|mixed|RedirectResponse
     */
    public function updateAction(Request $request, $id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        return parent::updateAction($request, $id);
    }

}
