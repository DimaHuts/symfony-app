<?php

namespace App\Events;

use Zend\EventManager\Event;

class MailerEvent extends Event
{
    private $renderedTemplate;

    /**
     * MailerEvent constructor.
     * @param $templating
     */
    public function __construct(string $renderedTemplate)
    {
        $this->renderedTemplate = $renderedTemplate;
    }

    /**
     * @return string
     */
    public function getRenderedTemplate(): string
    {
        return $this->renderedTemplate;
    }
}