<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    /**
     * Se logea al usuario
     */
    public function signIn(Request $request)
    {
        try {
            $rules = [
                'email' => 'required|exists:users',
                'password'  => 'required'
            ];

            $request->validate($rules);

            $data = [
                'email' => $request->get('email'),
                'password'  =>  $request->get('password')
            ];

            if (Auth::attempt($data)) {
                $user = $request->user();
                return response()->json([
                    'user'  =>  $user,
                    'token' => $user->createToken('authToken')->accessToken
                ], 201);
            } else {
                throw new \Exception('Unauthorized', 401);
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? 402;
            return response()->json($e->getMessage(), $code);
        }
    }

    /**
     * Se registra los usuarios
     */
    public function signUp(FormUser $userRequest)
    {
        try {
            if ($userRequest->hasErrors()) {
                return response()->json(
                    [
                        'errors' => $userRequest->getErrors(),
                        'success' => false
                    ],
                    200
                );
            }
            $user = new User($userRequest->all());
            $user->password = Hash::make($userRequest->input('password'));
            $user->save();
            return response()->json(
                [
                    'message' => 'Usuario creado. Hemos enviado un correo a tu email para confirmar tu cuenta',
                    'success' => true
                ],
                201
            );
        } catch (\Exception $e) {
            $code = $e->getCode() ?? 402;
            return response()->json($e->getMessage(), $code);
        }
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
