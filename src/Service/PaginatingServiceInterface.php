<?php

namespace App\Service;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Interface PaginatingServiceInterface
 *
 * @author Dmitry Huts
 */
interface PaginatingServiceInterface
{

    /**
     * This is additional method that configures $paginator
     *
     * @param QueryBuilder $query
     * @param int $page
     * @return Paginator
     */
    function paginate(QueryBuilder $query, int $page) : Paginator;


    /**
     * This method gets count elements on one page
     *
     * @return int
     */
    function getLimit(): int;

    /**
     * This method sets count element on one page
     *
     * @param int $limit
     */
    function setLimit(int $limit): void;

    /**
     * This method gets count all pages for paginating
     *
     * @return int
     */
    public function getMaxPages(): int;

    /**
     * Gets a current page for pagination
     *
     * @return int
     */
    function getCurrentPage(): int;

}