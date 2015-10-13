<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 01.09.15
 */

namespace App\DollyBundle\Controller;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PaymentController
 * @package App\DollyBundle\Controller
 */
class PaymentController extends DefaultController
{
    /**
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function indexAction(Request $request)
    {
        $entity = $this->getUser();

        if(is_null($entity)){

            return $this->redirectToRoute('dolly_homepage');

        }

        $customerHandler = $this->container->get('app_core.customer.handler');
        $response = $customerHandler->processEditAction($request, $entity);

        return $this->render('AppDollyBundle:Dolly:payment.html.twig', ['form' => $response['form']]);
    }
}