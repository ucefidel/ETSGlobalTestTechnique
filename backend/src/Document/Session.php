<?php

namespace App\Document;

use DateTimeInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;


#[ODM\Document(collection: 'sessions')]
class Session
{

    #[ODM\Id]
    private ?string $id = null;

    #[ODM\Field(type: 'string')]
    private string $language;

    #[ODM\Field(type: 'date')]
    private DateTimeInterface $dateAt;

    #[ODM\Field(type: 'string')]
    private string $hourAt;

    #[ODM\Field(type: 'string')]
    private string $location;

    #[ODM\Field(type: 'int')]
    private int $availableSeats;

    public function __construct(
        string $language,
        DateTimeInterface $dateAt,
        string $hourAt, string $location, int $availableSeats)
    {
        $this->availableSeats = $availableSeats;
        $this->location = $location;
        $this->hourAt = $hourAt;
        $this->dateAt = $dateAt;
        $this->language = $language;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getDateAt(): DateTimeInterface
    {
        return $this->dateAt;
    }

    public function getHourAt(): string
    {
        return $this->hourAt;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getAvailableSeats(): int
    {
        return $this->availableSeats;
    }

    public function update(
        string $language,
        DateTimeInterface $dateAt,
        string $hourAt,
        string $location, int $availableSeats): void
    {
        $this->dateAt = $dateAt;
        $this->hourAt = $hourAt;
        $this->location = $location;
        $this->availableSeats = $availableSeats;
        $this->language = $language;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'language' => $this->language,
            'dateAt' => $this->dateAt->format('Y-m-d'),
            'hourAt' => $this->hourAt,
            'location' => $this->location,
            'availableSeats' => $this->availableSeats
        ];
    }

    public function increaseSeats(): void
    {
        $this->availableSeats++;
    }

    public function decreaseSeats(): void
    {
        $this->availableSeats--;
    }

}
