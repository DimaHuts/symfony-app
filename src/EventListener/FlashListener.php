<?php

namespace App\EventListener;


use App\Events;
use App\Service\CommonMessagesService;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Translation\TranslatorInterface;

class FlashListener implements EventSubscriberInterface
{

    private $flashBag;
    private $translator;

    private static $messages = [
        Events::PRODUCT_ADD => 'product.saved',
        Events::SAVE_DB_ERROR => 'exception.save.db',
        Events::PRODUCT_MODIFIED => 'product.modified',
        Events::ENTITY_DOES_NOT_EXIST => 'entity.does.not.exist',
        Events::PRODUCT_DELETED => 'product.deleted',
        Events::USER_REGISTERED => 'user.registered',
        Events::EMAIL_CONFIRMED => 'email.confirmed',
        Events::PASSWORD_FORGOT_REQUEST => 'password.forgot.request',
        Events::PASSWORD_CHANGE_SUCCESS => 'password.change.success',
        Events::USER_DOES_NOT_EXIST => 'user.does.not.exist'
    ];

    /**
     * FlashListener constructor.
     *
     * @param FlashBagInterface $flashBag
     * @param TranslatorInterface $translator
     * @param CommonMessagesService $commonMessagesService
     */
    public function __construct(FlashBagInterface $flashBag, TranslatorInterface $translator)
    {
        $this->flashBag = $flashBag;
        $this->translator = $translator;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::PRODUCT_ADD => 'addSuccessMessage',
            Events::SAVE_DB_ERROR => 'addDangerMessage',
            Events::PRODUCT_MODIFIED => 'addSuccessMessage',
            Events::ENTITY_DOES_NOT_EXIST => 'addDangerMessage',
            Events::PRODUCT_DELETED => 'addSuccessMessage',
            Events::USER_REGISTERED => 'addSuccessMessage',
            Events::EMAIL_CONFIRMED => 'addSuccessMessage',
            Events::PASSWORD_FORGOT_REQUEST => 'addSuccessMessage',
            Events::PASSWORD_CHANGE_SUCCESS => 'addSuccessMessage',
            Events::USER_DOES_NOT_EXIST => 'addDangerMessage'
        ];
    }

    public function addSuccessMessage(Event $event, $eventName)
    {
        $this->flashBag
            ->add('success', $this->translator->trans(self::$messages[$eventName], [], 'messages'));
    }

    public function addDangerMessage(Event $event, $eventName)
    {
        $this->flashBag
            ->add('danger', $this->translator->trans(self::$messages[$eventName], [], 'messages'));
    }
}