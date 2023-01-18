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
    private ?\DateTimeInterface $date;

    #[ORM\Column(length: 255)]
    private string $hash;

    #[ORM\Column(length: 255)]
    private string $link;

    #[ORM\Column(length: 255)]
    private string $xmlLink;

    #[ORM\Column(type: Types::TEXT)]
    private string $xml;

    public function __construct(
        Mountain $mountain,
        \DateTimeInterface $date,
        string $hash,
        string $link,
        string $xmlLink,
        string $xml,
    ) {
        $this->mountain = $mountain;
        $this->date = $date;
        $this->hash = $hash;
        $this->link = $link;
        $this->xmlLink = $xmlLink;
        $this->xml = $xml;
    }

    public function __toString(): string
    {
        return sprintf('%s %s', $this->mountain->value, $this->date->format('d/m/Y'));
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

    public function getXmlLink(): string
    {
        return $this->xmlLink;
    }

    public function setXmlLink(string $xmlLink): self
    {
        $this->xmlLink = $xmlLink;

        return $this;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getXml(): ?string
    {
        return $this->xml;
    }

    public function setXml(string $xml): self
    {
        $this->xml = $xml;

        return $this;
    }
}
