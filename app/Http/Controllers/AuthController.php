<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormUser;
use App\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    /**
     * Se logea al usuario
     */
    public function signIn(Request $request)
    {

    }

    /**
     * Se registra los usuarios
     */
    public function signUp(FormUser $userRequest)
    {

    }

    /**
     * Se recupera la password
     */
    public function recoveryPassword(Request $request)
    {
        try {
            $userExist = (bool) User::where('email', $request->input('email'))->count();
            if (!$userExist) {
                throw new \Exception('No encontramos un usuario con el correo que nos ingresa', 404);
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }
}
