<?php

namespace App\Service;

use App\Document\Session;
use App\DTO\SessionDTO;
use App\Repository\SessionRepository;
use DateTime;

class SessionService
{
    private SessionRepository $repository;

    public function __construct(SessionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createSession(SessionDTO $sessionDTO): Session
    {
        $session = new Session(
            $sessionDTO->language,
            new DateTime($sessionDTO->dateAt),
            $sessionDTO->hourAt,
            $sessionDTO->location,
            $sessionDTO->availableSeats
        );

        $this->repository->save($session);

        return $session;
    }

    public function updateSession(Session $session, SessionDTO $sessionDTO): Session
    {
        $session->update($sessionDTO->language, new DateTime($sessionDTO->dateAt),
            $sessionDTO->hourAt, $sessionDTO->location, $sessionDTO->availableSeats);

        $this->repository->save($session);

        return $session;
    }

    public function deleteSession(Session $session): void
    {
        $this->repository->delete($session);
    }
}
