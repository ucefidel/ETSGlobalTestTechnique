<?php

namespace App\Service;

use App\Document\User;
use App\DTO\User\RegisterDTO;
use App\DTO\User\UpdateUserDTO;
use App\Repository\UserRepository;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher
    )
    {}

    public function createUser(RegisterDTO $userDTO): void
    {
        if ($this->userRepository->findByEmail($userDTO->email)) {
            throw new Exception('Email already exists', Response::HTTP_CONFLICT);
        }

        $user = new User($userDTO->name, $userDTO->email, '');

        $hashPassword = $this->passwordHasher->hashPassword($user, $userDTO->password);
        $user->setPassword($hashPassword);

        $this->userRepository->save($user);
    }

    public function updateUser(User $user, UpdateUserDTO $updateUserDTO): void
    {
        $user->update($updateUserDTO->email, $updateUserDTO->name);

        $this->userRepository->save($user);

    }
}
