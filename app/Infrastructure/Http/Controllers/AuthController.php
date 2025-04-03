<?php

namespace App\Infrastructure\Http\Controllers;

use App\Application\UseCases\RegisterUserUseCase;
use App\Application\UseCases\LoginUserUseCase;
use App\Infrastructure\Persistence\Eloquent\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Firebase\JWT\JWT;

class AuthController extends Controller
{
    private $registerUserUseCase;
    private $loginUserUseCase;

    public function __construct()
    {
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
            
            // Genera token para el usuario recién registrado
            $tokenData = $this->generateToken($user);

            return response()->json([
                'message' => 'Usuario registrado exitosamente',
                'user'    => [
                    'id'         => $user->getId(),
                    'email'      => $user->getEmail(),
                    'name'       => $user->getFirstName() . ' ' . $user->getLastName(),
                    'token'      => $tokenData['token'],
                    'expires_in' => $tokenData['expires_in']
                ]
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

            // Genera token para el usuario autenticado
            $tokenData = $this->generateToken($user);

            return response()->json([
                'message' => 'Inicio de sesión exitoso',
                'user'    => [
                    'id'         => $user->getId(),
                    'email'      => $user->getEmail(),
                    'name'       => $user->getFirstName() . ' ' . $user->getLastName(),
                    'token'      => $tokenData['token'],
                    'expires_in' => $tokenData['expires_in']
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    /**
     * Genera un token JWT para el usuario.
     *
     * @param $user
     * @return array
     */
    private function generateToken($user)
    {
        $issuedAt = time();
        $ttl = 20; // El token durará 20 segundos
        $expirationTime = $issuedAt + $ttl;
        $payload = [
            'iss' => env('APP_URL', 'http://localhost'), // Emisor
            'sub' => $user->getId(),                       // Identificador del usuario
            'iat' => $issuedAt,                            // Tiempo de emisión
            'exp' => $expirationTime                       // Tiempo de expiración (timestamp)
        ];
    
        $jwtSecret = env('JWT_SECRET', 'a3f9c1b7d4e8f2a5c6d3e0b1f7a9d2c4');
        $token = JWT::encode($payload, $jwtSecret, 'HS256');
    
        return [
            'token'      => $token,
            'expires_in' => $ttl  // Retorna 20, es decir, 20 segundos de vida
        ];
    }  
}
