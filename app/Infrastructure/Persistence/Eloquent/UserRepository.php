<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Contracts\UserRepositoryInterface;
use App\Domain\Entities\User;
use App\Models\User as EloquentUser;

class UserRepository implements UserRepositoryInterface
{
    public function findByEmail(string $email): ?User
    {
        $eloquentUser = EloquentUser::where('email', $email)->first();
        if (!$eloquentUser) {
            return null;
        }
        return new User(
            $eloquentUser->first_name,
            $eloquentUser->last_name,
            $eloquentUser->email,
            $eloquentUser->password,
            $eloquentUser->id
        );
    }

    public function save(User $user): User
    {
        $eloquentUser = new EloquentUser();
        $eloquentUser->first_name = $user->getFirstName();
        $eloquentUser->last_name  = $user->getLastName();
        $eloquentUser->email      = $user->getEmail();
        $eloquentUser->password   = $user->getPassword();
        $eloquentUser->save();

        return new User(
            $eloquentUser->first_name,
            $eloquentUser->last_name,
            $eloquentUser->email,
            $eloquentUser->password,
            $eloquentUser->id
        );
    }
}
