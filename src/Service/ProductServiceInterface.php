<?php

namespace App\Service;


use App\Entity\User;

interface ProductServiceInterface
{
    public function getUserByFilter($filter, User $user);
}