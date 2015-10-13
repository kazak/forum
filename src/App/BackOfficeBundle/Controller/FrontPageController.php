<?php

/**
 * @author:     aat <aat@norse.digital>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 24 08 2015
 */
namespace App\BackOfficeBundle\Controller;

use App\CoreBundle\Model\Controller\EntityController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class FrontPageController
 * @package App\BackOfficeBundle\Controller
 */
class FrontPageController extends EntityController
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
        return $this->container->get('app_core.front_page.handler');
    }

    /**
     * @return mixed
     */
    public function getBlockHandler()
    {
        return $this->container->get('app_core.front_page_block.handler');
    }

    /**
     * @param $id
     *
     * @return RedirectResponse|Response
     */
    public function setDefaultAction($id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        $pageNode = $this->getHandler()->getEntity($id);
        $data = $this->getHandler()->setDefault($pageNode);
        $response = new JsonResponse($data);

        return $response;
    }

    /**
     * @param $id
     *
     * @return RedirectResponse|Response
     */
    public function removeHeroAction($id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        $data = $this->getHandler()->removeHeroImage($id);
        $response = new JsonResponse($data);

        return $response;
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return RedirectResponse|Response
     */
    public function creatBlockAction(Request $request, $id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        $pageNode = $this->getHandler()->getEntity($id);
        $data = $this->getBlockHandler()->processCreateForPageAction($request, $pageNode);

        if ($data instanceof RedirectResponse) {
            return $data;
        }
        return $this->render(
            'AppBackOfficeBundle:FrontPage:create_block.html.twig',
            ['form' => $data['form'],
                'pageNode' => $pageNode,
            ]
        );
    }

    /**
     * @param $id
     *
     * @return RedirectResponse|Response
     */
    public function copyAction($id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        return $this->getHandler()->processCopyAction($id);
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return RedirectResponse|Response
     */
    public function updateBlockAction(Request $request, $id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        $data = $this->getBlockHandler()->processUpdateAction($request, $id);

        if ($data instanceof RedirectResponse) {
            return $data;
        }
        return $this->render(
            'AppBackOfficeBundle:FrontPage:update_block.html.twig',
            [
                'form' => $data['form'],
                'pageNode' => $data['pageNode']
            ]
        );
    }

    /**
     * @param $id
     *
     * @return RedirectResponse|Response
     */
    public function previewAction($id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        $pageNode = $this->getHandler()->getEntity($id);
        $classIdent = $this->container->getParameter('app_core.classes_identifier');

        return $this->render(
            'AppDollyBundle:Dolly:index.html.twig',
            [
                'pageNode' => $pageNode,
                'classIdent' => $classIdent
            ]
        );
    }

    /**
     * @param $id
     *
     * @return RedirectResponse|Response
     */
    public function deleteBlockAction($id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        $data = $this->getBlockHandler()->delete($id);

        if ($data instanceof RedirectResponse) {
            return $data;
        }
        $response = new JsonResponse($data);

        return $response;
    }

    /**
     * @param $id
     * @param $type
     *
     * @return RedirectResponse|Response
     */
    public function deletePageAction($id, $type)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        $data = $this->getHandler()->delete($id, $type);

        if ($data instanceof RedirectResponse) {
            return $data;
        }
        $response = new JsonResponse($data);

        return $response;
    }

    /**
     * @param $id
     *
     * @return RedirectResponse|Response
     */
    public function upBlockPriorityAction($id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        $data = $this->getBlockHandler()->upCurentBlockPriority($id);
        $response = new JsonResponse($data);

        return $response;
    }

    /**
     * @param $id
     *
     * @return RedirectResponse|Response
     */
    public function downBlockPriorityAction($id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        $data = $this->getBlockHandler()->downCurentBlockPriority($id);
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
    public function indexAction(Request $request)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }

        return parent::indexAction($request);
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
    public function showAction(Request $request, $id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }

        return parent::showAction($request, $id);
    }

    /**
     * @Template()
     *
     * @param Request $request
     * @param $id
     *
     * @return array|mixed|RedirectResponse
     */
    public function updateAction(Request $request, $id, $copy = false)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }

        return $this->getHandler()->processUpdateAction($request, $id, $copy);
    }

}
