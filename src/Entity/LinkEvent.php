<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\LinkEventRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LinkEventRepository::class)]
class LinkEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeImmutable $event_date;

    #[ORM\Column(type: 'string', length: 255)]
    private string $type;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $author;

    #[ORM\ManyToOne(targetEntity: Link::class, inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private Link $link;

    public function __construct()
    {
        $this->event_date = new \DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEventDate(): \DateTimeImmutable
    {
        return $this->event_date;
    }

    public function setEventDate(\DateTimeImmutable $event_date): self
    {
        $this->event_date = $event_date;

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

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getLink(): Link
    {
        return $this->link;
    }

    public function setLink(Link $link): self
    {
        $this->link = $link;

        return $this;
    }
}
