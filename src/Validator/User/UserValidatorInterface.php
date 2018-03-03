<?php

namespace App\Validator\User;


interface UserValidatorInterface
{
    public function isExistedUser($user);
}