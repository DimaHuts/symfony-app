<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class ProductService
 *
 * @author Dmitry Huts
 */
class ProductService
{
    private $dbService;

    /**
     * ProductService constructor.
     * @param $dbService
     */
    public function __construct(DbService $dbService)
    {
        $this->dbService = $dbService;
    }

    public function getUserByFilter($filter, User $user)
    {
        if ($filter === 'my')
        {
           return $this->dbService->queryFetchByCriteria('App:Product', ['user' => $user->getId()], ['id' => 'DESC']);
        }
        else
        {
            return $this->dbService->queryFetchAll('App:Product', ['id' => 'DESC']);
        }
    }

    public function isDenied(UserInterface $owner, \Symfony\Component\Security\Core\User\UserInterface $currentUser)
    {
        if ($owner !== $currentUser)
        {
            throw new AccessDeniedException();
        }
    }

}