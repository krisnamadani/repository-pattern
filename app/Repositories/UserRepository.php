<?php

namespace App\Repositories;

use App\Models\User;
use App\Traits\ResponseAPI;
use App\Interfaces\UserInterface;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserInterface
{
    use ResponseAPI;

    public function getAllUsers()
    {
        try {
            $users = User::all();
            return $this->success("All Users", $users);
        } catch(\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function getUserById($id)
    {
        try {
            $user = User::find($id);
            
            if(!$user) return $this->error("No user with ID $id", 404);

            return $this->success("User Detail", $user);
        } catch(\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function requestUser(UserRequest $request, $id = null)
    {
        DB::beginTransaction();
        try {
            $user = $id ? User::find($id) : new User;

            if($id && !$user) return $this->error("No user with ID $id", 404);

            $user->name = $request->name;
            $user->email = preg_replace('/\s+/', '', strtolower($request->email));
            
            if(!$id) $user->password = Hash::make($request->password);

            $user->save();

            DB::commit();
            return $this->success(
                $id ? "User updated"
                    : "User created",
                $user, $id ? 200 : 201);
        } catch(\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function deleteUser($id)
    {
        DB::beginTransaction();
        try {
            $user = User::find($id);

            if(!$user) return $this->error("No user with ID $id", 404);

            $user->delete();

            DB::commit();
            return $this->success("User deleted", $user);
        } catch(\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}