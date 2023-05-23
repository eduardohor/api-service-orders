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
