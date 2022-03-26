<?php

namespace App\Entity;

use App\Repository\ActorRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActorRepository::class)
 */
class Actor
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datebirth;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datedeath;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $placebirth;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDatebirth(): ?\DateTimeInterface
    {
        return $this->datebirth;
    }

    public function setDatebirth(?\DateTimeInterface $datebirth): self
    {
        $this->datebirth = $datebirth;

        return $this;
    }

    public function getDatedeath(): ?\DateTimeInterface
    {
        return $this->datedeath;
    }

    public function setDatedeath(?\DateTimeInterface $datedeath): self
    {
        $this->datedeath = $datedeath;

        return $this;
    }

    public function getPlacebirth(): ?string
    {
        return $this->placebirth;
    }

    public function setPlacebirth(?string $placebirth): self
    {
        $this->placebirth = $placebirth;

        return $this;
    }
}
