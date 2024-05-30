<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post as MetadataPost;
use ApiPlatform\Metadata\Put;
use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\String\Slugger\SluggerInterface;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[Orm\HasLifecycleCallbacks] // ecoute les events doctrines
#[UniqueEntity('slug')]
#[ApiResource(
    openapiContext: ['security' => [['JWT' => []]]],
    security: "is_granted('ROLE_USER')",
    denormalizationContext:['groups' => 'post:write'], 
    normalizationContext:['groups' => 'read:collection'],
    operations: [
        new GetCollection(),
        new Get(),
        new MetadataPost(normalizationContext:['groups' => 'post:read']),
        new Put(
            security: 'object.getUser() == user',
            normalizationContext:['groups' => 'post:read']
        ),
        new Delete(security: 'object.getUser() == user')
    ]
)]
#[ApiFilter(SearchFilter::class, properties:['title' => 'partial'])]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:collection', 'post:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['post:write', 'read:collection', 'post:read'])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(['post:read'])]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['post:write', 'read:collection', 'post:read'])]
    private ?string $content = null;

    #[ORM\Column]
    #[Groups(['read:collection', 'post:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read:collection', 'post:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[Groups(['post:write', 'read:collection', 'post:read'])]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:collection', 'post:read'])]
    private ?User $user = null;

    // complet createdAt automatiquement
    #[Orm\PrePersist]
    public function setCreateAt(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    // complet updatedAt automatiquement
    #[Orm\PreUpdate]
    public function setUpdateAt(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    // complet slug automatiquement
    public function computeSlug(SluggerInterface $slugger): void
    {
        $this->slug = $slugger->slug($this->title)->lower();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
