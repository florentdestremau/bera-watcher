<?php

namespace App\Entity;

use App\Model\Mountain;
use App\Repository\SubscriberRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Notifier\Recipient\EmailRecipientInterface;

#[ORM\Entity(repositoryClass: SubscriberRepository::class)]
#[ORM\UniqueConstraint(fields: ['email'])]
#[UniqueEntity('email', message: 'Cette adresse est déjà utilisée')]
class Subscriber implements EmailRecipientInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $email;

    /**
     * @var array<Mountain>
     */
    #[ORM\Column(type: Types::SIMPLE_ARRAY, enumType: 'App\Model\Mountain')]
    private array $mountains = [];

    #[ORM\Column(length: 255)]
    private string $token;

    public function __construct()
    {
        $this->token = bin2hex(random_bytes(8));
    }

    public function __toString(): string
    {
        return "{$this->email}: {$this->getMountainsAsString()}";
    }

    public function getMountainsAsString(): string
    {
        return join(', ', array_map(fn (Mountain $mountain) => $mountain->value, $this->mountains));
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
        $this->email = $email;

        return $this;
    }

    /**
     * @return Mountain[]
     */
    public function getMountains(): array
    {
        return $this->mountains;
    }

    /**
     * @param array<Mountain> $mountains
     *
     * @return $this
     */
    public function setMountains(array $mountains): self
    {
        $this->mountains = $mountains;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }
}
