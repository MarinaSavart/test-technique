<?php

namespace App\EntityListener;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsEntityListener(event: Events::prePersist, entity: Post::class)]
#[AsEntityListener(event: Events::preUpdate, entity: Post::class)]
class PostEntityListener 
{
    public function __construct(
        private readonly SluggerInterface $slugger,
        private readonly Security $security
    ){}


    public function prePersist(Post $post, LifecycleEventArgs $event): void
    {
        $post->computeSlug($this->slugger);
        $post->setUser($this->security->getUser());
    }

    public function preUpdate(Post $post, LifecycleEventArgs $event): void
    {
        $post->computeSlug($this->slugger);
    }
}