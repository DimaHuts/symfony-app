<?php

namespace App\EventListener;


use App\Events;
use App\Events\UploadImageEvent;
use App\Service\UploadService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UploadImageListener implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            Events::ADD_IMAGE => 'onAddImage',
            Events::DELETE_IMAGE => 'onDeleteImage'
        ];
    }

    public function onAddImage(UploadImageEvent $event, $eventName)
    {
        $entity = $event->getUploadService();
        $uploadedFiles = $entity->getUploadedFiles();
        $files = $entity->getFiles();

        if (!empty($uploadedFiles))
        {
            if (!empty($files))
            {
                UploadService::removeFiles($files);
            }

            $entity->setFiles(UploadService::transferFiles($uploadedFiles));
        }
    }

    public function onDeleteImage(UploadImageEvent $event, $eventName)
    {
        $entity = $event->getUploadService();
        $files = $entity->getFiles();

        if (!empty($files))
        {
            UploadService::removeFiles($files);
        }
    }


}