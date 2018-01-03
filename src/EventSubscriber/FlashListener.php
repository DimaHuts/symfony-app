<?php

namespace App\EventSubscriber;


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
    private $commonMessageService;

    private static $messages = [
        Events::PRODUCT_ADD => 'product.saved',
        Events::SAVE_DB_ERROR => 'exception.save.db',
        Events::PRODUCT_MODIFIED => 'product.modified',
        Events::ENTITY_DOES_NOT_EXIST => 'entity.does.not.exist',
        Events::PRODUCT_DELETED => 'product.deleted',
    ];

    /**
     * FlashListener constructor.
     *
     * @param FlashBagInterface $flashBag
     * @param TranslatorInterface $translator
     * @param CommonMessagesService $commonMessagesService
     */
    public function __construct(FlashBagInterface $flashBag, TranslatorInterface $translator,
                                CommonMessagesService $commonMessagesService)
    {
        $this->flashBag = $flashBag;
        $this->translator = $translator;
        $this->commonMessageService = $commonMessagesService;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::PRODUCT_ADD => 'addSuccessMessage',
            Events::SAVE_DB_ERROR => 'addDangerMessage',
            Events::PRODUCT_MODIFIED => 'addSuccessMessage',
            Events::ENTITY_DOES_NOT_EXIST => 'addDangerMessage',
            Events::PRODUCT_DELETED => 'addSuccessMessage',
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