<?php

namespace App\Controller;

use App\Document\User;
use App\DTO\User\RegisterDTO;
use App\DTO\User\UpdateUserDTO;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/', name: 'api_users_')]
class UserController extends AbstractController
{
    public function __construct(private readonly UserService $userService)
    {
    }

    #[Route('register', name: 'register', methods: [Request::METHOD_POST])]
    public function register(#[MapRequestPayload] RegisterDTO $registerDTO): JsonResponse
    {
        $this->userService->createUser($registerDTO);

        return new JsonResponse(
            ['message' => 'User registered successfully'],
            Response::HTTP_CREATED
        );
    }

    #[Route('users/me', name: 'me', methods: [Request::METHOD_GET])]
    public function me(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        return new JsonResponse($user->toArray());
    }

    #[Route('users/me', name: 'update', methods: [Request::METHOD_PUT])]
    public function updateUser(#[MapRequestPayload] UpdateUserDTO $updateUserDTO): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $this->userService->updateUser($user, $updateUserDTO);

        return new JsonResponse($user->toArray(), Response::HTTP_OK);
    }
}
