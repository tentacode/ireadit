<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\LinkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Safe\DateTimeImmutable;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: LinkRepository::class)]
class Link
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'uuid')]
    private UuidV4 $uuid;

    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    #[ORM\Column(type: 'string', length: 255)]
    private string $url;

    #[ORM\Column(type: 'string', length: 255)]
    private string $type;

    /** @var Collection<int,LinkEvent> $events */
    #[ORM\OneToMany(mappedBy: 'link', targetEntity: LinkEvent::class, orphanRemoval: true)]
    private Collection $events;

    #[ORM\Column(type: 'datetime')]
    private DateTimeImmutable $creation_date;

    /** @var array<string> $metas */
    #[ORM\Column(type: 'json')]
    private array $metas = [];

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->creation_date = new DateTimeImmutable();
        $this->uuid = new UuidV4();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): UuidV4
    {
        return $this->uuid;
    }

    public function setUuid(UuidV4 $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, LinkEvent>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(LinkEvent $event): self
    {
        if (! $this->events->contains($event)) {
            $this->events[] = $event;
            $event->setLink($this);
        }

        return $this;
    }

    public function getCreationDate(): ?DateTimeImmutable
    {
        return $this->creation_date;
    }

    public function setCreationDate(DateTimeImmutable $creation_date): self
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    /**
     * @return array<string>
     */
    public function getMetas(): array
    {
        return $this->metas;
    }

    /**
     * @param array<string> $metas
     */
    public function setMetas(array $metas): self
    {
        $this->metas = $metas;

        return $this;
    }
}
