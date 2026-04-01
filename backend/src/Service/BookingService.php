<?php

namespace App\Service;

use App\Document\Booking;
use App\Document\Session;
use App\Document\User;
use App\Repository\BookingRepository;
use App\Repository\SessionRepository;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class BookingService
{
    private BookingRepository $bookingRepository;
    private SessionRepository $sessionRepository;

    public function __construct(BookingRepository $bookingRepository, SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
        $this->bookingRepository = $bookingRepository;
    }

    public function book(string $sessionId, User $user): Booking
    {
        /** @var Session $session */
        $session = $this->sessionRepository->findOneBy(['id' => $sessionId]);

        if (null === $session) {
            throw new Exception('Session not found', Response::HTTP_NOT_FOUND);
        }

        if (0 >= $session->getAvailableSeats()) {
            throw new Exception('No available seats', Response::HTTP_CONFLICT);
        }

        if($this->bookingRepository->findByUserAndSession($user, $session)){
            throw new Exception('User already booked this session', Response::HTTP_CONFLICT);
        }

        $session->decreaseSeats();
        $this->sessionRepository->save($session);

        $booking = new Booking($session, $user);
        $this->bookingRepository->save($booking);

        return $booking;

    }

    public function cancel(Booking $booking): void
    {
        $session = $booking->getSession();
        $session->increaseSeats();

        $this->sessionRepository->save($session);

        $this->bookingRepository->delete($booking);
    }
}
