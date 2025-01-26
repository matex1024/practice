<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ReportDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public readonly string $name,

        #[Assert\NotBlank]
        #[Assert\Type('dateTime')]
        public readonly DateTime $dateTime,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public readonly string $userName,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public readonly string $room,
    ) {
    }
}