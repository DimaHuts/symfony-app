<?php
namespace App\Events;

use App\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class UserEvent extends Event
{
    private $user;
    
    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }
    
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    
    
}

