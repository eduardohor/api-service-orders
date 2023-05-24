<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @OA\POST(
     *      tags={"Usuário"},
     *      summary="Criar novo usuário",
     *      description="Este endpoint cria um novo usuário",
     *      path="/api/users",
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                required={"name"},
     *              @OA\Property(property="name", type="string", example="Eduardo Henrique", description="Nome do usuário")
     *          )
     *      ),
     *  ),
     *  @OA\Response(
     *      response=201,
     *      description="Usuário criado",
     *      @OA\JsonContent(
     *          @OA\Property(property="name", type="string", example="Eduardo Henrique", description="Nome do usuário"),
     *          @OA\Property(property="id", type="integer", example="1", description="Id do usuário")
     *    )
     *  ),
     *  @OA\Response(
     *      response=422,
     *      description="Campos incorretos",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="O campo nome é obrigatório"),
     *          @OA\Property(property="errors", type="string", example="..."),
     *    )
     *  )
     * )
     */

    public function create(Request $request)
    {
        $rules = [
            'name' => 'required|string'
        ];

        $feedback = [
            'required' => 'O campo :attribute é obrigatório',
        ];

        $request->validate($rules, $feedback);

        $user = $this->user->create([
            'name' => $request->name
        ]);

        return response()->json($user, 201);
    }
}
