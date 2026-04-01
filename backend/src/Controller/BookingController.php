<?php

declare(strict_types=1);

namespace App\Controller;

use App\Document\Booking;
use App\Document\User;
use App\DTO\BookingDTO;
use App\Repository\BookingRepository;
use App\Service\BookingService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/bookings', name: 'api_bookings_')]
class BookingController extends AbstractController
{

    private BookingService $bookingService;
    private BookingRepository $bookingRepository;

    public function __construct(BookingService $bookingService, BookingRepository $bookingRepository)
    {
        $this->bookingService = $bookingService;
        $this->bookingRepository = $bookingRepository;
    }

    #[Route('/me', name: 'me', methods: ['GET'])]
    public function myBooking(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $bookings = $this->bookingRepository->findByUser($user);

        return $this->json(
            array_map(fn(Booking $booking) => $booking->toArray(), $bookings)
        );
    }


    #[Route('/book', name: 'book', methods: [Request::METHOD_POST])]
    public function book(#[MapRequestPayload] BookingDTO $bookingDTO): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

      try {
          $booking = $this->bookingService->book($bookingDTO->sessionId, $user);

          return $this->json($booking->toArray(), Response::HTTP_CREATED);

      }catch (Exception $exception){
          return $this->json(['error' => $exception->getMessage()], $exception->getCode());
      }

    }

    #[Route('/cancel/{id}', name: 'cancel', methods: [Request::METHOD_DELETE])]
    public function cancel(string $id): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        /** @var ?Booking $booking */
        $booking = $this->bookingRepository->findOneBy(['id' => $id ]);

        if (null === $booking){
            return $this->json(['error' => 'Booking not found'], Response::HTTP_NOT_FOUND);
        }

        if ($user->getId() !== $booking->getUser()->getId()) {
            return $this->json(['error' => 'You are not allowed to cancel this booking'], Response::HTTP_FORBIDDEN);
        }

        $this->bookingService->cancel($booking);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

}
