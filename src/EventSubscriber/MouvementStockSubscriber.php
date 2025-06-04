<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MouvementStockSubscriber implements EventSubscriberInterface
{
    public function onPostPersist($event): void
    {
        // ...
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'postPersist' => 'onPostPersist',
        ];
    }
}
