<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|email',
            'password' => 'required|string',
        ]);

        $pegawai = Pegawai::where('email', $request->username)->first();

        if (!$pegawai) {
            return response()->json(['message' => 'Email tidak ditemukan'], 404);
        }

        if (!Hash::check($request->password, $pegawai->password)) {
            return response()->json(['message' => 'Password salah'], 401);
        }

        if (!in_array($pegawai->role, ['admin', 'atasan'])) {
            return response()->json(['message' => 'Akses ditolak'], 403);
        }

        $token = bin2hex(random_bytes(32));

        return response()->json([
            'token' => $token,
            'user' => $pegawai->email,
            'role' => $pegawai->role,
            'nama' => $pegawai->nama
        ]);
    }
}
