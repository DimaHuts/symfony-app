<?php

namespace App\Service;

/**
 * Interface CommonMessagesServiceInterface
 *
 * @author Dmitry Huts
 */
interface CommonMessagesServiceInterface
{
    /**
     * Gets the message for no existing entity
     *
     * @return string
     */
    function getNotExistEntityMessage(): string ;

}