<?php

namespace UnitTest\Service;

use App\Document\Booking;
use App\Document\Session;
use App\Document\User;
use App\Repository\BookingRepository;
use App\Repository\SessionRepository;
use App\Service\BookingService;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

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

    public function testBookSessionNotFound(): void
    {
        $this->sessionRepository->expects(self::once())
            ->method('findOneBy')
            ->willReturn(null);

        $user = $this->createStub(User::class);

        $this->expectException(Exception::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $this->bookingService->book('non-existent-id', $user);
    }

    public function testBookNoAvailableSeats(): void
    {
        $session = new Session("Anglais", new \DateTime(), "10:00", "Paris", 0);

        $this->sessionRepository->expects(self::once())
            ->method('findOneBy')
            ->willReturn($session);

        $user = $this->createStub(User::class);

        $this->expectException(Exception::class);
        $this->expectExceptionCode(Response::HTTP_CONFLICT);

        $this->bookingService->book('session-id', $user);
    }

    public function testBookAlreadyBooked(): void
    {
        $session = new Session("Anglais", new \DateTime(), "10:00", "Paris", 5);
        $user = $this->createStub(User::class);
        $existingBooking = new Booking($session, $user);

        $this->sessionRepository->expects(self::once())
            ->method('findOneBy')
            ->willReturn($session);

        $this->bookingRepository->expects(self::once())
            ->method('findByUserAndSession')
            ->willReturn($existingBooking);

        $this->expectException(Exception::class);
        $this->expectExceptionCode(Response::HTTP_CONFLICT);

        $this->bookingService->book('session-id', $user);
    }

    public function testBookSuccess(): void
    {
        $session = new Session("Anglais", new \DateTime(), "10:00", "Paris", 5);
        $user = $this->createStub(User::class);

        $this->sessionRepository->expects(self::once())
            ->method('findOneBy')
            ->willReturn($session);

        $this->bookingRepository->expects(self::once())
            ->method('findByUserAndSession')
            ->willReturn(null);

        $this->sessionRepository->expects(self::once())
            ->method('save');

        $this->bookingRepository->expects(self::once())
            ->method('save');

        $booking = $this->bookingService->book('session-id', $user);

        self::assertInstanceOf(Booking::class, $booking);
        self::assertEquals(4, $session->getAvailableSeats());
    }
}
