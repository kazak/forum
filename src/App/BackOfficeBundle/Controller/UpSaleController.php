<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 14.09.15
 * Time: 14:13
 */

namespace App\BackOfficeBundle\Controller;

use App\CoreBundle\Model\Controller\EntityController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class UpSaleController
 * @package App\BackOfficeBundle\Controller
 */
class UpSaleController extends EntityController
{
    /**
     * @var string
     */
    private $role = 'ROLE_FROM_MARKET';

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
            return $this->redirectToRoute('fos_user_security_login');
        }

        $group = [];
        foreach($this->getHandler()->getGroups() as $grp){
            $group[$grp->getId()]=$grp;
        }

        $priorityGroup = $this->getHandler()->getGroupByPriority();
        return $this->render(
            'AppBackOfficeBundle:BackOffice:up_sale.html.twig',
            ['priority_group' => $priorityGroup,
                'group' => $group]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function addGroupAction(Request $request)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        $this->getHandler()->addGroupPriority($request);

        return $this->redirectToRoute('app_back_office_up_sale_index');
    }

    /**
     * TODO: now
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function addProductAction(Request $request, $id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        $this->getHandler()->addProducts($request, $id);

        return $this->redirectToRoute('app_back_priority_product_index',['id'=>$id]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function productIndexAction(Request $request, $id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        $productsPriority = $this->getHandler()->getProductsPriority($id);
        $products = $this->getHandler()->getProducts($id);

         return $this->render(
            'AppBackOfficeBundle:BackOffice:up_sale_product.html.twig',
            ['priority_products' => $productsPriority,
            'products' => $products]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function changeGroupAction(Request $request)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        $this->getHandler()->changeGroupPriority($request);
        return $this->redirectToRoute('app_back_office_up_sale_index');
    }

    /**
     * @param $id
     * @return bool|RedirectResponse
     */
    public function removeGroupAction(Request $request, $id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        $this->getHandler()->removeGroup($id);

        return $this->redirectToRoute('app_back_office_up_sale_index');
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function removeProductAction($id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        $group_id = $this->getHandler()->removeProduct($id);

        return $this->redirectToRoute('app_back_priority_product_index',['id'=>$group_id]);
    }

    /**
     * TODO: this
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function changeProductAction(Request $request, $id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        $this->getHandler()->changeProduct($request);
        return $this->redirectToRoute('app_back_priority_product_index',['id'=>$id]);
    }

    /**
     * @return \App\OpenSolutionBundle\Handler\PriorityUpSaleHandler
     */
    public function getHandler()
    {
        return $this->container->get('app_open_solution.up_sale.handler');
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
    public function updateAction(Request $request, $id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        return parent::updateAction($request, $id);
    }

    /**
     * @Template()
     *
     * @param Request $request
     * @param $id
     *
     * @return array|mixed|RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        return parent::deleteAction($request, $id);

    }
}