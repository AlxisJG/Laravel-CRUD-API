<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormUser;
use Illuminate\Http\Request;
use App\User;

/**
* @OA\Info(title="API Usuarios", version="1.0")
*
* @OA\Server(url="http://localhost:5000")
*/
class UserController extends Controller
{
    /**
    * @OA\Get(
    *     path="/api/users",
    *     summary="Mostrar usuarios",
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar todos los usuarios."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function index(Request $request)
    {
        return User::all();
    }

    public function show($id)
    {

    }

    public function store(FormUser $userRequest)
    {

    }

    public function update(FormUser $userRequest)
    {

    }

    public function delete(Request $request)
    {

    }
}
