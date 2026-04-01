<?php

namespace UnitTest\Service;

use App\Document\Session;
use App\DTO\SessionDTO;
use App\Repository\SessionRepository;
use App\Service\SessionService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SessionServiceTest extends TestCase
{
    private SessionService $sessionService;

    private SessionRepository|MockObject $sessionRepository;

    public function setUp(): void
    {
        $this->sessionRepository = $this->createMock(SessionRepository::class);

        $this->sessionService = new SessionService($this->sessionRepository);
    }

    public function testCreateSession()
    {
        $sessionDTO = $this->createSessionDTO();

        $this->sessionRepository->expects(self::once())
            ->method('save');

        $this->sessionService->createSession($sessionDTO);
    }

    public function testUpdateSession()
    {
        $session = $this->createSessionDocument();
        $sessionDTO = $this->createSessionDTO();

        $this->sessionRepository->expects(self::once())
            ->method('save');

        $this->sessionService->updateSession($session, $sessionDTO);
    }

    public function testDeleteSession()
    {
        $session = $this->createSessionDocument();

        $this->sessionRepository->expects(self::once())
            ->method('delete');

        $this->sessionService->deleteSession($session);
    }

    private function createSessionDTO(): SessionDTO
    {
        $sessionDTO = new SessionDTO();
        $sessionDTO->language = "French";
        $sessionDTO->availableSeats=12;
        $sessionDTO->location= 'Paris';
        $sessionDTO->hourAt = "02:00";
        $sessionDTO->dateAt = "2023-01-01";

        return $sessionDTO;
    }

    private function createSessionDocument(): Session
    {
        return (new Session("Français",
            new \DateTime("2023-01-01"), "04-00",
            "Lyon", 3));
    }
}
