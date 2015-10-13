<?php

/**
 * @author:     lars <lars@norse.digital>
 *
 * @copyright   Copyright (C) 2015 Norse Digital AS.
 * @date: 09 07 2015
 */
namespace App\DollyBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\Form;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class WebStateService.
 */
class ContactFormService
{
    private $twig;
    private $formFactory;
    private $form;
    private $mailer;
    private $container;

    /**
     * @param $twig
     * @param FormFactory $formFactory
     * @param $form
     * @param $mailer
     * @param Container $container DI-container for getting the required services
     */
    public function __construct($twig, FormFactory $formFactory, $form, $mailer, Container $container)
    {
        $this->twig = $twig;
        $this->formFactory = $formFactory;
        $this->form = $form;
        $this->mailer = $mailer;
        $this->container = $container;
    }

    /**
     * Show the contact form.
     * 
     * @return array
     */
    public function showForm()
    {
        return ['form' => $this->getForm()];
    }

    /**
     * Process the contact form, if everyting is OK we will:
     * - Send a email to Dolly Dimple's with the request data
     * - Show a static thank you page
     * If the request is not OK (Does not validate) we will:
     * - Show the contact form again.
     * 
     * @param Request $request
     *
     * @return array|Response $response
     */
    public function processFormSubmission(Request $request)
    {
        $form = $this->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->sendEmail(
                $this->getSender($form->get('email')->getData()),
                $this->getToRecipient(),
                $this->getBccRecipient(),
                $this->prepareEmailData($form, $request)
            );

            return $this->renderThankYouPage();
        }

        return ['form' => $form];
    }

    /**
     * Generates a static thank you message.
     * 
     * @return Response object with the thank you page
     */
    private function renderThankYouPage()
    {
        return new Response(
            $this->twig->render(
                'AppDollyBundle:ContactForm:thanks.html.twig'
            )
        );
    }

    /**
     * @return mixed False by default
     */
    private function getForm()
    {
        return $this->formFactory->create($this->form);
    }

    /**
     * Send the email message.
     *
     * @param $sender
     * @param $recipient
     * @param $bccHeader
     * @param $data
     */
    private function sendEmail($sender, $recipient, $bccHeader, $data)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($data['subject'])
            ->setFrom($sender)
            ->setTo($recipient)
            ->setBcc($bccHeader)
            ->setContentType('text/html')
            ->setBody(
                $this->twig->render(
                    'AppDollyBundle:ContactForm:mail.html.twig',
                    $data
                )
            );

        $this->mailer->send($message);
    }

    /**
     * Get the from/sender email address to be used in the email.
     * 
     * @param string $sender the email address supplied by the user in the request form.
     *
     * @return string email address to be used as the sender/from on the email
     */
    private function getSender($sender)
    {
        if ($this->container->hasParameter('app_dolly.contact_form.email.from')) {
            // If the from parameter is set we use this
            $email = $this->container->getParameter('app_dolly.contact_form.email.from');

            if (is_string($email) && strlen(trim($email))) {
                return $email;
            }
        }
        // If not we used the email address supplied by the user as the sender address.
        return $sender;
    }

    /**
     * Return the recipients of the email that has been configutes in the yml files.
     * 
     * @return string|array of email addresses
     */
    private function getToRecipient()
    {
        if ($this->container->hasParameter('app_dolly.contact_form.email.to')) {
            return $this->container->getParameter('app_dolly.contact_form.email.to');
        }

        return [];
    }

    /**
     * Return the undisclosed recipients of the email that has been configutes in the yml files.
     * 
     * @return string|array of email addresses
     */
    private function getBccRecipient()
    {
        if ($this->container->hasParameter('app_dolly.contact_form.email.bcc')) {
            return $this->container->getParameter('app_dolly.contact_form.email.bcc');
        }

        return [];
    }

    /**
     * Create an array of data to be used by the template generating the email
     * message body.
     *
     * @param Form $form
     * @param Request $request
     * @return array of data
     */
    private function prepareEmailData(Form $form, Request $request)
    {
        return [
            'ip' => $request->getClientIp(),
            'firstName' => $form->get('firstName')->getData(),
            'lastName' => $form->get('lastName')->getData(),
            'email' => $form->get('email')->getData(),
            'subject' => $form->get('subject')->getData(),
            'message' => $form->get('message')->getData(),
        ];
    }
}
