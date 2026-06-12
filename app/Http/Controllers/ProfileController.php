<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show user profile (untuk modal atau halaman terpisah)
     */
    public function show()
    {
        $user = auth()->user();
        
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id_user,
                'username' => $user->username,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'role' => $user->role,
                'role_display' => $user->role_display,
                'profile_photo_url' => $user->profile_photo_url,
                'initial' => $user->initial,
                'joined_date' => $user->joined_date,
                'member_since' => $user->member_since,
                'last_login' => $user->last_login_formatted,
            ]
        ]);
    }

    /**
     * Update profile (name, email, phone, address)
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id_user . ',id_user',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan oleh user lain',
            'phone.max' => 'Nomor telepon maksimal 20 karakter',
            'address.max' => 'Alamat maksimal 500 karakter',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profile berhasil diperbarui',
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'address' => $user->address,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui profile: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 🔥 Change Password (Admin Ubah Password Sendiri)
     * 
     * Method ini digunakan ketika admin ingin mengubah password DIRINYA SENDIRI.
     * Memerlukan password lama untuk verifikasi.
     * 
     * Route: PUT /profile/change-password
     * Access: Admin Only
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        $user = auth()->user();
        
        // 🔒 VALIDASI ROLE: Hanya admin yang bisa ganti password
        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya administrator yang dapat mengubah password.'
            ], 403);
        }
        
        // Validasi input
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => [
                'required',
                'string',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', // Minimal 1 huruf kecil, 1 huruf besar, 1 angka
            ],
        ], [
            'current_password.required' => 'Password lama harus diisi',
            'new_password.required' => 'Password baru harus diisi',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok',
            'new_password.min' => 'Password minimal 8 karakter',
            'new_password.regex' => 'Password harus mengandung minimal 1 huruf besar, 1 huruf kecil, dan 1 angka',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password lama tidak sesuai',
                'errors' => [
                    'current_password' => ['Password lama yang Anda masukkan salah']
                ]
            ], 422);
        }

        // Check if new password same as current password
        if (Hash::check($request->new_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password baru tidak boleh sama dengan password lama',
                'errors' => [
                    'new_password' => ['Password baru harus berbeda dengan password lama']
                ]
            ], 422);
        }

        try {
            // Update password
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            // Log activity (optional)
            \Log::info('Admin changed own password', [
                'user_id' => $user->id_user,
                'username' => $user->username,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => '✅ Password Admin berhasil diubah! Silakan login kembali dengan password baru.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to change admin password', [
                'error' => $e->getMessage(),
                'user_id' => $user->id_user,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah password: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update avatar/profile photo
     */
    public function updateAvatar(Request $request)
    {
        $user = auth()->user();
        
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ], [
            'avatar.required' => 'File foto harus dipilih',
            'avatar.image' => 'File harus berupa gambar',
            'avatar.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'avatar.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $path = $user->updateProfilePhoto($request->file('avatar'));

            return response()->json([
                'success' => true,
                'message' => 'Foto profile berhasil diperbarui',
                'photo_url' => $user->profile_photo_url
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload foto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete avatar/profile photo
     */
    public function deleteAvatar()
    {
        $user = auth()->user();
        
        try {
            $user->deleteProfilePhoto();

            return response()->json([
                'success' => true,
                'message' => 'Foto profile berhasil dihapus',
                'photo_url' => $user->profile_photo_url // Will return default avatar
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus foto: ' . $e->getMessage()
            ], 500);
        }
    }
}