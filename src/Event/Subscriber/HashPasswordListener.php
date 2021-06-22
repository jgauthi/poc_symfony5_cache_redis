<?php
namespace App\Event\Subscriber;

use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Encode password before insert/update on database (Doctrine Event Subscriber).
 */
class HashPasswordListener implements EventSubscriber
{
    public function __construct(private UserPasswordEncoderInterface $encoder)
    {
    }

    private function encodePassword(User $user): void
    {
        $password = $user->getPlainPassword();
        if (empty($password)) {
            return;
        }

        $encoded = $this->encoder->encodePassword($user, $password);
        $user->setPassword($encoded);
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return string[]
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        /** @var User $user */
        $user = $args->getEntity();
        if (!$user instanceof User) {
            return;
        }

        $this->encodePassword($user);
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        /** @var User $user */
        $user = $args->getEntity();
        if (!$user instanceof User) {
            return;
        }

        $this->encodePassword($user);
    }
}
