<?php

namespace UnitTest\Service;

use App\Document\User;
use App\DTO\User\RegisterDTO;
use App\DTO\User\UpdateUserDTO;
use App\Repository\UserRepository;
use App\Service\UserService;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserServiceTest extends TestCase
{
    private UserService $userService;

    private UserRepository|MockObject $userRepository;

    private UserPasswordHasherInterface|MockObject $passwordHasher;

    public function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);

        $this->userService = new UserService(
            $this->userRepository,
            $this->passwordHasher
        );
    }

    public function testCreateUserThrowException(): void
    {
        $user = new User("test", "test@email", "test_password");
        $register = new RegisterDTO();
        $register->email = "test@email";

        $this->userRepository->expects(self::once())
            ->method('findByEmail')
            ->willReturn($user);

        $this->passwordHasher->expects(self::never())
            ->method('hashPassword');

        $this->expectException(Exception::class);
        $this->userService->createUser($register);
    }

    public function testCreateUser(): void
    {
        $register = new RegisterDTO();
        $register->email = "test@email";
        $register->name = "test";
        $register->password = "test_password";

        $this->userRepository->expects(self::once())
            ->method('findByEmail')
            ->willReturn(null);

        $this->passwordHasher->expects(self::once())
            ->method('hashPassword')
            ->willReturn("test_password");

        $this->userService->createUser($register);
    }

    public function testUpdateUser()
    {
        $user = new User("test", "test@email", "test_password");

        $updateUser = new UpdateUserDTO();
        $updateUser->email = "test@email";
        $updateUser->name = "test";

        $this->userRepository->expects(self::once())
            ->method('save');

        $this->passwordHasher->expects(self::never())
            ->method('hashPassword');

        $this->userService->updateUser($user, $updateUser);
    }
}
