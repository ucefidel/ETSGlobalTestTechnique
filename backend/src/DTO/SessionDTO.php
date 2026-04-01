<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class SessionDTO
{

    #[Assert\NotBlank]
    public string $language;

    #[Assert\NotBlank]
    public string $dateAt;

    #[Assert\NotBlank]
    public string $hourAt;

    #[Assert\NotBlank]
    public string $location;

    #[Assert\NotBlank]
    #[Assert\Type('int')]
    public int $availableSeats;
}
