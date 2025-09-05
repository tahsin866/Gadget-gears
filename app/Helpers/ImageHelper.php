<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Get the public URL for an image stored in storage/app/public
     */
    public static function getImageUrl($imagePath)
    {
        if (!$imagePath) {
            return 'https://placehold.co/400x300/E5E7EB/9CA3AF/png?text=No+Image';
        }

        // Check if it's already a full URL
        if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
            return $imagePath;
        }

        // For production/cPanel deployment, use direct file access
        if (app()->environment('production')) {
            // Remove 'products/' prefix if exists since we'll add it back
            $cleanPath = str_replace('products/', '', $imagePath);
            return asset('storage/products/' . $cleanPath);
        }

        // For local development, use the standard Laravel storage URL
        return asset('storage/' . $imagePath);
    }

    /**
     * Generate full file path for storage
     */
    public static function getStoragePath($imagePath)
    {
        return storage_path('app/public/' . $imagePath);
    }
}
