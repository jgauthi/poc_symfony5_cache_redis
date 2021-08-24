<?php
namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\DossierRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Dossier
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $createdDate;

    #[ORM\Column(type: 'boolean')]
    private bool $active;

    #[ORM\Column(type: 'text')]
    private string $content;

    #[ORM\ManyToOne(targetEntity: 'App\Entity\Client', inversedBy: 'dossier'), ORM\JoinColumn(nullable: false)]
    private Client $client;

    #[ORM\ManyToMany(targetEntity: 'App\Entity\Category', mappedBy: 'dossier')]
    private Collection $categories;

    #[ORM\ManyToOne(targetEntity: 'App\Entity\User', inversedBy: 'dossiers'), ORM\JoinColumn(nullable: false)]
    private User $author;

    public function __construct()
    {
        $this->categories = new ArrayCollection;
    }

    public function __toString(): string
    {
        return $this->getTitle();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCreatedDate(): DateTimeInterface
    {
        return $this->createdDate;
    }

    #[ORM\PrePersist]
    public function setCreatedDate(): self
    {
        $this->createdDate = new DateTimeImmutable;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        if (null === $client) {
            unset($this->client);
        } else {
            $this->client = $client;
        }

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addDossier($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
            $category->removeDossier($this);
        }

        return $this;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        if (null === $author) {
            unset($this->author);
        } else {
            $this->author = $author;
        }

        return $this;
    }
}
