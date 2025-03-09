<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        try {
            if (!$request->hasFile('image')) {
                Log::error('No se recibió ningún archivo de imagen');
                return response()->json(['error' => 'No image file uploaded'], 400);
            }

            $image = $request->file('image');
            
            if (!$image->isValid()) {
                Log::error('El archivo de imagen no es válido');
                return response()->json(['error' => 'Invalid image file'], 400);
            }

            // Generar un nombre único para la imagen
            $fileName = uniqid() . '.' . $image->getClientOriginalExtension();
            
            // Asegurarse de que el directorio existe
            $path = public_path('images');
            if (!file_exists($path)) {
                mkdir($path, 0775, true);
            }
            
            // Guardar la imagen en la carpeta public/images
            $image->move($path, $fileName);
            
            // Verificar que el archivo se guardó correctamente
            if (!file_exists($path . '/' . $fileName)) {
                Log::error('No se pudo guardar el archivo de imagen');
                return response()->json(['error' => 'Could not save image file'], 500);
            }
            
            // Generar la URL completa de la imagen
            $imageUrl = url('images/' . $fileName);
            
            Log::info('Imagen subida exitosamente: ' . $imageUrl);
            
            return response()->json([
                'url' => $imageUrl
            ]);
        } catch (\Exception $e) {
            Log::error('Error al subir imagen: ' . $e->getMessage());
            return response()->json(['error' => 'Error uploading image: ' . $e->getMessage()], 500);
        }
    }
}