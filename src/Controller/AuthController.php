<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\TokenGenerator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthController extends AbstractController
{
    private const EMAIL = 'recipesapp.mailer@gmail.com';
    private const URL = 'http://192.168.183.74:3000/new_password';

    public function confirmRegistration(
            Request $request, 
            EntityManagerInterface $em, 
            JWTTokenManagerInterface $jwtManager
        ) : Response
    {
        $content = new ArrayCollection(json_decode($request->getContent(), true));

        $email = $content->get('email');
        $confirmationToken = $content->get('confirmationToken');

        $user = $em->getRepository(User::class)->findOneBy(
            array('email' => $email, 'confirmationToken' => $confirmationToken)
        );
        if (!$user instanceof User)
        {
            return new Response(null, Response::HTTP_BAD_REQUEST);
        }

        $user->setConfirmationToken(null);
        $user->setIsEnabled(true);

        $em->persist($user);
        $em->flush();

        return new JsonResponse(['token' => $jwtManager->create($user)]);
    }

    public function resetPassword(
            Request $request, 
            EntityManagerInterface $em, 
            TokenGenerator $tokenGenerator, 
            \Swift_Mailer $mailer
        ) : Response
    {
        $content = new ArrayCollection(json_decode($request->getContent(), true));

        $email = $content->get('email');

        $user = $em->getRepository(User::class)->findOneBy(array('email' => $email));
        if (!$user instanceof User)
        {
            return new Response(null, Response::HTTP_BAD_REQUEST);
        }

        $resetPasswordToken = $tokenGenerator->getRandomSecureToken();

        $user->setResetPasswordToken($resetPasswordToken);
        $user->setIsEnabled(false);

        $em->persist($user);
        $em->flush();

        $email = $user->getEmail();
        $message = (new \Swift_Message('RecipesApp, password reset!'))
            ->setContentType('text/html')
            ->setFrom([self::EMAIL => 'RecipesApp'])
//            ->setTo($email)
            ->setTo(self::EMAIL)
            ->setBody(
                "<h3>Hi, {$email}!</h3>
                <p>Your password has been reset</p>
                <p>To create a new password go to: 
                <a href='".self::URL."?email={$email}&resetPasswordToken={$resetPasswordToken}'>Link</a>
                </p>
                <p>Your password reset token is:</p>
                <code><b>{$resetPasswordToken}</b></code>"
            );

        $mailer->send($message);

        return new Response();
    }

    public function newPassword(
            Request $request, 
            EntityManagerInterface $em, 
            JWTTokenManagerInterface $jwtManager, 
            UserPasswordEncoderInterface $encoder
        ) : Response
    {
        $content = new ArrayCollection(json_decode($request->getContent(), true));

        $email = $content->get('email');
        $resetPasswordToken = $content->get('resetPasswordToken');
        $password = $content->get('password');

        $user = $em->getRepository(User::class)->findOneBy(
            array('email' => $email, 'resetPasswordToken' => $resetPasswordToken)
        );
        if (!$user instanceof User)
        {
            return new Response(null, Response::HTTP_BAD_REQUEST);
        }

        $user->setResetPasswordToken(null);
        $user->setIsEnabled(true);
        $encodedPassword = $encoder->encodePassword($user, $password);
        $user->setPassword($encodedPassword);

        $em->persist($user);
        $em->flush();

        return new JsonResponse(['token' => $jwtManager->create($user)]);
    }
}
