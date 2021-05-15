<?php


namespace SweetSallyBe\Helpers\Service;

use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use SweetSallyBe\Helpers\Entity\AbstractEntity;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AbstractEasyAdminSubscriber implements EventSubscriberInterface
{

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => [
                ['setCreatedAtPersist'],
                ['setUpdatedAtPersist'],
            ],
            BeforeEntityUpdatedEvent::class   => [
                ['setUpdatedAtUpdate'],
            ]
        ];
    }

    public function setCreatedAtPersist(BeforeEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();
        if ($entity instanceof AbstractEntity) {
            $entity->setCreatedAt(new \DateTime());
        }
    }

    public function setUpdatedAtPersist(BeforeEntityPersistedEvent $event): void
    {
        $this->setUpdatedAt($event->getEntityInstance());
    }

    public function setUpdatedAtUpdate(BeforeEntityUpdatedEvent $event): void
    {
        $this->setUpdatedAt($event->getEntityInstance());
    }

    private function setUpdatedAt(AbstractEntity $entity): void
    {
        $entity->setUpdatedAt(new \DateTime());
    }

}