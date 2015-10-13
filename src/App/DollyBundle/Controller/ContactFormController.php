<?php

namespace App\DollyBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ContactFormController.
 */
class ContactFormController extends DefaultController
{
    /**
     * @Template()
     *
     * @param Request $request
     *
     * @return Response
     */
    public function showAction(Request $request)
    {
        $contactForm = $this->get('app_dolly.contact.handler');

        if ($request->isMethod('POST')) {
            return $contactForm->processFormSubmission($request);
        }

        return $contactForm->showForm($request);
    }
}
