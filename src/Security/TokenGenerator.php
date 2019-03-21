<?php

namespace App\Security;

class TokenGenerator
{
    private const ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    /**
     * @param int $length
     * @return string
     */
    public function getRandomSecureToken(int $length = 30): string
    {
        $token = '';

        try 
        {
            $maxNumber = strlen(self::ALPHABET);

            for ($i = 0; $i < $length; $i++) {
                $token .= self::ALPHABET[random_int(0, $maxNumber - 1)];
            }
        }
        catch (\Exception $exception) { }

        return $token;
    }
}