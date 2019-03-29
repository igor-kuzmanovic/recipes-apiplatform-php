<?php

namespace App\Services;

final class MailerService
{
    private const DEBUG = true;
    private const EMAIL = 'recipesapp.mailer@gmail.com';
    private const CLIENT_URL = 'http://192.168.183.74:3000';
    
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendRegistrationEmail(string $targetEmail, string $confirmationToken) : void
    {
        $message = (new \Swift_Message('RecipesApp, registration successful!'))
            ->setContentType('text/html')
            ->setFrom([self::EMAIL => 'RecipesApp'])
            ->setTo(self::DEBUG ? self::EMAIL : $targetEmail)
            ->setBody(
                "<h3>You did it!</h3>
                <p>Hi, {$targetEmail}! You've successfully registered.</p>
                <p>To confirm your account, go to: 
                <a href='".self::CLIENT_URL."/confirm_registration?email={$targetEmail}&confirmationToken={$confirmationToken}'>Link</a>
                </p>
                <p>Your registration token is:</p>
                <code><b>{$confirmationToken}</b></code>"
            );

        $this->mailer->send($message);
    }

    public function sendResetPasswordEmail(string $targetEmail, string $resetPasswordToken) : void
    {
        $message = (new \Swift_Message('RecipesApp, password reset!'))
            ->setContentType('text/html')
            ->setFrom([self::EMAIL => 'RecipesApp'])
            ->setTo(self::DEBUG ? self::EMAIL : $targetEmail)
            ->setBody(
                "<h3>Hi, {$targetEmail}!</h3>
                <p>Your password has been reset.</p>
                <p>To create a new password go to: 
                <a href='".self::CLIENT_URL."/new_password?email={$targetEmail}&resetPasswordToken={$resetPasswordToken}'>Link</a>
                </p>
                <p>Your password reset token is:</p>
                <code><b>{$resetPasswordToken}</b></code>"
            );

        $this->mailer->send($message);
    }
}