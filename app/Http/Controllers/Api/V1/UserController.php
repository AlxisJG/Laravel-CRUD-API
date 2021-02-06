<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormUser;
use Illuminate\Http\Request;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\User;
use Illuminate\Support\Facades\Hash;

/**
* @OA\Info(title="API Usuarios", version="1.0")
*
* @OA\Server(url="http://localhost:5000")
*/
class UserController extends Controller
{
    /**
    * @OA\Get(
    *     path="/api/v1/users",
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
    public function index()
    {
        $users = User::paginate(10);
        return (new UserCollection($users))->response()->setStatusCode(201);
    }

    /**
    * @OA\Get(
    *     path="/api/v1/users/:id",
    *     summary="Muestra un usuario por id",
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar un usuario."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function show($id)
    {
        try {
            $user = new UserResource(User::findOrFail($id));
            return response()->json(['data' => $user, 'success' => true], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No pudimos encontrar al usuario', 'success' => false], 404);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function store(FormUser $userRequest)
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

    public function update(FormUser $userRequest, $id)
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
            $user = User::findOrFail($id);
            $user->update($userRequest->all());
            $userResult = new UserResource($user);
            return response()->json(['data' => $userResult, 'success' => true], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No pudimos encontrar el user', 'success' => false], 404);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @return JsonContent
     */
    public function destroy($id)
    {
        try {
            $item = User::findOrFail($id);
            $item->delete();
            return response()->json(['message' => 'Hemos eliminado al usuario', 'success' => true], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No pudimos encontrar al usuario', 'success' => false], 404);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }
}
