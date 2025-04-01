<?php

namespace App\Infrastructure\Http\Controllers;

use App\Application\UseCases\RegisterUserUseCase;
use App\Application\UseCases\LoginUserUseCase;
use App\Infrastructure\Persistence\Eloquent\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    private $registerUserUseCase;
    private $loginUserUseCase;

    public function __construct()
    {
        // Para simplificar, se instancia manualmente el repositorio.
        // En una aplicación real, se recomienda utilizar el Service Container.
        $userRepository = new UserRepository();
        $this->registerUserUseCase = new RegisterUserUseCase($userRepository);
        $this->loginUserUseCase    = new LoginUserUseCase($userRepository);
    }

    public function register(Request $request)
    {
        $data = $request->only(['first_name', 'last_name', 'email', 'password']);

        try {
            $user = $this->registerUserUseCase->execute(
                $data['first_name'],
                $data['last_name'],
                $data['email'],
                $data['password']
            );
            return response()->json([
                'message' => 'Usuario registrado exitosamente',
                'user'    => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function login(Request $request)
    {
        $data = $request->only(['email', 'password']);

        try {
            $user = $this->loginUserUseCase->execute(
                $data['email'],
                $data['password']
            );
            // En un caso real, aquí se generaría un token de autenticación
            return response()->json([
                'message' => 'Inicio de sesión exitoso',
                'user'    => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }
}
