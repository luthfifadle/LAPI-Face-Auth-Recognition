<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Models\User;
use Exception;

class FaceLoginController extends Controller
{
    public function registerFace(Request $request)
    {
        try {
            $validate = $request->validate([
                'name' => 'required',
                'email' => 'required',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Simpan gambar
            $path = $request->file('image')->store('uploads', 'public');

            // Panggil Python untuk menghasilkan data wajah
            $pythonScript = base_path('scripts/register_face.py');
            $imagePath = storage_path('app/public/' . $path);

            $output = shell_exec("python3 $pythonScript $imagePath");

            // Simpan data wajah ke database
            $faceData = json_decode($output, true);

            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->face_image_path = $path;
            $user->face_data = json_encode($faceData);
            $user->save();

            return response()->json(['message' => 'Face data registered successfully.']);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function loginWithFace(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Simpan gambar sementara
            $path = $request->file('image')->store('uploads', 'public');

            // Panggil Python untuk memeriksa wajah
            $pythonScript = base_path('scripts/recognize_face.py');
            $imagePath = storage_path('app/public/' . $path);

            $users = User::where('face_image_path', '!=', null)->get();

            foreach ($users as $item) {
                $imagePathExist = storage_path('app/public/' . $item->face_image_path);
                $output = shell_exec("python3 $pythonScript $imagePath $imagePathExist");
                $faceData = json_decode($output, true);

                if ($faceData == "true") {
                    Auth::login($item);
                    return response()->json(['message' => 'Login successful.', 'user' => $item]);
                }
            }

            return response()->json(['message' => 'Login Failed.', 'user' => null]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
