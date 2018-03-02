<?php

namespace App\EventListener;

use App\Events;
use App\Events\UserEvent;
use App\Utils\TokenGenerator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserListener implements EventSubscriberInterface
{
    private $passwordEncoder;
    private $tokenGenerator;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, TokenGenerator $tokenGenerator)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenGenerator = $tokenGenerator;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::PASSWORD_ENCODE => 'onPasswordEncode',
            Events::SET_TOKEN => 'onSetToken',
            Events::EMAIL_CONFIRMED => 'onEmailConfirmed',
        ];
    }

    public function onPasswordEncode(UserEvent $event, $eventName)
    {
        $user = $event->getUser();
        $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
    }

    public function onSetToken(UserEvent $event, $eventName)
    {
        $user = $event->getUser();

        if (null == $user->getToken())
        {
            $user->setToken($this->tokenGenerator->generateToken());
        }
    }

    public function onEmailConfirmed(UserEvent $event, $eventName)
    {
        $user = $event->getUser();

        $user->setIsActive(true);
        $user->setToken(null);
    }
}