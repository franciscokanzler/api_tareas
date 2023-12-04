<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class TareaController extends Controller
{
    //método para mostras todas las tareas registradas
    public function index(Request $request, $columna = null, $orden = null)
    {
        try {
            $columna = $request->columna ?? 'id';
            $orden = $request->orden ?? 'asc';
            $tareas = Tarea::orderBy($columna, $orden)->paginate(5);
            return response()->json([
                'data' => $tareas,
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Error función tarea.index: ' . $th->getMessage());
            Log::error('Archivo: ' . $th->getFile());
            Log::error('Línea: ' . $th->getLine());
            return response()->json([
                'mensaje' => 'Estimado usuario, en estos momentos el servidor esta fuera de servicio, por favor intente mas tarde.',
            ], 400);
        }
    }

    //método para registrar nueva tarea
    public function store(Request $request)
    {
        $rules = [
            'titulo' => 'required',
            'descripcion' => 'required',
        ];

        $ErrorMessages = [
            'titulo.required' => 'Estimado usuario, el titulo es requerido ',
            'descripcion.required' => 'Estimado usuario, la descripcion es requerido ',
        ];

        $validator = Validator::make($request->all(), $rules, $ErrorMessages);
        if ($validator->fails()) {
            return response()->json([
                'created' => false,
                'errors'  => $validator->errors()
            ], 400);
        }

        try {
            $tareas = Tarea::create($request->all());

            return response()->json([
                'data' => $tareas,
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Error función tarea.store: ' . $th->getMessage());
            Log::error('Archivo: ' . $th->getFile());
            Log::error('Línea: ' . $th->getLine());
            return response()->json([
                'mensaje' => 'Estimado usuario, en estos momentos el servidor esta fuera de servicio, por favor intente mas tarde.',
            ], 400);
        }
    }

    //método para consultar una tarea en especifico
    public function show($id)
    {
        try {
            $tarea = Tarea::where('id', $id)->first();
            if ($tarea != null) {
                return response()->json([
                    'data' => $tarea,
                ], 200);
            } else {
                return response()->json([
                    'mensaje' => 'Estimado usuario, el usuario al que hace referencia no esta registrado.',
                ], 400);
            }
        } catch (\Throwable $th) {
            Log::error('Error función tarea.show: ' . $th->getMessage());
            Log::error('Archivo: ' . $th->getFile());
            Log::error('Línea: ' . $th->getLine());
            return response()->json([
                'mensaje' => 'Estimado usuario, en estos momentos el servidor esta fuera de servicio, por favor intente mas tarde.',
            ], 400);
        }
    }

    /*
        método para consultar una tarea en especifico para editar,
        este método podria ser reemplazado por el anterior, pero
        igual se creó para no crear confuciones e identificar mejor
        su funcionalidad.
    */
    public function edit($id)
    {
        try {
            $tarea = Tarea::where('id', $id)->first();
            if ($tarea != null) {
                return response()->json([
                    'data' => $tarea,
                ], 200);
            } else {
                return response()->json([
                    'mensaje' => 'Estimado usuario, el usuario al que hace referencia no esta registrado.',
                ], 400);
            }
        } catch (\Throwable $th) {
            Log::error('Error función tarea.edit: ' . $th->getMessage());
            Log::error('Archivo: ' . $th->getFile());
            Log::error('Línea: ' . $th->getLine());
            return response()->json([
                'mensaje' => 'Estimado usuario, en estos momentos el servidor esta fuera de servicio, por favor intente mas tarde.',
            ], 400);
        }
    }

    //método para actualizar una tarea
    public function update(Request $request, $id)
    {
        $rules = [
            'titulo' => 'required',
            'descripcion' => 'required',
        ];

        $ErrorMessages = [
            'titulo.required' => 'Estimado usuario, el titulo es requerido ',
            'descripcion.required' => 'Estimado usuario, la descripcion es requerido ',
        ];

        $validator = Validator::make($request->all(), $rules, $ErrorMessages);
        if ($validator->fails()) {
            return response()->json([
                'created' => false,
                'errors'  => $validator->errors()
            ], 400);
        }

        try {
            $tareas = Tarea::where('id', $id)->first();
            if ($tareas != null) {
                $tareas->update($request->all());
                return response()->json([
                    'data' => $tareas,
                ], 200);
            } else {
                return response()->json([
                    'mensaje' => 'Estimado usuario, el usuario al que hace referencia no esta registrado.',
                ], 400);
            }
        } catch (\Throwable $th) {
            Log::error('Error función tarea.update: ' . $th->getMessage());
            Log::error('Archivo: ' . $th->getFile());
            Log::error('Línea: ' . $th->getLine());
            return response()->json([
                'mensaje' => 'Estimado usuario, en estos momentos el servidor esta fuera de servicio, por favor intente mas tarde.',
            ], 400);
        }
    }

    //método para eliminar(fisicamente) una tarea en especifico.
    public function destroy($id)
    {
        try {
            $tareas = Tarea::where('id', $id)->first();
            if ($tareas != null) {
                $tareas->delete();
                return response()->json([
                    'data' => $tareas,
                ], 200);
            } else {
                return response()->json([
                    'mensaje' => 'Estimado usuario, el usuario al que hace referencia no esta registrado.',
                ], 400);
            }
        } catch (\Throwable $th) {
            Log::error('Error función tarea.destroy: ' . $th->getMessage());
            Log::error('Archivo: ' . $th->getFile());
            Log::error('Línea: ' . $th->getLine());
            return response()->json([
                'mensaje' => 'Estimado usuario, en estos momentos el servidor esta fuera de servicio, por favor intente mas tarde.',
            ], 400);
        }
    }
}
