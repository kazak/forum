<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 29.09.15
 * Time: 14:13
 */
namespace App\BackOfficeBundle\Controller;

use App\CoreBundle\Model\Controller\EntityController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class HelpController
 * @package App\BackOfficeBundle\Controller
 */
class HelpController extends EntityController
{
    /**
     * @var string
     */
    private $role = 'ROLE_FROM_ALL_ADMIN';

    /**
     * @return \App\BackOfficeBundle\Handler\HelpHandler
     */
    public function getHandler()
    {
        return $this->container->get('app_back.help.handler');
    }

    /**
     * @Template()
     *
     * @param Request $request
     *
     * @param $id
     * @return array|mixed|RedirectResponse
     */
    public function createHelpAction(Request $request, $id)
    {
        if (!$this->permission(['ROLE_ADMIN'])) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        return $this->getHandler()->CreateAction($request, $id);
    }

    /**
     * @Template()
     *
     * @param Request $request
     * @return array|mixed|RedirectResponse
     */
    public function indexAction(Request $request)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        $help = $this->getHandler()->getOneHelp();

        return $this->render(
            'AppBackOfficeBundle:Help:index.html.twig',
            ['help' => $help]);
    }

    /**
     * @Template()
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse|Response
     */
    public function showAction(Request $request, $id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        $help = $this->getHandler()->getEntity($id);

        return $this->render(
            'AppBackOfficeBundle:Help:index.html.twig',
            ['help' => $help]);
    }


    /**
     * @param Request $request
     * @return Response
     */
    public function addAction(Request $request)
    {
        if (!$this->permission(['ROLE_ADMIN'])) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        $menu = $this->getHandler()->getMenu();
        return $this->render(
            'AppBackOfficeBundle:Help:add.html.twig',
            ['menu' => $menu]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function newMenuAction(Request $request)
    {
        if (!$this->permission(['ROLE_ADMIN'])) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        $this->getHandler()->processCreateMenuAction($request);

        return $this->redirectToRoute('app_back_office_help_add');
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function updateMenuAction(Request $request, $id)
    {
        if (!$this->permission(['ROLE_ADMIN'])) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        $this->getHandler()->processUpdateMenuAction($request, $id);

        return $this->redirectToRoute('app_back_office_help_add');
    }


}