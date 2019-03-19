<?php

namespace App\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    public function updateUser(Request $request, EntityManagerInterface $em, JWTTokenManagerInterface $jwtManager, UserPasswordEncoderInterface $encoder) : Response
    {
        $content = new ArrayCollection(json_decode($request->getContent(), true));

        $oldPassword = $content->get('oldPassword');
        $newPassword = $content->get('password');

        $user = $this->getUser();
        if (!$encoder->isPasswordValid($user, $oldPassword))
        {
            return new Response(null, Response::HTTP_BAD_REQUEST);
        }

        $encodedPassword = $encoder->encodePassword($user, $newPassword);
        $user->setPassword($encodedPassword);

        $em->persist($user);
        $em->flush();

        return new Response();
    }
}
