<?php

namespace App\DollyBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DollyController
 * @package App\DollyBundle\Controller
 */
class DollyController extends DefaultController
{
    /**
     * @Template()
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function indexAction(Request $request)
    {
        $classIdent = $this->container->getParameter('app_core.classes_identifier');
        $pageNodeHandler = $this->container->get('app_core.front_page.handler');
        $pageNode = $pageNodeHandler->getCurrentFrontPage();

        return [
            'pageNode' => $pageNode,
            'classIdent' => $classIdent
        ];
    }

    /**
     * @Template()
     *
     * @param Request $request
     *
     * @return Response
     */
    public function footerAction(Request $request)
    {
        $settingsHandler = $this->get('app_core.settings.handler');
        $contentHandler = $this->get('app_core.content_page.handler');

        $data = $settingsHandler->getByCode('footer_content');

        //get don't visible Content page
        $dontShowContents = $contentHandler->getEntities([
            'visible' => 0
        ]);
        //transform Content page slug to link
        $dontShowContentLinks = [];

        foreach ($dontShowContents as $dontShowContent) {
            $dontShowContentLinks[] = '/' . $dontShowContent->getSlug();
        }

        return [
            'data' => $data,
            'user' => $this->getUser(),
            'heidLinks' => $dontShowContentLinks
        ];
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * todo Orders view
     */
    public function myOrdersAction(Request $request)
    {
        $entity = $this->getUser();

        if(is_null($entity)){

           return $this->redirectToRoute('dolly_homepage');

        }

        $customerHandler = $this->container->get('app_core.customer.handler');
        $responce = $customerHandler->processEditAction($request, $entity);
        $tpl = 'AppDollyBundle:Dolly:myOrders.html.twig';

        return $this->render($tpl, [
            'entity' => $entity,
            'form' => $responce['form']->createView(),
            'homeAddress' => $entity->getAddresses()->get('home'),
            'workAddress' => $entity->getAddresses()->get('work'),
            'otherAddress' => $entity->getAddresses()->get('other')
        ]);

    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * todo bonus history
     */
    public function myBonusHistoryAction(Request $request)
    {
        $entity = $this->getUser();

        if(is_null($entity)){

            return $this->redirectToRoute('dolly_homepage');

        }

        $customerHandler = $this->container->get('app_core.customer.handler');
        $responce = $customerHandler->processEditAction($request, $entity);
        $tpl = 'AppDollyBundle:Dolly:myOrders.html.twig';

        return $this->render($tpl, [
            'entity' => $entity,
            'form' => $responce['form']->createView(),
            'homeAddress' => $entity->getAddresses()->get('home'),
            'workAddress' => $entity->getAddresses()->get('work'),
            'otherAddress' => $entity->getAddresses()->get('other')
        ]);
    }

    /**
     * @Template()
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function complaintsAction(Request $request)
    {
        $entity = $this->getUser();

        if(is_null($entity)){

            return $this->redirectToRoute('dolly_homepage');

        }

        $serviceComplaints = $this->get('app_core.complaints.service');
        $reasons = $serviceComplaints->getReasonsStructure();
        $complaints = '';

        return [
            'reasons' => $reasons,
            'complaints' => $complaints
        ];
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function myProfileAction(Request $request)
    {
        $entity = $this->getUser();

        if(is_null($entity)){

           return $this->redirectToRoute('dolly_homepage');

        }

        $customerHandler = $this->container->get('app_core.customer.handler');
        $responce = $customerHandler->processEditAction($request, $entity);

        $tpl = 'AppDollyBundle:Dolly:myProfile.html.twig';

        return $this->render($tpl, [
            'entity' => $entity,
            'form' => $responce['form']->createView(),
            'homeAddress' => $entity->getAddresses()->get('home'),
            'workAddress' => $entity->getAddresses()->get('work'),
            'otherAddress' => $entity->getAddresses()->get('other')
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function addComplaintsAction(Request $request)
    {
        $entity = $this->getUser();

        if(is_null($entity)){

           return $this->redirectToRoute('dolly_homepage');

        }

        $serviceComplaints = $this->get('app_core.complaints.service');
        $serviceComplaints->addComplaints($request, $entity);

        return $this->redirectToRoute('dolly_profile_page');
    }

    /**
     * @Template()
     *
     * @param Request $request
     * @param mixed $id
     *
     * @return Response
     */
    public function menuAction(Request $request, $id)
    {
        $menuHandler = $this->container->get('app_core.menu.handler');

        return $menuHandler->processShowBySlugAction($request, $id);
    }

    /**
     * @Template()
     *
     * @param Request $request
     *
     * @return Response
     */
    public function confirmationAction(Request $request)
    {
        return $this->container->get('app_core.order.handler')->processCreateOrder($request->request->all());
    }

    /**
     * @Template("AppDollyBundle::headerNav.html.twig")
     * @param Request $request
     *
     * @return array
     */
    public function headerNavAction(Request $request)
    {
        $menuHandler = $this->container->get('app_core.menu.handler');
        $menuList = $menuHandler->menu();

        return [
            'topMenuItem' => $menuList['data'][0]
        ];
    }

    /**
     * @Template()
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function customizeAction(Request $request)
    {
        $id = $request->get('cartitem');
        $productId = $request->get('product');
        $shopHandler = $this->get('app_core.shop.handler');

        if($id && !$productId){
            $productId = $shopHandler
                ->getOrderItem($id)
                ->getProductVariant()
                ->getProduct()
                ->getId();
        }
        $isPizza = $shopHandler
                ->getProduct($productId)
                ->getVariants()
                ->first()
                ->getOsProduct()
                ->getCategory()
                ->getParentId() === 1;

        return [
            'productId' => $productId,
            'isPizza' => $isPizza,
            'id'        => $id,
        ];
    }
}
