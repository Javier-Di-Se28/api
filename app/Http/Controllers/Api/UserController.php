<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
     // 1. Listar todos los usuarios
     public function index()
     {
         $users = User::all();
         return response()->json($users);
     }
 
     // 2. Mostrar un usuario específico por su ID
     public function show($id)
     {
         $user = User::find($id);
 
         if (!$user) {
             return response()->json(['message' => 'Usuario no encontrado'], 404);
         }
 
         return response()->json($user);
     }
 
     // 3. Crear un nuevo usuario
     public function store(Request $request)
     {
         // Validar los datos recibidos
         $request->validate([
             'name' => 'required|string|max:255',
             'email' => 'required|string|email|max:255|unique:users',
             'password' => 'required|string|min:6',
         ]);
 
         // Crear el usuario
         $user = User::create([
             'name' => $request->name,
             'email' => $request->email,
             'password' => bcrypt($request->password),
         ]);
 
         return response()->json($user, 201);
     }
 
     // 4. Actualizar un usuario existente
     public function update(Request $request, $id)
     {
         $user = User::find($id);
 
         if (!$user) {
             return response()->json(['message' => 'Usuario no encontrado'], 404);
         }
 
         // Validar los datos
         $request->validate([
             'name' => 'sometimes|required|string|max:255',
             'email' => 'sometimes|required|string|email|max:255|unique:users,email,'.$id,
             'password' => 'sometimes|required|string|min:6',
         ]);
 
         // Actualizar los datos del usuario
         $user->update([
             'name' => $request->name ?? $user->name,
             'email' => $request->email ?? $user->email,
             'password' => $request->password ? bcrypt($request->password) : $user->password,
         ]);
 
         return response()->json($user);
     }
 
     // 5. Eliminar un usuario
     public function destroy($id)
     {
         $user = User::find($id);
 
         if (!$user) {
             return response()->json(['message' => 'Usuario no encontrado'], 404);
         }
 
         $user->delete();
 
         return response()->json(['message' => 'Usuario eliminado correctamente']);
     }
}
