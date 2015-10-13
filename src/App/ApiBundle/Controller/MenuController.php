<?php

/**
 * @author      :     aat <aat@norse.digital>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date        : 07 07 2015
 */
namespace App\ApiBundle\Controller;

use App\CoreBundle\Entity\Menu;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MenuController
 * @package App\ApiBundle\Controller
 */
class MenuController extends FOSRestController
{
    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->container->get('app_core.menu.handler');
    }

    /**
     * Get menu item.
     *
     * @ApiDoc(
     *   section="Menu",
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when not found menu item"
     *   },
     *   requirements={
     *     {"name"="id", "dataType"="integer", "requirement"="\d+", "description"="menu id"}
     *   }
     * )
     *
     * @Annotations\Get("/menu/{id}")
     * @Annotations\View()
     */
    public function getMenuAction($id)
    {
        $response = $this->getService()->Menu($id);
        $view = $this->view($response['data'], $response['errorCode'])->setTemplate('AppApiBundle:Menu:menu.html.twig');

        return $this->handleView($view);
    }

    /**
     * Get menu items.
     *
     * @ApiDoc(
     *   section="Menu",
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     * @Annotations\Get("/menu")
     * @Annotations\View()
     */
    public function getMenuAllAction()
    {
        $response = $this->getService()->menu();
        $view = $this->view($response['data'], $response['errorCode'])
            ->setTemplate('AppApiBundle:Menu:menu.html.twig');

        return $this->handleView($view);
    }

    /**
     * Create menu item.
     *
     * @ApiDoc(
     *   section="Menu",
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   parameters={
     *      {"name"="name", "dataType"="string", "required"=true, "description"="menu name"},
     *      {"name"="priority", "dataType"="integer", "required"=true, "description"="menu priority"},
     *      {"name"="status", "dataType"="string", "required"=true, "description"="menu status ('visible' || 'hidden')"},
     *      {"name"="hideForSearchEngines", "dataType"="integer", "required"=true, "description"="menu hide for search engines (0 || 1)"}
     *   }
     * )
     * @Annotations\Post("/menu")
     * @Annotations\View()
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postMenuAction(Request $request)
    {
        $response = $this->getService()->save($request);
        $view = $this->view($response['data'], $response['errorCode'])
            ->setTemplate('AppApiBundle:Menu:menu.html.twig');

        return $this->handleView($view);
    }

    /**
     * Edit menu item.
     *
     * @ApiDoc(
     *   section="Menu",
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   requirements={
     *     {"name"="id", "dataType"="integer", "requirement"="\d+", "description"="menu id"}
     *   },
     *   parameters={
     *      {"name"="name", "dataType"="string", "required"=true, "description"="menu name"},
     *      {"name"="priority", "dataType"="integer", "required"=true, "description"="menu priority"},
     *      {"name"="status", "dataType"="string", "required"=true, "description"="menu status ('visible' || 'hidden')"},
     *      {"name"="hideForSearchEngines", "dataType"="integer", "required"=true, "description"="menu hide for search engines (0 || 1)"}
     *   }
     * )
     * @Annotations\Put("/menu/{id}")
     * @Annotations\View()
     * @param Request $request
     * @param Menu $menu
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function putMenuAction(Request $request, Menu $menu)
    {
        $response = $this->getService()->save($request, $menu);
        $view = $this->view($response['data'], $response['errorCode'])
            ->setTemplate('AppApiBundle:Menu:menu.html.twig');

        return $this->handleView($view);
    }

    /**
     * Delete menu item.
     *
     * @ApiDoc(
     *   section="Menu",
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful"
     *   },
     *   requirements={
     *     {"name"="id", "dataType"="integer", "requirement"="\d+", "description"="menu id"}
     *   }
     * )
     * @Annotations\Delete("/menu/{id}")
     * @Annotations\View()
     * @param Menu $menu
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteMenuAction(Menu $menu)
    {
        $response = $this->getService()->delete($menu);
        $view = $this->view($response['data'], $response['errorCode'])
            ->setTemplate('AppApiBundle:Menu:menu.html.twig');

        return $this->handleView($view);
    }

    /**
     * Add product to menu.
     *
     * @ApiDoc(
     *   section="Menu",
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   requirements={
     *     {"name"="menu_id", "dataType"="integer", "requirement"="\d+", "description"="menu id"},
     *     {"name"="product_id", "dataType"="integer", "requirement"="\d+", "description"="product id"}
     *   }
     * )
     * @Annotations\Post("/menu/{menu_id}/product/{product_id}")
     * @Annotations\View()
     * @param $menu_id
     * @param $product_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postProductToMenuAction($menu_id, $product_id)
    {
        $response = $this->getService()->addProduct($menu_id, $product_id);
        $view = $this->view($response['data'], $response['errorCode'])
            ->setTemplate('AppApiBundle:Menu:menu.html.twig');

        return $this->handleView($view);
    }

    /**
     * Delete product from menu.
     *
     * @ApiDoc(
     *   section="Menu",
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   requirements={
     *     {"name"="menu_id", "dataType"="integer", "requirement"="\d+", "description"="menu id"},
     *     {"name"="product_id", "dataType"="integer", "requirement"="\d+", "description"="product id"}
     *   }
     * )
     * @Annotations\Delete("/menu/{menu_id}/product/{product_id}")
     * @Annotations\View()
     * @param $menu_id
     * @param $product_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteProductFromMenuAction($menu_id, $product_id)
    {
        $response = $this->getService()->removeProduct($menu_id, $product_id);
        $view = $this->view($response['data'], $response['errorCode'])
            ->setTemplate('AppApiBundle:Menu:menu.html.twig');

        return $this->handleView($view);
    }
}
