<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Notification;
use App\Models\Proyek;
use App\Models\Kwitansi;
use App\Models\Laporan;
use App\Models\User; // 🔥 IMPORT MODEL USER

class AdminController extends Controller
{
    public function index()
    {
        // ========================================
        // 🔥 AMBIL NOTIFIKASI DARI DATABASE (3 TERBARU SAJA)
        // ========================================
        
        // 🔥 HAPUS NOTIFIKASI LAMA (HANYA SIMPAN 3 TERBARU)
        $totalNotifications = Notification::count();
        
        if ($totalNotifications > 3) {
            // Ambil ID dari 3 notifikasi terbaru
            $keepIds = Notification::orderBy('created_at', 'desc')
                ->limit(3)
                ->pluck('id')
                ->toArray();
            
            // Hapus notifikasi selain 3 terbaru
            Notification::whereNotIn('id', $keepIds)->delete();
        }
        
        // Ambil 3 notifikasi terbaru untuk ditampilkan
        $notifications = Notification::orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function($notif) {
                return [
                    'type' => $this->getNotificationType($notif->icon),
                    'icon' => $notif->icon,
                    'title' => $notif->title,
                    'message' => $notif->message,
                    'time' => $notif->time_ago, // Menggunakan accessor dari Model
                ];
            })
            ->toArray();

        // ========================================
        // 🔥 DATA STATISTIK UNTUK CARDS (REAL DATA)
        // ========================================
        
        // 🔥 Ambil statistik proyek (PADAT/SEPI)
        $statusProyek = Proyek::getStatusProyek();
        
        // 🔥 Total jumlah proyek
        $totalPenjualan = Proyek::count();
        
        // 🔥 Hitung kwitansi yang BELUM lunas (Status != 'Lunas')
        $kwitansiLunas = Kwitansi::where('Status', '!=', 'Lunas')->count();
        
        // 🔥 Total profit dari semua laporan yang telah masuk (SUDAH DINAMIS)
        $totalProfit = Laporan::sum('total_profit') ?? 0;

        // ========================================
        // KIRIM DATA KE VIEW
        // ========================================
        return view('admin.dashboard', [
            'notifications' => $notifications,
            'totalPenjualan' => $totalPenjualan,
            'statusProyek' => $statusProyek,
            'kwitansiLunas' => $kwitansiLunas,
            'totalProfit' => $totalProfit,
        ]);
    }

    /**
     * 🔥 HELPER: Convert icon ke tipe notifikasi untuk styling
     */
    private function getNotificationType($icon)
    {
        $typeMap = [
            'bi-exclamation-triangle-fill' => 'warning',
            'bi-check-circle-fill' => 'success',
            'bi-info-circle-fill' => 'info',
            'bi-x-circle-fill' => 'danger',
            'bi-file-earmark-plus-fill' => 'info',
            'bi-pencil-square' => 'info',
            'bi-trash-fill' => 'danger',
            'bi-hourglass-split' => 'warning',
        ];

        return $typeMap[$icon] ?? 'info';
    }

    /**
     * 🔥 METHOD BARU: Tandai notifikasi sebagai dibaca
     */
    public function markNotificationAsRead($id)
    {
        $notification = Notification::find($id);
        
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }

    /**
     * 🔥 METHOD BARU: Tandai semua notifikasi sebagai dibaca
     */
    public function markAllNotificationsAsRead()
    {
        Notification::where('status', 'unread')->update(['status' => 'read']);
        
        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }

    /**
     * 🔥 METHOD BARU: Hapus notifikasi
     */
    public function deleteNotification($id)
    {
        $notification = Notification::find($id);
        
        if ($notification) {
            $notification->delete();
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }

    /**
     * 🔥 METHOD BARU: Get notifikasi untuk AJAX (real-time)
     */
    public function getNotifications()
    {
        $notifications = Notification::orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($notif) {
                return [
                    'id' => $notif->id,
                    'type' => $this->getNotificationType($notif->icon),
                    'icon' => $notif->icon,
                    'title' => $notif->title,
                    'message' => $notif->message,
                    'time' => $notif->time_ago,
                    'status' => $notif->status,
                ];
            });
        
        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => Notification::unread()->count(),
        ]);
    }

    /**
     * 🔥 Method untuk mendapatkan statistik dashboard (UPDATED)
     */
    public function getStatistics()
    {
        $statusProyek = Proyek::getStatusProyek();
        $statistikProyek = Proyek::getStatistikStatus();
        
        // 🔥 Hitung kwitansi belum lunas (REAL DATA)
        $kwitansiBelumLunas = Kwitansi::where('Status', '!=', 'Lunas')->count();
        
        // 🔥 Total profit dari semua laporan (REAL DATA)
        $totalProfit = Laporan::sum('total_profit') ?? 0;
        
        $stats = [
            'totalProyek' => Proyek::count(),
            'statusProyek' => $statusProyek['status'], // PADAT atau SEPI
            'jumlahStatus' => $statusProyek['jumlah'],
            'keterangan' => $statusProyek['keterangan'],
            'detailStatus' => [
                'rab_belum_dibuat' => $statistikProyek['rab_belum_dibuat'],
                'rab_telah_dibuat' => $statistikProyek['rab_telah_dibuat'],
                'proyek_dikerjakan' => $statistikProyek['proyek_dikerjakan'],
                'proyek_selesai' => $statistikProyek['proyek_selesai'],
            ],
            'kwitansiLunas' => $kwitansiBelumLunas,
            'totalProfit' => $totalProfit,
        ];
        
        return response()->json($stats);
    }

    // ========================================
    // 🔥🔥🔥 PASSWORD MANAGEMENT METHODS
    // ========================================

    /**
     * 🔥 NEW METHOD: Admin Ubah Password Kasir
     * 
     * Method ini digunakan ketika admin ingin mengubah password KASIR.
     * TIDAK memerlukan password lama karena admin memiliki privilege penuh.
     * 
     * Route: PUT /admin/change-kasir-password
     * Access: Admin Only (sudah di-protect di routes)
     * 
     * Request Body:
     * - kasir_id (required): ID user kasir yang akan diubah passwordnya
     * - new_password (required): Password baru
     * - new_password_confirmation (required): Konfirmasi password baru
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeKasirPassword(Request $request)
    {
        $admin = auth()->user();
        
        // 🔒 DOUBLE CHECK: Pastikan yang request adalah admin
        if ($admin->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya administrator yang dapat mengubah password kasir.'
            ], 403);
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'kasir_id' => 'required|exists:users,id_user',
            'new_password' => [
                'required',
                'string',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', // Minimal 1 huruf kecil, 1 huruf besar, 1 angka
            ],
        ], [
            'kasir_id.required' => 'Pilih kasir yang akan diubah passwordnya',
            'kasir_id.exists' => 'Kasir tidak ditemukan',
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

        try {
            // Cari user kasir
            $kasir = User::where('id_user', $request->kasir_id)
                ->where('role', 'kasir')
                ->first();

            // Validasi: Pastikan user adalah kasir
            if (!$kasir) {
                return response()->json([
                    'success' => false,
                    'message' => 'User yang dipilih bukan kasir atau tidak ditemukan',
                    'errors' => [
                        'kasir_id' => ['User yang dipilih bukan kasir']
                    ]
                ], 422);
            }

            // Validasi: Admin tidak bisa ubah password admin lain lewat endpoint ini
            if ($kasir->role === 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat mengubah password admin melalui fitur ini',
                    'errors' => [
                        'kasir_id' => ['Gunakan fitur ubah password admin untuk mengubah password admin']
                    ]
                ], 422);
            }

            // Update password kasir (LANGSUNG hash, tanpa cek password lama)
            $kasir->update([
                'password' => Hash::make($request->new_password)
            ]);

            // Log activity untuk audit
            \Log::info('Admin changed kasir password', [
                'admin_id' => $admin->id_user,
                'admin_username' => $admin->username,
                'kasir_id' => $kasir->id_user,
                'kasir_username' => $kasir->username,
                'kasir_name' => $kasir->name,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // 🔥 OPTIONAL: Kirim notifikasi ke kasir (jika ada sistem notifikasi)
            // Notification::create([
            //     'user_id' => $kasir->id_user,
            //     'title' => 'Password Diubah oleh Admin',
            //     'message' => 'Password akun Anda telah diubah oleh administrator. Gunakan password baru untuk login.',
            //     'icon' => 'bi-shield-lock-fill',
            //     'status' => 'unread',
            // ]);

            return response()->json([
                'success' => true,
                'message' => "✅ Password kasir \"{$kasir->name}\" berhasil diubah! Kasir dapat login dengan password baru.",
                'kasir' => [
                    'id' => $kasir->id_user,
                    'name' => $kasir->name,
                    'username' => $kasir->username,
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to change kasir password', [
                'error' => $e->getMessage(),
                'admin_id' => $admin->id_user,
                'kasir_id' => $request->kasir_id ?? null,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah password kasir: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 🔥 NEW METHOD: Get List Kasir (untuk dropdown)
     * 
     * Method helper untuk mendapatkan list kasir yang aktif.
     * Digunakan untuk populate dropdown di modal ubah password kasir.
     * 
     * Route: GET /admin/kasir-list (optional, bisa ditambahkan di routes)
     * Access: Admin Only
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getKasirList()
    {
        $admin = auth()->user();
        
        if ($admin->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak'
            ], 403);
        }

        try {
            $kasirList = User::where('role', 'kasir')
                ->orderBy('name')
                ->get()
                ->map(function($kasir) {
                    return [
                        'id' => $kasir->id_user,
                        'name' => $kasir->name ?? $kasir->username,
                        'username' => $kasir->username,
                        'email' => $kasir->email,
                        'last_login' => $kasir->last_login_formatted,
                    ];
                });

            return response()->json([
                'success' => true,
                'kasir_list' => $kasirList,
                'total' => $kasirList->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kasir: ' . $e->getMessage()
            ], 500);
        }
    }
}