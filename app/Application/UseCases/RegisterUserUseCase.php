<?php

namespace App\Application\UseCases;

use App\Domain\Contracts\UserRepositoryInterface;
use App\Domain\Entities\User;
use Illuminate\Support\Facades\Hash;

class RegisterUserUseCase
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(string $firstName, string $lastName, string $email, string $password): User
    {
        $existingUser = $this->userRepository->findByEmail($email);
        if ($existingUser) {
            throw new \Exception('El usuario ya existe');
        }
        $hashedPassword = Hash::make($password);
        $user = new User($firstName, $lastName, $email, $hashedPassword);
        return $this->userRepository->save($user);
    }
}
