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

        $message = (new \Swift_Message('RecipesApp, registration successful!'))
            ->setContentType('text/html')
            ->setFrom(['recipesapp.mailer@gmail.com' => 'RecipesApp'])
//            ->setTo($email)
            ->setTo('recipesapp.mailer@gmail.com')
            ->setBody(sprintf(
                "<h3>You did it! You registered!</h3>
                <p>Hi, %s! You've successfully registered.</p>
                <p>To confirm your account, go to: 
                <a href='http://localhost:3000/confirm_registration?email=%s&confirmationToken=%s'>Link</a>
                </p>
                <p>Your registration token is:</p>
                <code>%s</code>", $email, $email, $confirmationToken, $confirmationToken
            ));

        $this->mailer->send($message);
    }
}