<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    
    public function index(User $usuario): JsonResponse
    {
         return response()->json(User::all());
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {

        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'saldo_inicial' => $data['saldo_inicial'],
            'password' => bcrypt('password'),
        ]);

        
        return response()->json($user, 200); 
    }

    /**
     * Display the specified resource.
     */
    public function show(User $usuario): JsonResponse
    {
        return response()->json($usuario);
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $usuario)
    {
        $usuario->update($request->validated());
        return response()->json($usuario);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $usuario): JsonResponse
    {
        $usuario->delete();
        return response()->json(['message' => 'Usuario eliminado correctamente.']);
    }
}
