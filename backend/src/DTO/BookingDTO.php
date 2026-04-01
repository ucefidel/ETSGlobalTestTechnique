<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class BookingDTO
{
    #[Assert\NotBlank]
    public string $sessionId;
}
