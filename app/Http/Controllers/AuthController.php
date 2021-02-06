<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Support\Facades\Password;
use App\ApiCode;

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
            $user->sendEmailVerificationNotification();
            return response()->json(
                [
                    'message' => 'Usuario creado.',
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
     * Verify email
     *
     * @param $user_id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function verify($user_id, Request $request)
    {
        if (!$request->hasValidSignature()) {
            return $this->respondUnAuthorizedRequest(ApiCode::INVALID_EMAIL_VERIFICATION_URL);
        }

        $user = User::findOrFail($user_id);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect()->to('/');
    }

    public function forgot()
    {
        request()->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(request()->only('email'));
        return $status === Password::RESET_LINK_SENT ?
            response()->json(['message' => 'Te hemos enviado la recuperación de tu contraseña a tu correo', 'success' => true], 201)
            : response()->json(['message' => __($status), 'success' => true], 200);
    }


    public function reset(ResetPasswordRequest $request)
    {
        if ($request->hasErrors()) {
            return response()->json(
                [
                    'errors' => $request->getErrors(),
                    'success' => false
                ],
                200
            );
        }
        $reset_password_status = Password::reset($request->validated(), function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        });

        if ($reset_password_status == Password::INVALID_TOKEN) {
            return response()->json(['message' => __($reset_password_status)], ApiCode::INVALID_RESET_PASSWORD_TOKEN);
        }

        return response()->json(['message' => "La contraseña ha sido cambiada exitosamente"], 201);
    }
}
