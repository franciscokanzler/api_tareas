<?php

namespace App\Http\Controllers;

use App\Models\Miembros;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    //método para autenticación en el api
    public function login(Request $request){
        $rules = [
            'email' => 'required',
            'password' => 'required',
        ];

        $ErrorMessages = [
            'email.required' => 'Estimado usuario, el email es requerido ',
            'password.required' => 'Estimado usuario, el nombre es requerido ',
        ];

        $validator = Validator::make($request->all(), $rules, $ErrorMessages);
        if ($validator->fails()) {
            return response()->json([
                'created' => false,
                'errors'  => $validator->errors()
            ], 400);
        }

        try {
            if(Auth::attempt(['email' => $request['email'], 'password' => $request['password']])){
                $user = Auth::user();
                $token = $user->createToken('token')->plainTextToken;
                return response()->json([
                    'user' => $user,
                    'token' => $token,
                ], 200);
            }else{
                return response()->json([
                    'mensaje' => 'Credenciales Inválidas',
                ], 400);
            }
        } catch (\Throwable $th) {
            Log::error('Error función user.login: ' . $th->getMessage());
            Log::error('Archivo: ' . $th->getFile());
            Log::error('Línea: ' . $th->getLine());
            return response()->json([
                'mensaje' => 'Estimado usuario, en estos momentos el servidor esta fuera de servicio, por favor intente mas tarde.',
            ], 400);
        }
    }

    //método para registrar nuevo usuario
    public function store(Request $request){
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ];

        $ErrorMessages = [
            'name.required' => 'Estimado usuario, el nombre es requerido. ',
            'email.required' => 'Estimado usuario, el email es requerido. ',
            'email.unique' => 'Estimado usuario, el email suministrado ya existe, ingrese otro email. ',
            'password.required' => 'Estimado usuario, el password es requerido. ',
        ];

        $validator = Validator::make($request->all(), $rules, $ErrorMessages);
        if ($validator->fails()) {
            return response()->json([
                'created' => false,
                'errors'  => $validator->errors()
            ], 400);
        }

        try {
            $user = User::create([
                'name' => $request['name'],
                'email'=> $request['email'],
                'password'=> bcrypt($request['password']),
            ]);

            return response()->json([
                'data' => $user,
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Error función user.store: ' . $th->getMessage());
            Log::error('Archivo: ' . $th->getFile());
            Log::error('Línea: ' . $th->getLine());
            return response()->json([
                'mensaje' => 'Estimado usuario, en estos momentos el servidor esta fuera de servicio, por favor intente mas tarde.',
            ], 400);
        }
    }

    //método para cerrar sesión
    public function logout(){
        try {
            auth()->user()->tokens()->delete();
            return response()->json([
                'mensaje' => 'cierre de sesión exitoso',
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Error función user.logout: ' . $th->getMessage());
            Log::error('Archivo: ' . $th->getFile());
            Log::error('Línea: ' . $th->getLine());
            return response()->json([
                'mensaje' => 'Estimado usuario, en estos momentos el servidor esta fuera de servicio, por favor intente mas tarde.',
            ], 400);
        }
    }
}
