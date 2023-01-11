<?php

namespace App\Entity;

use App\Model\Mountain;
use App\Model\Weekday;
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

    /**
     * @var array<Weekday>
     */
    #[ORM\Column(type: Types::SIMPLE_ARRAY, enumType: 'App\Model\Weekday')]
    private array $weekdays;

    #[ORM\Column(length: 255)]
    private string $token;

    #[ORM\Column(length: 255)]
    private string $editLink;

    public function __construct()
    {
        $this->token = bin2hex(random_bytes(8));
        $this->weekdays = Weekday::cases();
    }

    public function __toString(): string
    {
        return "{$this->email}: {$this->getWeekdaysAsString()} - {$this->getMountainsAsString()}";
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

    public function getEditLink(): string
    {
        return $this->editLink;
    }

    public function setEditLink(string $editLink): self
    {
        $this->editLink = $editLink;

        return $this;
    }

    /**
     * @return array<Weekday>
     */
    public function getWeekdays(): array
    {
        return $this->weekdays;
    }

    /**
     * @param array<Weekday> $weekdays
     *
     * @return Subscriber
     */
    public function setWeekdays(array $weekdays): Subscriber
    {
        $this->weekdays = $weekdays;

        return $this;
    }

    public function hasDay(int $day): bool
    {
        return in_array(Weekday::fromNumber($day), $this->weekdays);
    }

    private function getWeekdaysAsString(): string
    {
        return join(', ', array_map(fn (Weekday $weekday) => $weekday->value, $this->weekdays));
    }
}
