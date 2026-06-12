<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'id_product';

    protected $fillable = [
        'name',
        'category',
        'image',
        'ecommerce',
        'link',
        'description',
        'status',
        'display_order',
        'created_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship: User yang membuat
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }

    /**
     * Get full image URL
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return 'https://via.placeholder.com/300x300/0d2946/ffffff?text=' . urlencode($this->name);
        }

        // Jika sudah full path (dimulai dengan product-images/ atau images/)
        if (str_starts_with($this->image, 'product-images/') || str_starts_with($this->image, 'images/')) {
            return asset($this->image);
        }

        // Jika di storage
        if (Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }

        return asset($this->image);
    }

    /**
     * Get category display name
     */
    public function getCategoryNameAttribute()
    {
        return match($this->category) {
            'alat-las' => 'Alat Las',
            'Perlengkapan' => 'Perlengkapan',
            'perkakas' => 'Perkakas',
            'Cat' => 'Cat',
            default => ucfirst($this->category)
        };
    }

    /**
     * Get ecommerce platform name
     */
    public function getEcommercePlatformAttribute()
    {
        return match($this->ecommerce) {
            'shopee' => 'Shopee',
            'tokopedia' => 'Tokopedia',
            'tiktok' => 'TikTok Shop',
            'facebook' => 'Facebook Marketplace',
            'whatsapp' => 'WhatsApp Catalog',
            default => ucfirst($this->ecommerce)
        };
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'active' => 'success',
            'inactive' => 'secondary',
            default => 'secondary'
        };
    }

    /**
     * Update product image
     */
    public function updateImage($file)
    {
        // Delete old image
        $this->deleteImage();

        // Store new image
        $path = $file->store('products', 'public');

        // Update record
        $this->update(['image' => $path]);

        return $path;
    }

    /**
     * Delete product image
     */
    public function deleteImage()
    {
        if ($this->image && 
            !str_starts_with($this->image, 'product-images/') && 
            !str_starts_with($this->image, 'images/')) {
            
            if (Storage::disk('public')->exists($this->image)) {
                Storage::disk('public')->delete($this->image);
            }
        }
    }

    /**
     * Scope: Active products only
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: By category
     */
    public function scopeByCategory($query, $category)
    {
        if ($category && $category !== 'all') {
            return $query->where('category', $category);
        }
        return $query;
    }

    /**
     * Scope: Search by name
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('name', 'ILIKE', '%' . $search . '%');
        }
        return $query;
    }

    /**
     * Scope: Ordered by display_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc')
                     ->orderBy('created_at', 'desc');
    }

    /**
     * Get available categories
     */
    public static function getCategories()
    {
        return [
            'alat-las' => 'Alat Las',
            'Perlengkapan' => 'Perlengkapan',
            'perkakas' => 'Perkakas',
            'Cat' => 'Cat',
        ];
    }

    /**
     * Get available ecommerce platforms
     */
    public static function getEcommercePlatforms()
    {
        return [
            'shopee' => 'Shopee',
            'tokopedia' => 'Tokopedia',
            'tiktok' => 'TikTok Shop',
            'facebook' => 'Facebook Marketplace',
            'whatsapp' => 'WhatsApp Catalog',
        ];
    }
}