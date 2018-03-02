<?php

namespace App\Events;


use App\Service\UploadService;
use Symfony\Component\EventDispatcher\Event;

class UploadImageEvent extends Event
{
    private $uploadService;

    /**
     * @return UploadService
     */
    public function getUploadService(): UploadService
    {
        return $this->uploadService;
    }

    /**
     * UploadImageEvent constructor.
     * @param $uploadService - entity that supports UploadService
     */
    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }
}