<?php

namespace App\Entity\Traits;

use ApiPlatform\Metadata\ApiProperty;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait TimestampTrait
{
    #[Groups(groups: ['timestamp'])]
    #[ApiProperty(
        description: 'Creation date and time.',
        required: false,
        example: '2022-11-28T10:02:42+00:00'
    )]
    #[ORM\Column(type: 'datetimetz_immutable', nullable: true, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[Groups(groups: ['timestamp'])]
    #[ApiProperty(
        description: 'Last update date and time.',
        required: false,
        example: '2022-11-28T10:02:42+00:00'
    )]
    #[ORM\Column(type: 'datetimetz', nullable: false)]
    private ?\DateTime $updatedAt = null;

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedValue(): void
    {
        $this->setCreatedAt(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedValue(): void
    {
        $this->setUpdatedAt(new \DateTime('now', new \DateTimeZone('UTC')));
    }
}
