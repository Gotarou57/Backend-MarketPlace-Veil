<?php
// app/Http/Controllers/AuthController.php
// REPLACE file yang lama dengan ini

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    // Register Step 1: Buat akun user
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:buyer,seller',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        $token = auth()->login($user);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token,
            'needs_store_setup' => $request->role === 'seller', // Flag untuk frontend
        ], 201);
    }

    // Register Step 2: Setup toko (khusus seller)
    public function setupStore(Request $request)
    {
        $user = auth()->user();

        // Validasi: hanya seller yang belum punya toko
        if ($user->role !== 'seller') {
            return response()->json(['error' => 'Only sellers can setup store'], 403);
        }

        if ($user->store) {
            return response()->json(['error' => 'Store already exists'], 400);
        }

        $validator = Validator::make($request->all(), [
            'store_name' => 'required|string|max:255',
            'store_description' => 'nullable|string',
            'pic_name' => 'required|string|max:255',
            'pic_phone' => 'required|string|max:20',
            'pic_email' => 'required|email|max:255',
            'pic_street' => 'required|string|max:255',
            'pic_rt' => 'required|string|max:10',
            'pic_rw' => 'required|string|max:10',
            'pic_kelurahan' => 'required|string|max:100',
            'pic_city' => 'required|string|max:100',
            'pic_province' => 'required|string|max:100',
            'pic_ktp_number' => 'required|string|max:20',
            'pic_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'pic_ktp_file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Upload foto PIC
        $picPhotoPath = null;
        if ($request->hasFile('pic_photo')) {
            $picPhotoPath = $request->file('pic_photo')->store('stores/pic_photos', 'public');
        }

        // Upload KTP file
        $ktpFilePath = null;
        if ($request->hasFile('pic_ktp_file')) {
            $ktpFilePath = $request->file('pic_ktp_file')->store('stores/ktp_files', 'public');
        }

        $store = Store::create([
            'user_id' => $user->id,
            'name' => $request->store_name,
            'description' => $request->store_description,
            'pic_name' => $request->pic_name,
            'pic_phone' => $request->pic_phone,
            'pic_email' => $request->pic_email,
            'pic_street' => $request->pic_street,
            'pic_rt' => $request->pic_rt,
            'pic_rw' => $request->pic_rw,
            'pic_kelurahan' => $request->pic_kelurahan,
            'pic_city' => $request->pic_city,
            'pic_province' => $request->pic_province,
            'pic_ktp_number' => $request->pic_ktp_number,
            'pic_photo' => $picPhotoPath,
            'pic_ktp_file' => $ktpFilePath,
        ]);

        return response()->json([
            'message' => 'Store setup successfully',
            'store' => $store,
            'user' => $user->load('store'),
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = $request->only('email', 'password');

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = auth()->user();
        $user->load('store');

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function me()
    {
        return response()->json(auth()->user()->load('store'));
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return response()->json([
            'token' => auth()->refresh(),
        ]);
    }
}