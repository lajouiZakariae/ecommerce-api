<?php

namespace App\Services;

use App\Enums\Role;
use App\Exceptions\AppExceptions\BadRequestException;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class UserService
{
    private string $notFoundMessage = 'User Not Found';

    /**
     * @return Collection<int,User>
     */
    public function getAllUsersExceptAdmin(): Collection
    {
        $users = User::whereNot('role_id', Role::ADMIN)->get();

        return $users;
    }

    /**
     * @param array $userPayload
     * 
     * @return User
     */
    public function createUser(array $userPayload): User
    {
        $user = new User($userPayload);

        if (!$user->save()) throw new BadRequestException('User Could not be created');

        return $user->load('role');
    }

    /**
     * @param int $userId
     * @param array $userPayload
     * 
     * @return bool
     */
    public function updateUser($userId, array $userPayload): bool
    {
        $affectedRowCount = User::where($userId)->update($userPayload);

        if ($affectedRowCount === 0) throw new ResourceNotFoundException($this->notFoundMessage);

        return true;
    }

    /**
     * @param int $userId
     * 
     * @return bool
     */
    public function deleteUser($userId): bool
    {
        $affectedRowCount = User::destroy($userId);

        if ($affectedRowCount === 0) throw new ResourceNotFoundException($this->notFoundMessage);

        return true;
    }
}
