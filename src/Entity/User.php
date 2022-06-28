<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;
use Webmozart\Assert\Assert as WebmozartAssert;

enum RegistrationStatus: string
{
    case PENDING = 'pending';
    case VALIDATED = 'validated';
    case DISABLED = 'disabled';
}

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]

#[UniqueEntity(
    fields: 'email',
    errorPath: 'email',
    message: 'Cet email est déjà utilisé.',
    repositoryMethod: 'findByEmailInsensitive'
)]
#[UniqueEntity(
    fields: 'username',
    errorPath: 'username',
    message: "Ce nom d'utilisateur est déjà utilisé.",
    repositoryMethod: 'findByUsernameInsensitive'
)]
class User implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank(message: "L'email ne doit pas être vide.")]
    #[Assert\Email]
    private string $email;

    /** @var array<string> $roles */
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'uuid')]
    #[Assert\NotBlank]
    private UuidV4 $uuid;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank(message: "Le nom d'utilisateur ne doit pas être vide.")]
    #[Assert\Length(min: 3, max: 40)]
    #[Assert\Regex(
        '/^[\w]+$/',
        message: "Le nom d'utilisateur ne doit contenir que des lettres, des chiffres et des tirets."
    )]
    private string $username;

    #[ORM\Column(type: 'string', length: 10, enumType: RegistrationStatus::class)]
    #[Assert\NotBlank]
    private RegistrationStatus $registrationStatus;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $registrationDate;

    public function __construct()
    {
        $this->registrationDate = new \Safe\DateTimeImmutable();
        $this->uuid = new UuidV4();
        $this->registrationStatus = RegistrationStatus::PENDING;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = strtolower($email);

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @return array<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array<string> $roles
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmailProviderUrl(): string
    {
        WebmozartAssert::email($this->email);
        list(, $provider) = explode('@', $this->email);

        return 'https://' . $provider;
    }

    public function setRegistrationStatus(RegistrationStatus $registrationStatus): self
    {
        $this->registrationStatus = $registrationStatus;

        return $this;
    }

    public function getRegistrationStatus(): RegistrationStatus
    {
        return $this->registrationStatus;
    }

    public function getRegistrationDate(): ?\DateTimeImmutable
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(\DateTimeImmutable $registrationDate): self
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    public function getValidationHash(): string
    {
        // this hash is used to check user after registration
        return sha1($this->registrationDate->format('U') . (string) $this->uuid);
    }
}
