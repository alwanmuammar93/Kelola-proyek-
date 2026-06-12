<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Primary key custom (bukan 'id', tapi 'id_user')
     */
    protected $primaryKey = 'id_user';
    
    /**
     * Set to false jika id_user BUKAN auto-increment (UUID/UID manual)
     */
    public $incrementing = true;

    /**
     * Tipe data primary key
     */
    protected $keyType = 'int';

    /**
     * Table name (opsional, Laravel auto-detect jadi 'users')
     */
    protected $table = 'users';

    /**
     * Kolom yang bisa diisi mass assignment
     */
    protected $fillable = [
        'username',
        'name',
        'password',
        'email',
        'role',
        'profile_photo',
        'phone',
        'address',
        'theme_preference',
        'notification_email',
        'notification_system',
        'fcm_token', // 🔥 NEW: FCM Token untuk push notification
        'two_factor_enabled',
        'two_factor_secret',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'fcm_token', // 🔥 NEW: Hide FCM token dari response API
    ];

    /**
     * Casting tipe data
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'last_login_at' => 'datetime',
        'notification_email' => 'boolean',
        'notification_system' => 'boolean',
        'two_factor_enabled' => 'boolean',
    ];

    /**
     * Default values untuk kolom
     */
    protected $attributes = [
        'role' => 'kasir',
        'theme_preference' => 'light',
        'notification_email' => true,
        'notification_system' => true,
        'two_factor_enabled' => false,
    ];

    /**
     * ========== AUTHENTICATION OVERRIDES ==========
     */

    /**
     * Override method untuk autentikasi menggunakan 'username' bukan 'email'
     */
    public function getAuthIdentifierName()
    {
        return 'username'; // Laravel akan pakai kolom 'username' untuk login
    }

    /**
     * Override method untuk ambil password
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * ========== ACCESSORS (GETTERS) ==========
     */

    /**
     * Get full profile photo URL
     */
    public function getProfilePhotoUrlAttribute()
    {
        // Jika ada foto dan file exists
        if ($this->profile_photo && Storage::disk('public')->exists($this->profile_photo)) {
            return asset('storage/' . $this->profile_photo);
        }
        
        // Fallback to UI Avatars dengan initial
        $initial = $this->getInitialAttribute();
        return 'https://ui-avatars.com/api/?name=' . urlencode($initial) . 
               '&background=667eea&color=fff&size=200&bold=true';
    }

    /**
     * Get user initial (huruf pertama nama)
     */
    public function getInitialAttribute()
    {
        $name = $this->name ?? $this->username;
        return strtoupper(substr($name, 0, 1));
    }

    /**
     * Get display name (name atau username)
     */
    public function getDisplayNameAttribute()
    {
        return $this->name ?? $this->username;
    }

    /**
     * Get formatted created_at date
     */
    public function getJoinedDateAttribute()
    {
        return $this->created_at->format('d F Y');
    }

    /**
     * Get formatted created_at date (short)
     */
    public function getJoinedDateShortAttribute()
    {
        return $this->created_at->format('d M Y');
    }

    /**
     * Get how long user has been member
     */
    public function getMemberSinceAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get role badge class
     */
    public function getRoleBadgeClassAttribute()
    {
        return match($this->role) {
            'admin' => 'role-admin',
            'kasir' => 'role-kasir',
            default => 'role-default'
        };
    }

    /**
     * Get role display name
     */
    public function getRoleDisplayAttribute()
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'kasir' => 'Kasir',
            default => ucfirst($this->role)
        };
    }

    /**
     * Get last login formatted
     */
    public function getLastLoginFormattedAttribute()
    {
        if (!$this->last_login_at) {
            return 'Belum pernah login';
        }
        
        return $this->last_login_at->diffForHumans();
    }

    /**
     * Check if user is admin
     */
    public function getIsAdminAttribute()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is kasir
     */
    public function getIsKasirAttribute()
    {
        return $this->role === 'kasir';
    }

    /**
     * Check if email notifications enabled
     */
    public function getEmailNotificationsEnabledAttribute()
    {
        return (bool) $this->notification_email;
    }

    /**
     * Check if system notifications enabled
     */
    public function getSystemNotificationsEnabledAttribute()
    {
        return (bool) $this->notification_system;
    }

    /**
     * 🔥 NEW: Check if push notifications enabled (has FCM token)
     */
    public function getPushNotificationsEnabledAttribute()
    {
        return !empty($this->fcm_token);
    }

    /**
     * Check if 2FA is enabled
     */
    public function getTwoFactorEnabledStatusAttribute()
    {
        return (bool) $this->two_factor_enabled;
    }

    /**
     * Get theme preference (light/dark)
     */
    public function getThemeAttribute()
    {
        return $this->theme_preference ?? 'light';
    }

    /**
     * ========== MUTATORS (SETTERS) ==========
     */

    /**
     * 🔥 FIXED: Mutator password di-disable karena menyebabkan double hashing
     * Password sudah di-hash di Controller dengan Hash::make() atau bcrypt()
     * 
     * JANGAN UNCOMMENT INI KECUALI ANDA TAHU APA YANG ANDA LAKUKAN!
     */
    // public function setPasswordAttribute($value)
    // {
    //     if (!empty($value)) {
    //         $this->attributes['password'] = bcrypt($value);
    //     }
    // }

    /**
     * ========== METHODS ==========
     */

    /**
     * Check if user has specific role
     */
    public function hasRole($role)
    {
        if (is_array($role)) {
            return in_array($this->role, $role);
        }
        
        return $this->role === $role;
    }

    /**
     * Update profile photo
     */
    public function updateProfilePhoto($file)
    {
        // Delete old photo first
        $this->deleteProfilePhoto();
        
        // Store new photo
        $path = $file->store('profile-photos', 'public');
        
        // Update user record
        $this->update(['profile_photo' => $path]);
        
        return $path;
    }

    /**
     * Delete profile photo
     */
    public function deleteProfilePhoto()
    {
        if ($this->profile_photo && Storage::disk('public')->exists($this->profile_photo)) {
            Storage::disk('public')->delete($this->profile_photo);
            $this->update(['profile_photo' => null]);
            return true;
        }
        
        return false;
    }

    /**
     * Update last login info
     */
    public function updateLastLogin($ip = null)
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip ?? request()->ip(),
        ]);
    }

    /**
     * Update theme preference
     */
    public function updateTheme($theme)
    {
        $this->update(['theme_preference' => $theme]);
    }

    /**
     * Enable/disable email notifications
     */
    public function toggleEmailNotifications($enabled = true)
    {
        $this->update(['notification_email' => $enabled]);
    }

    /**
     * Enable/disable system notifications
     */
    public function toggleSystemNotifications($enabled = true)
    {
        $this->update(['notification_system' => $enabled]);
    }

    /**
     * 🔥 NEW: Save FCM Token for push notifications
     */
    public function saveFcmToken($token)
    {
        $this->update(['fcm_token' => $token]);
    }

    /**
     * 🔥 NEW: Remove FCM Token (user unsubscribe push notification)
     */
    public function removeFcmToken()
    {
        $this->update(['fcm_token' => null]);
    }

    /**
     * 🔥 NEW: Check if user has FCM Token
     */
    public function hasFcmToken()
    {
        return !empty($this->fcm_token);
    }

    /**
     * Enable two-factor authentication
     */
    public function enable2FA($secret)
    {
        $this->update([
            'two_factor_enabled' => true,
            'two_factor_secret' => encrypt($secret),
        ]);
    }

    /**
     * Disable two-factor authentication
     */
    public function disable2FA()
    {
        $this->update([
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
        ]);
    }

    /**
     * Get two-factor secret (decrypted)
     */
    public function get2FASecret()
    {
        if ($this->two_factor_secret) {
            return decrypt($this->two_factor_secret);
        }
        
        return null;
    }

    /**
     * 🔥 FIXED: Change password method - langsung assign tanpa bcrypt lagi
     * Karena ProfileController sudah hash dengan Hash::make()
     */
    public function changePassword($newPassword)
    {
        // Langsung assign, karena sudah di-hash di controller
        $this->update(['password' => $newPassword]);
    }

    /**
     * Verify current password
     */
    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }

    /**
     * ========== SCOPES ==========
     */

    /**
     * Scope query untuk admin only
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope query untuk kasir only
     */
    public function scopeKasirs($query)
    {
        return $query->where('role', 'kasir');
    }

    /**
     * Scope query untuk active users (login dalam 30 hari terakhir)
     */
    public function scopeActive($query)
    {
        return $query->where('last_login_at', '>=', now()->subDays(30));
    }

    /**
     * Scope query untuk inactive users
     */
    public function scopeInactive($query)
    {
        return $query->where(function($q) {
            $q->whereNull('last_login_at')
              ->orWhere('last_login_at', '<', now()->subDays(30));
        });
    }

    /**
     * 🔥 NEW: Scope untuk users yang punya FCM token (bisa terima push notif)
     */
    public function scopeWithPushNotifications($query)
    {
        return $query->whereNotNull('fcm_token');
    }

    /**
     * ========== RELATIONSHIPS ==========
     * Tambahkan relationships jika diperlukan di masa depan
     */

    /**
     * Example: User has many projects (if needed)
     */
    // public function projects()
    // {
    //     return $this->hasMany(Proyek::class, 'created_by', 'id_user');
    // }
}