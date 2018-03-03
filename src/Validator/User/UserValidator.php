<?php

namespace App\Validator\User;

use App\Entity\User;
use App\Events;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UserValidator implements UserValidatorInterface
{
    private $eventDispatcher;

    /**
     * UserValidator constructor.
     * @param $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function isExistedUser($user)
    {
        if (!$user instanceof User)
        {
            $this->eventDispatcher->dispatch(Events::USER_DOES_NOT_EXIST);
            return false;
        }

        return true;
    }
}
