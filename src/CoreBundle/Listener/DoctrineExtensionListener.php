<?php

namespace CoreBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class DoctrineExtensionListener.
 */
class DoctrineExtensionListener implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

//    public function onLateKernelRequest(GetResponseEvent $event)
//    {
//        $translatable = $this->container->get('gedmo.listener.translatable');
//        $translatable->setTranslatableLocale($event->getRequest()->getLocale());
//    }
//
//    public function onKernelRequest(GetResponseEvent $event)
//    {
//        $securityContext = $this->container->get('security.context', ContainerInterface::NULL_ON_INVALID_REFERENCE);
//        if (null !== $securityContext && null !== $securityContext->getToken() && $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
//            $loggable = $this->container->get('gedmo.listener.loggable');
//            $loggable->setUsername($securityContext->getToken()->getUsername());
//        }
//    }

    /**
     * @param GetResponseEvent $event
     */
    public function blamableOnKernelRequest(GetResponseEvent $event)
    {
        $securityContext = $this->container->get('security.token_storage', ContainerInterface::NULL_ON_INVALID_REFERENCE);
        $authChecker = $this->container->get('security.authorization_checker', ContainerInterface::NULL_ON_INVALID_REFERENCE);
        if (null !== $securityContext && null !== $authChecker &&
            null !== $securityContext->getToken() &&
            $authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $blamable = $this->container->get('gedmo.listener.blameable');
            $blamable->setUserValue($securityContext->getToken()->getUser());
        }
    }
}
