<?php

namespace App\Domain\Entities;

class User
{
    private $id;
    private $firstName;
    private $lastName;
    private $email;
    private $password; // Contraseña encriptada

    public function __construct(string $firstName, string $lastName, string $email, string $password, int $id = null)
    {
        $this->firstName = $firstName;
        $this->lastName  = $lastName;
        $this->email     = $email;
        $this->password  = $password;
        $this->id        = $id;
    }

    // Métodos getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }
    
    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
