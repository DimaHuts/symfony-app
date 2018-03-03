<?php

namespace App\Service;

use App\Entity\User;

/**
 * Class ProductService
 *
 * @author Dmitry Huts
 */
class ProductService implements ProductServiceInterface
{
    private $dbService;

    /**
     * ProductService constructor.
     * @param $dbService
     */
    public function __construct(DbServiceInterface $dbService)
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
}