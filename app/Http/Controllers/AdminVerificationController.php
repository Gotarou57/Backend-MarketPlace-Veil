<?php
// app/Http/Controllers/AdminVerificationController.php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\User;
use App\Mail\SellerRegistrationApproved;
use App\Mail\SellerRegistrationRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminVerificationController extends Controller
{
    /**
     * Get list of pending stores (untuk ditampilkan di admin dashboard)
     */
    public function getPendingStores()
    {
        $pendingStores = Store::with('user')
            ->pending()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pendingStores,
            'total' => $pendingStores->count()
        ]);
    }

    /**
     * Get detail store by ID (untuk review data lengkap)
     */
    public function getStoreDetail($id)
    {
        $store = Store::with('user')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'store' => $store,
                'pic_photo_url' => $store->pic_photo ? asset('storage/' . $store->pic_photo) : null,
                'pic_ktp_url' => $store->pic_ktp_file ? asset('storage/' . $store->pic_ktp_file) : null,
            ]
        ]);
    }

    /**
     * Approve seller registration
     */
    public function approveStore(Request $request, $id)
    {
        $store = Store::with('user')->findOrFail($id);

        // Validasi: Pastikan masih pending
        if (!$store->isPending()) {
            return response()->json([
                'success' => false,
                'message' => 'Store sudah diverifikasi sebelumnya.'
            ], 400);
        }

        try {
            // Generate username & password
            $generatedUsername = User::generateUsername($store->name);
            $generatedPassword = $this->generateSecurePassword();

            // Update user dengan credentials baru
            $store->user->update([
                'email' => $generatedUsername . '@seller.marketplace',
                'password' => Hash::make($generatedPassword),
            ]);

            // Update store status menjadi approved
            $store->update([
                'verification_status' => 'approved',
                'verified_at' => now(),
                'verified_by' => auth()->id(),
                'rejection_reason' => null,
            ]);

            // Kirim email approval dengan username & password
            Mail::to($store->pic_email)->send(
                new SellerRegistrationApproved(
                    $store->pic_name,
                    $store->name,
                    $generatedUsername,
                    $generatedPassword
                )
            );

            return response()->json([
                'success' => true,
                'message' => 'Store berhasil diapprove dan email telah dikirim ke seller.',
                'data' => $store->fresh(['user', 'verifier'])
            ]);

        } catch (\Exception $e) {
            \Log::error('Approve store error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal approve store: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject seller registration
     */
    public function rejectStore(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $store = Store::with('user')->findOrFail($id);

        // Validasi: Pastikan masih pending
        if (!$store->isPending()) {
            return response()->json([
                'success' => false,
                'message' => 'Store sudah diverifikasi sebelumnya.'
            ], 400);
        }

        try {
            // Update store status menjadi rejected
            $store->update([
                'verification_status' => 'rejected',
                'verified_at' => now(),
                'verified_by' => auth()->id(),
                'rejection_reason' => $request->rejection_reason,
            ]);

            // Kirim email rejection
            Mail::to($store->pic_email)->send(
                new SellerRegistrationRejected(
                    $store->pic_name,
                    $store->name,
                    [$request->rejection_reason],
                    [$request->rejection_reason]
                )
            );

            return response()->json([
                'success' => true,
                'message' => 'Store berhasil ditolak dan email telah dikirim ke seller.',
                'data' => $store->fresh(['user', 'verifier'])
            ]);

        } catch (\Exception $e) {
            \Log::error('Reject store error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal reject store: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all verification history (approved & rejected)
     */
    public function getVerificationHistory()
    {
        $approvedStores = Store::with(['user', 'verifier'])
            ->approved()
            ->orderBy('verified_at', 'desc')
            ->get();

        $rejectedStores = Store::with(['user', 'verifier'])
            ->rejected()
            ->orderBy('verified_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'approved' => $approvedStores,
                'rejected' => $rejectedStores,
            ],
            'summary' => [
                'total_approved' => $approvedStores->count(),
                'total_rejected' => $rejectedStores->count(),
                'total_pending' => Store::pending()->count(),
            ]
        ]);
    }

    /**
     * Generate secure random password
     */
    private function generateSecurePassword($length = 10)
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        
        $password = '';
        $password .= $uppercase[rand(0, strlen($uppercase) - 1)];
        $password .= $lowercase[rand(0, strlen($lowercase) - 1)];
        $password .= $numbers[rand(0, strlen($numbers) - 1)];
        
        $allChars = $uppercase . $lowercase . $numbers;
        for ($i = 3; $i < $length; $i++) {
            $password .= $allChars[rand(0, strlen($allChars) - 1)];
        }
        
        return str_shuffle($password);
    }
}