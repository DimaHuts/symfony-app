<?php

namespace App\Service;

/**
 * Interface PaginationValidationServiceInterface
 *
 * @author Dmitry Huts
 */
interface PaginationValidationServiceInterface
{
    /**
     * This method checks received page number for case if the number of page
     * is <0 or > maxPages
     *
     * @param int $testPageNumber
     * @return int
     */
    function validatePageNumber(int $testPageNumber): int;
}