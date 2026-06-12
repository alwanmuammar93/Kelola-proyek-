<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CMSSection extends Model
{
    use HasFactory;

    protected $table = 'cms_sections';

    protected $fillable = [
        'section_key',
        'title',
        'subtitle',
        'subtitle2',
        'content',
        'image',
        'logo',
        'background_image',
        'button1_text',
        'button1_link',
        'button2_text',
        'button2_link',
        'metadata',
        'is_active',
        'updated_by',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_active' => 'boolean',
        'updated_by' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->user()->id_user;
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->user()->id_user;
            }
        });
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id_user');
    }

    public static function getByKey($key)
    {
        return self::where('section_key', $key)->first();
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        return asset($this->image);
    }

    public function getLogoUrlAttribute()
    {
        if (!$this->logo) {
            return null;
        }

        return asset($this->logo);
    }

    public function getBackgroundImageUrlAttribute()
    {
        if (!$this->background_image) {
            return null;
        }

        return asset($this->background_image);
    }

    public function getSubtitleLinesAttribute()
    {
        if (!$this->subtitle) {
            return ['', '', ''];
        }

        return explode('|', $this->subtitle);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ========================================
    // CATALOG PRODUCTS METHODS - START
    // ========================================

    /**
     * Get all catalog products from metadata
     * 
     * @return array
     */
    public function getCatalogProductsAttribute()
    {
        if (!$this->metadata || !isset($this->metadata['products'])) {
            return [];
        }
        
        return $this->metadata['products'];
    }

    /**
     * Get all available catalog categories from metadata
     * 
     * @return array
     */
    public function getCatalogCategoriesAttribute()
    {
        if (!$this->metadata || !isset($this->metadata['categories'])) {
            return [];
        }
        
        return $this->metadata['categories'];
    }

    /**
     * Add a new product to catalog
     * 
     * @param array $product ['name', 'ecommerce_link', 'category', 'image']
     * @return $this
     */
    public function addCatalogProduct(array $product)
    {
        $metadata = $this->metadata ?? [];
        
        if (!isset($metadata['products'])) {
            $metadata['products'] = [];
        }

        // Generate unique ID for product
        $product['id'] = 'prod_' . uniqid();
        $product['created_at'] = now()->toDateTimeString();
        
        $metadata['products'][] = $product;
        $this->metadata = $metadata;
        
        return $this;
    }

    /**
     * Update existing product in catalog
     * 
     * @param string $productId
     * @param array $data
     * @return $this
     */
    public function updateCatalogProduct($productId, array $data)
    {
        $metadata = $this->metadata ?? [];
        
        if (!isset($metadata['products'])) {
            return $this;
        }

        foreach ($metadata['products'] as $key => $product) {
            if ($product['id'] === $productId) {
                $metadata['products'][$key] = array_merge($product, $data);
                $metadata['products'][$key]['updated_at'] = now()->toDateTimeString();
                break;
            }
        }

        $this->metadata = $metadata;
        
        return $this;
    }

    /**
     * Delete product from catalog
     * 
     * @param string $productId
     * @return $this
     */
    public function deleteCatalogProduct($productId)
    {
        $metadata = $this->metadata ?? [];
        
        if (!isset($metadata['products'])) {
            return $this;
        }

        $metadata['products'] = array_values(
            array_filter($metadata['products'], function($product) use ($productId) {
                // Delete image file if exists
                if ($product['id'] === $productId && isset($product['image']) && $product['image']) {
                    if (file_exists(public_path($product['image']))) {
                        @unlink(public_path($product['image']));
                    }
                }
                return $product['id'] !== $productId;
            })
        );

        $this->metadata = $metadata;
        
        return $this;
    }

    /**
     * Get single product by ID
     * 
     * @param string $productId
     * @return array|null
     */
    public function getCatalogProduct($productId)
    {
        $products = $this->catalog_products;
        
        foreach ($products as $product) {
            if ($product['id'] === $productId) {
                return $product;
            }
        }
        
        return null;
    }

    /**
     * Set/Replace all catalog categories
     * 
     * @param array $categories
     * @return $this
     */
    public function setCatalogCategories(array $categories)
    {
        $metadata = $this->metadata ?? [];
        $metadata['categories'] = array_values(array_unique($categories));
        $this->metadata = $metadata;
        
        return $this;
    }

    /**
     * Add single category to catalog
     * 
     * @param string $category
     * @return $this
     */
    public function addCatalogCategory($category)
    {
        $metadata = $this->metadata ?? [];
        
        if (!isset($metadata['categories'])) {
            $metadata['categories'] = [];
        }

        if (!in_array($category, $metadata['categories'])) {
            $metadata['categories'][] = $category;
        }

        $this->metadata = $metadata;
        
        return $this;
    }

    /**
     * Delete category from catalog
     * 
     * @param string $category
     * @return $this
     */
    public function deleteCatalogCategory($category)
    {
        $metadata = $this->metadata ?? [];
        
        if (!isset($metadata['categories'])) {
            return $this;
        }

        $metadata['categories'] = array_values(
            array_filter($metadata['categories'], function($cat) use ($category) {
                return $cat !== $category;
            })
        );

        $this->metadata = $metadata;
        
        return $this;
    }

    /**
     * Get products filtered by category
     * 
     * @param string $category
     * @return array
     */
    public function getCatalogProductsByCategory($category)
    {
        $products = $this->catalog_products;
        
        return array_filter($products, function($product) use ($category) {
            return isset($product['category']) && $product['category'] === $category;
        });
    }

    /**
     * Get product image URL with fallback
     * 
     * @param array $product
     * @return string
     */
    public function getProductImageUrl($product)
    {
        if (!isset($product['image']) || !$product['image']) {
            return asset('images/no-image.png'); // fallback image
        }

        return asset($product['image']);
    }

    // ========================================
    // CATALOG PRODUCTS METHODS - END
    // ========================================

    // ========================================
    // PROJECT GALLERY METHODS - START
    // ========================================

    /**
     * Get all projects from metadata
     * 
     * @return array
     */
    public function getProjectsAttribute()
    {
        if (!$this->metadata || !isset($this->metadata['projects'])) {
            return [];
        }
        
        return $this->metadata['projects'];
    }

    /**
     * Get all available project categories from metadata
     * 
     * @return array
     */
    public function getProjectCategoriesAttribute()
    {
        if (!$this->metadata || !isset($this->metadata['project_categories'])) {
            return [];
        }
        
        return $this->metadata['project_categories'];
    }

    /**
     * Add a new project to gallery
     * 
     * @param array $project ['name', 'description', 'category', 'external_link', 'image']
     * @return $this
     */
    public function addProject(array $project)
    {
        $metadata = $this->metadata ?? [];
        
        if (!isset($metadata['projects'])) {
            $metadata['projects'] = [];
        }

        // Generate unique ID for project
        $project['id'] = 'proj_' . uniqid();
        $project['created_at'] = now()->toDateTimeString();
        $project['updated_at'] = now()->toDateTimeString();
        
        $metadata['projects'][] = $project;
        $this->metadata = $metadata;
        
        return $this;
    }

    /**
     * Update existing project in gallery
     * 
     * @param string $projectId
     * @param array $data
     * @return $this
     */
    public function updateProject($projectId, array $data)
    {
        $metadata = $this->metadata ?? [];
        
        if (!isset($metadata['projects'])) {
            return $this;
        }

        foreach ($metadata['projects'] as $key => $project) {
            if ($project['id'] === $projectId) {
                $metadata['projects'][$key] = array_merge($project, $data);
                $metadata['projects'][$key]['updated_at'] = now()->toDateTimeString();
                break;
            }
        }

        $this->metadata = $metadata;
        
        return $this;
    }

    /**
     * Delete project from gallery
     * 
     * @param string $projectId
     * @return $this
     */
    public function deleteProject($projectId)
    {
        $metadata = $this->metadata ?? [];
        
        if (!isset($metadata['projects'])) {
            return $this;
        }

        $metadata['projects'] = array_values(
            array_filter($metadata['projects'], function($project) use ($projectId) {
                // Delete image file if exists
                if ($project['id'] === $projectId && isset($project['image']) && $project['image']) {
                    if (file_exists(public_path($project['image']))) {
                        @unlink(public_path($project['image']));
                    }
                }
                return $project['id'] !== $projectId;
            })
        );

        $this->metadata = $metadata;
        
        return $this;
    }

    /**
     * Get single project by ID
     * 
     * @param string $projectId
     * @return array|null
     */
    public function getProject($projectId)
    {
        $projects = $this->projects;
        
        foreach ($projects as $project) {
            if ($project['id'] === $projectId) {
                return $project;
            }
        }
        
        return null;
    }

    /**
     * Set/Replace all project categories
     * 
     * @param array $categories
     * @return $this
     */
    public function setProjectCategories(array $categories)
    {
        $metadata = $this->metadata ?? [];
        $metadata['project_categories'] = array_values(array_unique($categories));
        $this->metadata = $metadata;
        
        return $this;
    }

    /**
     * Add single category to projects
     * 
     * @param string $category
     * @return $this
     */
    public function addProjectCategory($category)
    {
        $metadata = $this->metadata ?? [];
        
        if (!isset($metadata['project_categories'])) {
            $metadata['project_categories'] = [];
        }

        if (!in_array($category, $metadata['project_categories'])) {
            $metadata['project_categories'][] = $category;
        }

        $this->metadata = $metadata;
        
        return $this;
    }

    /**
     * Delete category from projects
     * 
     * @param string $category
     * @return $this
     */
    public function deleteProjectCategory($category)
    {
        $metadata = $this->metadata ?? [];
        
        if (!isset($metadata['project_categories'])) {
            return $this;
        }

        $metadata['project_categories'] = array_values(
            array_filter($metadata['project_categories'], function($cat) use ($category) {
                return $cat !== $category;
            })
        );

        $this->metadata = $metadata;
        
        return $this;
    }

    /**
     * Get projects filtered by category
     * 
     * @param string $category
     * @return array
     */
    public function getProjectsByCategory($category)
    {
        $projects = $this->projects;
        
        return array_filter($projects, function($project) use ($category) {
            return isset($project['category']) && $project['category'] === $category;
        });
    }

    /**
     * Get project image URL with fallback
     * 
     * @param array $project
     * @return string
     */
    public function getProjectImageUrl($project)
    {
        if (!isset($project['image']) || !$project['image']) {
            return asset('images/no-image.png'); // fallback image
        }

        return asset($project['image']);
    }

    // ========================================
    // PROJECT GALLERY METHODS - END
    // ========================================
}