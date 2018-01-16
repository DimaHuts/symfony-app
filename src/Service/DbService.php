<?php

namespace App\Service;

use App\Events;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class DbService
 *
 * @author Dmitry Huts
 */
class DbService implements DbServiceInterface
{

    private $em;
    private $eventDispatcher;

    /**
     * DbService constructor.
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @see DbServiceInterface::saveData()
     * @inheritdoc
     */
    function saveData($entities): void
    {
        try
        {
            for ($i = 0; $i < count($entities); $i++) {

                $entity = $entities[$i];
                
                $this->em->persist($entity);
                $this->em->flush();
            }
            
        }
        catch (Exception $exception)
        {
            $this->eventDispatcher->dispatch(Events::SAVE_DB_ERROR);
        }
    }

    /**
     * @see DbServiceInterface::deleteData()
     * @inheritdoc
     */
    function deleteData($entity, string $successEventName): void
    {
        try
        {
            $this->em->remove($entity);
            $this->em->flush();
            $this->eventDispatcher->dispatch($successEventName);
        }
        catch (Exception $exception)
        {
            $this->eventDispatcher->dispatch(Events::SAVE_DB_ERROR);
        }
    }

    /**
     * @see DbServiceInterface::findByCriteria()
     * @inheritdoc
     */
    function findByCriteria(string $className, array $criteria, array $orderBy)
    {
        return $this->em->getRepository($className)->findBy($criteria, $orderBy);
    }

    /**
     * @see DbServiceInterface::findOneByCriteria()
     * @inheritdoc
     */
    function findOneByCriteria(string $className, array $criteria)
    {
        return $this->em->getRepository($className)->findOneBy($criteria);
    }

    /**
     * @see DbServiceInterface::queryFetchAll()
     * @inheritdoc
     */
    function queryFetchAll(string $className, array $orderBy)
    {
        $query = $this->em->createQueryBuilder();
        $sortField = key($orderBy);
        $sortType = $orderBy[$sortField];
        return $query->select('a')->from($className, 'a')->orderBy('a.' . $sortField, $sortType);
    }

    /**
     * Fetches $className data with limit $criteria
     *
     * @param string $className
     * @param array $criteria
     * @param array $orderBy
     * @return mixed
     */
    function queryFetchByCriteria(string $className, array $criteria, array $orderBy)
    {
        $query = $this->em->createQueryBuilder();
        $sortField = key($orderBy);
        $sortType = $orderBy[$sortField];
        $criteriaField = key($criteria);
        $criteriaValue = $criteria[$criteriaField];
        return $query
                    ->select('a')
                    ->from($className, 'a')
                    ->where('a.' . $criteriaField . '= ' . $criteriaValue)
                    ->orderBy('a.' . $sortField, $sortType);
    }
}