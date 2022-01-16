<?php

namespace App\EventSubscriber;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

class TaskSubscriber implements EventSubscriberInterface
{
    /**
     * @var Security
     */
    private $security;

    private $skipLoad;    

    public function __construct(Security $security)
    {
        $this->security = $security;
        $this->skipLoad = false;
    }

    public function skipLoad(): void
    {
        $this->skipLoad = true;
    }

    public function unSkipLoad(): void
    {
        $this->skipLoad = false;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        // if this subscriber only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$entity instanceof Task || $this->skipLoad) {
            return;
        }

        $entity->setUser($this->security->getUser());
    }
}
