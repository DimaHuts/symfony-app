<?php

namespace App\EventListener;


use App\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Events\UserEvent;
use App\Utils\TokenGenerator;
use App\Mailer\Mailer;

class EmailConfirmationListener implements EventSubscriberInterface
{
    
    private $mailer;
    private $tokenGenerator;
    
    public function __construct(Mailer $mailer, TokenGenerator $tokenGenerator)
    {
        $this->mailer = $mailer;
        $this->tokenGenerator = $tokenGenerator;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::USER_REGISTERED => "onRegistrationSuccess",
        ];
    }

    public function onRegistrationSuccess(UserEvent $event, $eventName)
    {
        $user = $event->getUser();
        
        if (null == $user->getToken())
        {
            $user->setToken($this->tokenGenerator->generateToken());
        }
        
        $this->mailer->sendConfirmationEmailMessage($user);
    }
}