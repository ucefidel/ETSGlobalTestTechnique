<?php

namespace App\Controller;

use App\DTO\User\LoginDTO;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/', name: 'api_')]
class LoginController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly JWTTokenManagerInterface $jwtToken
    )
    {
    }

    #[Route('login', name: 'login', methods: [Request::METHOD_POST])]
    public function login(#[MapRequestPayload] LoginDTO $loginDTO): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['email' => $loginDTO->email]);
        $isValid = $this->passwordHasher->isPasswordValid($user, $loginDTO->password);

        if (!$user || !$isValid) {
            return new JsonResponse(['message' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }

        $token = $this->jwtToken->create($user);

        return new JsonResponse(['token' => $token], Response::HTTP_OK);
    }
}
