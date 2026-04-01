<?php

namespace App\Document;

use App\Repository\SessionRepository;
use App\Repository\UserRepository;
use DateTimeInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'bookings')]
class Booking
{
    #[ODM\Id]
    private ?string $id = null;

    #[ODM\ReferenceOne(targetDocument: Session::class)]
    private Session $session;

    #[ODM\ReferenceOne(targetDocument: User::class)]
    private User $user;

    #[ODM\Field(type: 'date')]
    private DateTimeInterface $bookedAt;

    public function __construct(Session $session, User $user)
    {
        $this->session = $session;
        $this->user = $user;
        $this->bookedAt = new \DateTime();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUser(): User
    {

        return $this->user;
    }

    public function getSession(): Session
    {
        return $this->session;
    }

    public function getBookedAt(): DateTimeInterface
    {
        return $this->bookedAt;
    }

    public function toArray(): array
    {

        return [
            'id' => $this->id,
            'session' => $this->session->toArray(),
            'bookedAt' => $this->bookedAt->format('Y-m-d H:i:s')
        ];
    }

}
