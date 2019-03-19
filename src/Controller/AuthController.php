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
    public function confirmRegistration(Request $request, EntityManagerInterface $em, JWTTokenManagerInterface $jwtManager) : Response
    {
        $content = new ArrayCollection(json_decode($request->getContent(), true));

        $email = $content->get('email');
        $confirmationToken = $content->get('confirmationToken');

        $user = $em->getRepository(User::class)->findOneBy(array('email' => $email, 'confirmationToken' => $confirmationToken));
        if (!$user instanceof User)
        {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }

        $user->setConfirmationToken(null);
        $user->setIsEnabled(true);

        $em->persist($user);
        $em->flush();

        return new JsonResponse(['token' => $jwtManager->create($user)]);
    }

    public function resetPassword(Request $request, EntityManagerInterface $em, TokenGenerator $tokenGenerator, \Swift_Mailer $mailer) : Response
    {
        $content = new ArrayCollection(json_decode($request->getContent(), true));

        $email = $content->get('email');

        $user = $em->getRepository(User::class)->findOneBy(array('email' => $email));
        if (!$user instanceof User)
        {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }

        $resetPasswordToken = $tokenGenerator->getRandomSecureToken();

        $user->setResetPasswordToken($resetPasswordToken);
        $user->setIsEnabled(false);

        $em->persist($user);
        $em->flush();

        $email = $user->getEmail();
        $message = (new \Swift_Message('RecipesApp, password reset!'))
            ->setContentType('text/html')
            ->setFrom(['recipesapp.mailer@gmail.com' => 'RecipesApp'])
//            ->setTo($email)
            ->setTo('recipesapp.mailer@gmail.com')
            ->setBody(sprintf(
                "<h3>Your password has been reset!</h3>
                <p>Hi, %s! You've successfully registered.</p>
                <p>To create a new password go to: 
                <a href='http://localhost:3000/new_password?email=%s&resetPasswordToken=%s'>Link</a>
                </p>
                <p>Your password reset token is:</p>
                <code>%s</code>", $email, $email, $resetPasswordToken, $resetPasswordToken
            ));

        $mailer->send($message);

        return new Response();
    }

    public function newPassword(Request $request, EntityManagerInterface $em, JWTTokenManagerInterface $jwtManager, UserPasswordEncoderInterface $encoder) : Response
    {
        $content = new ArrayCollection(json_decode($request->getContent(), true));

        $email = $content->get('email');
        $resetPasswordToken = $content->get('resetPasswordToken');
        $password = $content->get('password');

        $user = $em->getRepository(User::class)->findOneBy(array('email' => $email, 'resetPasswordToken' => $resetPasswordToken));
        if (!$user instanceof User)
        {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
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
