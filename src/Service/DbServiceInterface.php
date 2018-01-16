<?php

namespace App\Service;

use Doctrine\ORM\Query;

/**
 * Interface DbServiceInterface
 *
 * @author Dmitry Huts
 */
interface DbServiceInterface {

    /**
     * This method saves data into database
     */
    function saveData($entity): void;

    /**
     * This method deletes data from database
     *
     * @param $entity
     * @param string $successEventName
     * @return mixed
     */
    function deleteData($entity, string $successEventName): void;

    /**
     * This method fetches $className type`s data from database using
     * $criteria and $orderBy params
     *
     * @param string $className
     * @param array $criteria
     * @param array $orderBy
     * @return mixed
     */
    function findByCriteria(string $className, array $criteria, array $orderBy);

    /**
     * This method fetches one $className object only from database
     *
     * @param string $className
     * @param array $criteria
     * @return mixed
     */
    function findOneByCriteria(string $className, array $criteria);

    /**
     * This method fetches all $className data via queryBuilder
     *
     * @param string $className
     * @param array $orderBy
     * @return Query
     */
    function queryFetchAll(string $className, array $orderBy);

    /**
     * Fetches $className data with limit $criteria
     *
     * @param string $className
     * @param array $criteria
     * @param array $orderBy
     * @return mixed
     */
    function queryFetchByCriteria(string  $className, array $criteria, array $orderBy);

}
