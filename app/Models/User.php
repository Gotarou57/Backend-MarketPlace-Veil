<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Str;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // seller, admin
        'phone',
        'address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // JWT Methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // Relationships
    public function store()
    {
        return $this->hasOne(Store::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Helper: Check if user is admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Helper: Check if user is seller
    public function isSeller()
    {
        return $this->role === 'seller';
    }

    /**
     * Generate unique username from store name
     * Format: [store_slug]_[random_6_chars]
     * Example: novanz_racing_a8d4f2
     * 
     * @param string $storeName
     * @return string
     */
    public static function generateUsername($storeName)
    {
        // Convert store name to slug (lowercase, replace spaces with underscore)
        $baseUsername = Str::slug($storeName, '_');
        
        // Limit to 20 characters to leave room for random suffix
        $baseUsername = Str::limit($baseUsername, 20, '');
        
        // Remove trailing underscore if any
        $baseUsername = rtrim($baseUsername, '_');
        
        // Generate unique username with random suffix
        $username = $baseUsername;
        $counter = 0;
        
        // Keep generating until we find a unique username
        while (self::where('email', $username . '@seller.marketplace')->exists()) {
            $counter++;
            
            if ($counter === 1) {
                // First attempt: add 6 random characters
                $randomSuffix = Str::lower(Str::random(6));
                $username = $baseUsername . '_' . $randomSuffix;
            } else {
                // Subsequent attempts: add counter
                $randomSuffix = Str::lower(Str::random(6));
                $username = $baseUsername . '_' . $randomSuffix . '_' . $counter;
            }
            
            // Prevent infinite loop (safety check)
            if ($counter > 100) {
                // Fall back to completely random username
                $username = 'seller_' . Str::lower(Str::random(10));
                break;
            }
        }
        
        return $username;
    }

    /**
     * Alternative method: Generate username from email prefix
     * Format: [email_prefix]_[random_4_chars]
     * Example: haqiqi_iqbal_a8d4
     * 
     * @param string $email
     * @return string
     */
    public static function generateUsernameFromEmail($email)
    {
        // Get email prefix (before @)
        $emailPrefix = explode('@', $email)[0];
        
        // Convert to slug format
        $baseUsername = Str::slug($emailPrefix, '_');
        
        // Limit to 20 characters
        $baseUsername = Str::limit($baseUsername, 20, '');
        $baseUsername = rtrim($baseUsername, '_');
        
        // Add random suffix for uniqueness
        $randomSuffix = Str::lower(Str::random(4));
        $username = $baseUsername . '_' . $randomSuffix;
        
        // Ensure uniqueness
        $counter = 1;
        while (self::where('email', $username . '@seller.marketplace')->exists()) {
            $randomSuffix = Str::lower(Str::random(4));
            $username = $baseUsername . '_' . $randomSuffix . '_' . $counter;
            $counter++;
            
            if ($counter > 50) {
                $username = 'seller_' . Str::lower(Str::random(10));
                break;
            }
        }
        
        return $username;
    }

    /**
     * Generate simple sequential username
     * Format: seller_[padded_number]
     * Example: seller_001, seller_002
     * 
     * @return string
     */
    public static function generateSimpleUsername()
    {
        // Get the last user ID
        $lastUser = self::orderBy('id', 'desc')->first();
        $nextNumber = $lastUser ? $lastUser->id + 1 : 1;
        
        // Pad with zeros (3 digits minimum)
        $paddedNumber = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        
        return 'seller_' . $paddedNumber;
    }

    /**
     * Validate if username is available
     * 
     * @param string $username
     * @return bool
     */
    public static function isUsernameAvailable($username)
    {
        return !self::where('email', $username . '@seller.marketplace')->exists();
    }

    /**
     * Get display name for user
     * Returns name if available, otherwise email prefix
     * 
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        return $this->name ?: explode('@', $this->email)[0];
    }

    /**
     * Get username from email (for display purposes)
     * 
     * @return string
     */
    public function getUsernameAttribute()
    {
        if (Str::contains($this->email, '@seller.marketplace')) {
            return str_replace('@seller.marketplace', '', $this->email);
        }
        
        return explode('@', $this->email)[0];
    }
}