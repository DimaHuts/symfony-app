<?php

namespace App\Service;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class PaginatingService
 *
 * @author Dmitry Huts
 */
class PaginatingService implements PaginatingServiceInterface, PaginationValidationServiceInterface
{

    private $limit = 10;
    private $dbService;
    private $maxPages;
    private $validatedPageNumber;

    public function __construct(DbServiceInterface $dbService)
    {
        $this->dbService = $dbService;
    }

    /**
     * @see PaginatingServiceInterface::paginate()
     * @inheritdoc
     */
    public function paginate(QueryBuilder $query, int $page) : Paginator
    {
        $paginator = new Paginator($query);

        $this->setMaxPages($paginator);

        $page = $this->validatePageNumber($page);
        $this->setValidatedPageNumber($page);

        $paginator->getQuery()
            ->setFirstResult($this->limit * ($page - 1)) // Offset
            ->setMaxResults($this->limit); // Limit

        return $paginator;
    }

    /**
     * @see PaginatingServiceInterface::getLimit()
     * @inheritdoc
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @see PaginatingServiceInterface::setLimit()
     * @inheritdoc
     */
    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    private function setMaxPages(Paginator $paginator)
    {
        $this->maxPages = ceil($paginator->count() / $this->getLimit());
    }

    /**
     * @see PaginatingServiceInterface::getMaxPages()
     * @inheritdoc
     */
    public function getMaxPages(): int
    {
        return $this->maxPages;
    }

    /**
     * @param mixed $validatedPageNumber
     */
    private function setValidatedPageNumber(int $validatedPageNumber): void
    {
        $this->validatedPageNumber = $validatedPageNumber;
    }

    /**
     * @see PaginatingServiceInterface::getCurrentPage()
     * @inheritdoc
     */
    public function getCurrentPage(): int
    {
        return $this->validatedPageNumber;
    }

    /**
     * @see PaginationValidationServiceInterface::validatePageNumber()
     * @inheritdoc
     */
    public function validatePageNumber(int $testPageNumber): int
    {
        if ($testPageNumber < 1)
        {
            $this->setValidatedPageNumber(1);
            return 1;
        }
        elseif ($testPageNumber > $this->maxPages)
        {
            $this->setValidatedPageNumber($this->maxPages);
            return $this->maxPages;
        }

        $this->setValidatedPageNumber($testPageNumber);

        return $testPageNumber;
    }
}