<?php
// src/Service/UserService.php

namespace App\Service;

use App\Repository\UserRepository;

class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function generateReferralCode(): string
    {
        do {
            $referralCode = $this->generateRandomString(8);
        } while ($this->userRepository->findOneBy(['referral_code' => $referralCode]));

        return $referralCode;
    }

    private function generateRandomString($length = 8): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
