<?php

namespace App\Security;


use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;


class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if(!$user instanceof AppUser){
            return;
        }

        if ($user->getIsActive() == false) {
            throw new CustomUserMessageAuthenticationException("You're banned !");
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if(!$user instanceof AppUser){
            return;
        }

        if ($user->getIsActive() == false) {
            throw new CustomUserMessageAuthenticationException("You're banned 1!");
        }
    }
}