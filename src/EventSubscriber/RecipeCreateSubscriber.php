<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Recipe;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

final class RecipeCreateSubscriber implements EventSubscriberInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents() : array
    {
        return [
            KernelEvents::VIEW => ['linkUser', EventPriorities::PRE_WRITE],
        ];
    }

    public function linkUser(GetResponseForControllerResultEvent $event) : void
    {
        $recipe = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$recipe instanceof Recipe || Request::METHOD_POST !== $method) {
            return;
        }

        $user = $this->security->getUser();
        $recipe->setUser($user);
    }
}