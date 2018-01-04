<?php

namespace App\Service;

use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class CommonMessagesService
 *
 * @author Dmitry Huts
 */
class CommonMessagesService implements CommonMessagesServiceInterface
{

    private $translator;

    /**
     * CommonMessagesService constructor.
     * @param $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }


    /**
     * @see CommonMessagesServiceInterface::getNotExistEntityMessage()
     * @inheritdoc
     */
    function getNotExistEntityMessage(): string
    {
        return $this->translator->trans('entity.does.not.exist', [], 'product');
    }
}