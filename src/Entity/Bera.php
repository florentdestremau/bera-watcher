<?php

namespace App\Entity;

use App\Model\Mountain;
use App\Repository\BeraRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BeraRepository::class)]
#[ORM\UniqueConstraint(fields: ['mountain', 'date'])]
class Bera
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, enumType: 'App\Model\Mountain')]
    private Mountain $mountain;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $link = null;

    public function __construct(
        Mountain $mountain,
        \DateTimeInterface $date,
        string $link,
    ) {
        $this->mountain = $mountain;
        $this->date = $date;
        $this->link = $link;
    }

    public function __toString(): string
    {
        return sprintf('%s - %s', $this->mountain->value, $this->date->format('Y-m-d'));
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getMountain(): Mountain
    {
        return $this->mountain;
    }

    public function setMountain(Mountain $mountain): self
    {
        $this->mountain = $mountain;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }
}
