<?php

namespace UnitTest\Service;

use App\Document\Booking;
use App\Document\Session;
use App\Document\User;
use App\Repository\BookingRepository;
use App\Repository\SessionRepository;
use App\Service\BookingService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BookingServiceTest extends TestCase
{

    private BookingService $bookingService;

    private BookingRepository|MockObject $bookingRepository;

    private SessionRepository|MockObject $sessionRepository;

    public function setUp(): void
    {
        $this->bookingRepository = $this->createMock(BookingRepository::class);
        $this->sessionRepository = $this->createMock(SessionRepository::class);

        $this->bookingService = new BookingService(
            $this->bookingRepository, $this->sessionRepository);
    }

    public function testCancel(): void
    {
        $session = new Session(
            "Anglais",
            new \DateTime(),
            "04:00",
            "Casablanca",
            2
        );

        $user = $this->createStub(User::class);

        $booking = new Booking($session, $user);

        $this->sessionRepository->expects(self::once())
            ->method('save');

        $this->bookingRepository->expects(self::once())
            ->method('delete');

        $this->bookingService->cancel($booking);
    }
}
