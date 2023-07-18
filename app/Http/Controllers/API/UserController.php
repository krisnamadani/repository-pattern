<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Interfaces\UserInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    protected $userInterface;

    public function __construct(UserInterface $userInterface)
    {
        $this->userInterface = $userInterface;
    }
    
    public function index()
    {
        return $this->userInterface->getAllUsers();
    }

    public function store(UserRequest $request)
    {
        return $this->userInterface->requestUser($request);
    }

    public function show($id)
    {
        return $this->userInterface->getUserById($id);
    }

    public function update(UserRequest $request, $id)
    {
        return $this->userInterface->requestUser($request, $id);
    }

    public function destroy($id)
    {
        return $this->userInterface->deleteUser($id);
    }
}
