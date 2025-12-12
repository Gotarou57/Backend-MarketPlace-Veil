<?php
// app/Http/Controllers/AuthController.php
// REPLACE method register dengan yang ini

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Store;
use App\Mail\SellerRegistrationApproved;
use App\Mail\SellerRegistrationRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Register dengan PENDING status (menunggu verifikasi admin)
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Data user (optional - akan di-generate otomatis)
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users',
            'password' => 'nullable|string|min:6|confirmed',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            
            // Data toko (wajib)
            'store_name' => 'required|string|max:255',
            'store_description' => 'nullable|string',
            'pic_name' => 'required|string|max:255',
            'pic_phone' => 'required|string|max:20',
            'pic_email' => 'required|email|max:255',
            'pic_street' => 'required|string|max:255',
            'pic_rt' => 'required|string|max:10',
            'pic_rw' => 'required|string|max:10',
            'pic_kelurahan' => 'required|string|max:100',
            'pic_province_id' => 'required|string',
            'pic_province_name' => 'required|string',
            'pic_city_id' => 'required|string',
            'pic_city_name' => 'required|string',
            'pic_district_id' => 'required|string',
            'pic_district_name' => 'required|string',
            'pic_ktp_number' => 'required|string|max:20',
            'pic_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'pic_ktp_file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid. Mohon periksa kembali form registrasi Anda.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Upload files
            $picPhotoPath = $request->file('pic_photo')->store('stores/pic_photos', 'public');
            $ktpFilePath = $request->file('pic_ktp_file')->store('stores/ktp_files', 'public');

            // Create user dengan status BELUM AKTIF (belum bisa login)
            $user = User::create([
                'name' => $request->name ?? $request->pic_name,
                'email' => $request->email ?? $request->pic_email,
                'password' => Hash::make(Str::random(16)), // Password sementara random
                'role' => 'seller',
                'phone' => $request->phone ?? $request->pic_phone,
                'address' => $request->address ?? $request->pic_street,
            ]);

            // Create store dengan status PENDING
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
                'pic_province_id' => $request->pic_province_id,
                'pic_province_name' => $request->pic_province_name,
                'pic_city_id' => $request->pic_city_id,
                'pic_city_name' => $request->pic_city_name,
                'pic_district_id' => $request->pic_district_id,
                'pic_district_name' => $request->pic_district_name,
                'pic_ktp_number' => $request->pic_ktp_number,
                'pic_photo' => $picPhotoPath,
                'pic_ktp_file' => $ktpFilePath,
                'verification_status' => 'pending', // STATUS PENDING
                'verified_at' => null,
                'verified_by' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil! Silakan menunggu verifikasi dari admin. Anda akan menerima email setelah akun Anda diverifikasi.',
                'data' => [
                    'store_name' => $store->name,
                    'pic_email' => $store->pic_email,
                    'status' => 'pending_verification'
                ]
            ], 201);

        } catch (\Exception $e) {
            // Rollback jika ada error
            if (isset($user)) {
                $user->delete();
            }
            if (isset($picPhotoPath)) {
                Storage::disk('public')->delete($picPhotoPath);
            }
            if (isset($ktpFilePath)) {
                Storage::disk('public')->delete($ktpFilePath);
            }

            \Log::error('Registration error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // LOGIN - Cek status verifikasi
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

        // BARU: Cek status verifikasi untuk seller
        if ($user->isSeller() && $user->store) {
            if ($user->store->isPending()) {
                auth()->logout();
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda masih dalam proses verifikasi oleh admin. Silakan tunggu email konfirmasi.'
                ], 403);
            }

            if ($user->store->isRejected()) {
                auth()->logout();
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda ditolak oleh admin. Alasan: ' . ($user->store->rejection_reason ?? 'Tidak ada alasan yang diberikan.')
                ], 403);
            }
        }

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

    /**
     * Generate secure random password
     * Format: 10 karakter (huruf besar, huruf kecil, angka)
     */
    private function generateSecurePassword($length = 10)
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        
        $password = '';
        $password .= $uppercase[rand(0, strlen($uppercase) - 1)]; // Min 1 uppercase
        $password .= $lowercase[rand(0, strlen($lowercase) - 1)]; // Min 1 lowercase
        $password .= $numbers[rand(0, strlen($numbers) - 1)]; // Min 1 number
        
        $allChars = $uppercase . $lowercase . $numbers;
        for ($i = 3; $i < $length; $i++) {
            $password .= $allChars[rand(0, strlen($allChars) - 1)];
        }
        
        return str_shuffle($password);
    }
}