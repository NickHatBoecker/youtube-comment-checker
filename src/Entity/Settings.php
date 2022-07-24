<?php

namespace App\Entity;

use App\Repository\SettingsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SettingsRepository::class)
 */
class Settings
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $jsonSettings;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJsonSettings(): ?string
    {
        return $this->jsonSettings;
    }

    public function setJsonSettings(?string $jsonSettings): self
    {
        $this->jsonSettings = $jsonSettings;

        return $this;
    }
}
