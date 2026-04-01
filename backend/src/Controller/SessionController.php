<?php

namespace App\Controller;

use App\DTO\SessionDTO;
use App\Repository\SessionRepository;
use App\Service\SessionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Json;

#[Route('/api/sessions', name: 'api_sessions_')]
class SessionController extends AbstractController
{
    private SessionRepository $sessionRepository;
    private SessionService $sessionService;

    public function __construct(
        SessionRepository $sessionRepository,
        SessionService    $sessionService
    )
    {
        $this->sessionRepository = $sessionRepository;
        $this->sessionService = $sessionService;
    }

    #[Route('', name: 'list', methods: ['GET'])]
    public function list(#[MapQueryParameter] int $page = 1, #[MapQueryParameter] int $limit = 10, #[MapQueryParameter] bool $availableOnly = false): JsonResponse
    {
        $offset = ($page - 1) * $limit;

        $sessions = $this->sessionRepository->findPagination($offset, $limit, $availableOnly);
        $total = $this->sessionRepository->countPagination($availableOnly);

        return new JsonResponse([
            'data' => array_map(fn($session) => $session->toArray(), $sessions),
            'total' => $total,
            'pages' => (int) ceil($total / $limit),
            'limit' => $limit,
            'page' => $page
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function getOne(string $id): JsonResponse
    {
        $session = $this->sessionRepository->findOneBy(['id' => $id]);

        if(null === $session){
            return new JsonResponse(['error' => 'Session no found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($session->toArray());
    }

    #[Route('', name: 'add', methods: ['POST'])]
    public function add(#[MapRequestPayload] SessionDTO $sessionDTO): JsonResponse
    {

        $session = $this->sessionService->createSession($sessionDTO);

        return new JsonResponse($session->toArray(), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(string $id, #[MapRequestPayload] SessionDTO $sessionDTO): JsonResponse
    {
        $session = $this->sessionRepository->findOneBy(['id' => $id]);

        if(null === $session){
            return new JsonResponse(['error' => 'Session not found'],
            Response::HTTP_NOT_FOUND);
        }

        $this->sessionService->updateSession($session, $sessionDTO);

        return new JsonResponse($session->toArray());
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(string $id): JsonResponse
    {

        $session = $this->sessionRepository->findOneBy(['id' => $id]);

        if(null === $session){
            return new JsonResponse(['error' => 'Session no found'],
            Response::HTTP_NOT_FOUND);
        }

        $this->sessionService->deleteSession($session);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
