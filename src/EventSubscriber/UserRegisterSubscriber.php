<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use App\Security\TokenGenerator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserRegisterSubscriber implements EventSubscriberInterface
{
    private $encoder;
    private $tokenGenerator;

    public function __construct(UserPasswordEncoderInterface $encoder, TokenGenerator $tokenGenerator)
    {
        $this->encoder = $encoder;
        $this->tokenGenerator = $tokenGenerator;
    }

    public static function getSubscribedEvents() : array
    {
        return [
            KernelEvents::VIEW => ['hashPasswordGenerateToken', EventPriorities::PRE_WRITE],
        ];
    }

    public function hashPasswordGenerateToken(GetResponseForControllerResultEvent $event) : void
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$user instanceof User || Request::METHOD_POST !== $method) {
            return;
        }

        $encodedPassword = $this->encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($encodedPassword);

        $token = $this->tokenGenerator->getRandomSecureToken();
        $user->setConfirmationToken($token);
    }
}