<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

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
}
