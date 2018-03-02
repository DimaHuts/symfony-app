<?php
namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Events;
use App\Events\ProductEvent;

class ProductListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            Events::PRODUCT_ADD => "onProductAdd",
        ];
    }
    
    public function onProductAdd(ProductEvent $event)
    {
        $event->getProduct()->setUser($event->getUser());
    }

}