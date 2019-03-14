<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class UserMailSubscriber implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents() : array
    {
        return [
            KernelEvents::VIEW => ['sendMail', EventPriorities::POST_WRITE],
        ];
    }

    public function sendMail(GetResponseForControllerResultEvent $event) : void
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$user instanceof User || Request::METHOD_POST !== $method) {
            return;
        }

        $email = $user->getEmail();
        $confirmationToken = $user->getConfirmationToken();

        $message = (new \Swift_Message('Welcome to RecipesApp'))
            ->setFrom('recipesapp.mailer@gmail.com')
//            ->setTo($email)
            ->setTo('recipesapp.mailer@gmail.com')
            ->setBody(sprintf('Your account has been created. Token: %s', $confirmationToken));

        $this->mailer->send($message);
    }
}