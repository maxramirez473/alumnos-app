<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Mostrar el perfil del usuario.
     */
    public function show()
    {
        /** @var User $user */
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    /**
     * Mostrar el formulario de edición del perfil.
     */
    public function edit()
    {
        /** @var User $user */
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Actualizar el perfil del usuario.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        if (!$user instanceof User) {
            return redirect()->route('login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:password',
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        // Actualizar información básica
        $user->name = $request->name;
        $user->email = $request->email;

        // Manejar cambio de contraseña
        if ($request->filled('password')) {
            // Verificar contraseña actual
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.']);
            }
            $user->password = Hash::make($request->password);
        }

        // Manejar imagen de perfil
        if ($request->hasFile('profile_picture')) {
            try {
                // Validar que el archivo sea realmente una imagen
                $file = $request->file('profile_picture');
                $imageInfo = getimagesize($file->getPathname());
                
                if (!$imageInfo) {
                    return back()->withErrors(['profile_picture' => 'El archivo seleccionado no es una imagen válida.']);
                }
                
                // Redimensionar imagen si es necesario
                $this->resizeImageIfNeeded($file);
                
                // Eliminar imagen anterior si existe
                if ($user->profile_picture) {
                    Storage::disk('public')->delete('media/' . $user->profile_picture);
                }

                // Generar nombre único para la imagen
                $filename = $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                
                // Guardar en storage/app/public/media
                $path = $file->storeAs('media', $filename, 'public');
                
                if ($path) {
                    $user->profile_picture = $filename;
                } else {
                    return back()->withErrors(['profile_picture' => 'Error al guardar la imagen. Intenta nuevamente.']);
                }
            } catch (\Exception $e) {
                return back()->withErrors(['profile_picture' => 'Error al procesar la imagen: ' . $e->getMessage()]);
            }
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Perfil actualizado correctamente.');
    }

    /**
     * Eliminar la imagen de perfil del usuario.
     */
    public function deleteProfilePicture()
    {
        $user = Auth::user();
        
        if (!$user instanceof User) {
            return redirect()->route('login');
        }

        if ($user->profile_picture) {
            // Eliminar archivo del storage
            Storage::disk('public')->delete('media/' . $user->profile_picture);
            
            // Limpiar campo en la base de datos
            $user->profile_picture = null;
            $user->save();

            return back()->with('success', 'Imagen de perfil eliminada correctamente.');
        }

        return back()->with('error', 'No tienes una imagen de perfil para eliminar.');
    }

    /**
     * Redimensionar imagen si es muy grande
     */
    private function resizeImageIfNeeded($file, $maxWidth = 400, $maxHeight = 400)
    {
        $imageInfo = getimagesize($file->getPathname());
        
        if (!$imageInfo) {
            return false;
        }
        
        $width = $imageInfo[0];
        $height = $imageInfo[1];
        
        // Si la imagen ya es pequeña, no hacer nada
        if ($width <= $maxWidth && $height <= $maxHeight) {
            return true;
        }
        
        // Calcular nuevas dimensiones manteniendo la proporción
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = round($width * $ratio);
        $newHeight = round($height * $ratio);
        
        // Crear nueva imagen redimensionada
        $srcImage = null;
        $mime = $imageInfo['mime'];
        
        switch ($mime) {
            case 'image/jpeg':
                $srcImage = imagecreatefromjpeg($file->getPathname());
                break;
            case 'image/png':
                $srcImage = imagecreatefrompng($file->getPathname());
                break;
            case 'image/gif':
                $srcImage = imagecreatefromgif($file->getPathname());
                break;
            default:
                return false;
        }
        
        if (!$srcImage) {
            return false;
        }
        
        $destImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preservar transparencia para PNG y GIF
        if ($mime === 'image/png' || $mime === 'image/gif') {
            imagealphablending($destImage, false);
            imagesavealpha($destImage, true);
        }
        
        imagecopyresampled($destImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        
        // Guardar imagen redimensionada
        $success = false;
        switch ($mime) {
            case 'image/jpeg':
                $success = imagejpeg($destImage, $file->getPathname(), 85);
                break;
            case 'image/png':
                $success = imagepng($destImage, $file->getPathname(), 8);
                break;
            case 'image/gif':
                $success = imagegif($destImage, $file->getPathname());
                break;
        }
        
        imagedestroy($srcImage);
        imagedestroy($destImage);
        
        return $success;
    }
}
