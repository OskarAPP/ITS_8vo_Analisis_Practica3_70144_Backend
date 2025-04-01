<?php

namespace App\Application\UseCases;

use App\Domain\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class LoginUserUseCase
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(string $email, string $password)
    {
        $user = $this->userRepository->findByEmail($email);
        if (!$user) {
            throw new \Exception('Credenciales inválidas');
        }
        // Verificar la contraseña
        if (!Hash::check($password, $user->getPassword())) {
            throw new \Exception('Credenciales inválidas');
        }
        // Aquí se podría generar y retornar un token
        return $user;
    }
}
