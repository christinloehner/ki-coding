<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'bio',
        'avatar',
        'website',
        'location',
        'job_title',
        'company',
        'birthday',
        'github_username',
        'twitter_username',
        'linkedin_username',
        'instagram_username',
        'privacy_settings',
        'profile_completed',
        'last_profile_update',
        'is_active',
        'is_banned',
        'banned_until',
        'ban_reason',
        'reputation_score',
        'articles_count',
        'comments_count',
        'last_activity_at',
        'preferences',
        'notification_settings',
        'email_notifications',
        'invitation_token',
        'invited_by',
        'invited_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
        'invitation_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birthday' => 'date',
            'privacy_settings' => 'array',
            'profile_completed' => 'boolean',
            'last_profile_update' => 'datetime',
            'is_active' => 'boolean',
            'is_banned' => 'boolean',
            'banned_until' => 'datetime',
            'reputation_score' => 'integer',
            'articles_count' => 'integer',
            'comments_count' => 'integer',
            'last_activity_at' => 'datetime',
            'preferences' => 'array',
            'notification_settings' => 'array',
            'email_notifications' => 'boolean',
            'invited_at' => 'datetime',
        ];
    }

    /**
     * Get the user who invited this user.
     */
    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    /**
     * Get the users invited by this user.
     */
    public function invitedUsers(): HasMany
    {
        return $this->hasMany(User::class, 'invited_by');
    }

    /**
     * Get the articles created by this user.
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    /**
     * Get the published articles created by this user.
     */
    public function publishedArticles(): HasMany
    {
        return $this->articles()->where('status', 'published');
    }

    /**
     * Get the comments created by this user.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the approved comments created by this user.
     */
    public function approvedComments(): HasMany
    {
        return $this->comments()->where('status', 'approved');
    }

    /**
     * Get the article revisions created by this user.
     */
    public function revisions(): HasMany
    {
        return $this->hasMany(ArticleRevision::class);
    }

    /**
     * Scope to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to only include non-banned users.
     */
    public function scopeNotBanned($query)
    {
        return $query->where(function ($q) {
            $q->where('is_banned', false)
              ->orWhere('banned_until', '<', now());
        });
    }

    /**
     * Check if the user is banned.
     */
    public function isBanned(): bool
    {
        if (!$this->is_banned) {
            return false;
        }

        if ($this->banned_until && $this->banned_until->isPast()) {
            $this->update(['is_banned' => false, 'banned_until' => null]);
            return false;
        }

        return true;
    }

    /**
     * Check if the user can create articles.
     */
    public function canCreateArticles(): bool
    {
        return $this->is_active && 
               !$this->isBanned() && 
               $this->hasVerifiedEmail() &&
               ($this->hasPermissionTo('create articles') || $this->hasRole(['admin', 'moderator', 'editor', 'contributor']));
    }

    /**
     * Check if the user can comment on articles.
     */
    public function canComment(): bool
    {
        return $this->is_active && 
               !$this->isBanned() && 
               $this->hasVerifiedEmail() &&
               ($this->hasPermissionTo('create comments') || $this->hasRole(['admin', 'moderator', 'editor', 'contributor', 'user']));
    }

    /**
     * Check if the user can moderate content.
     */
    public function canModerate(): bool
    {
        return $this->hasRole(['admin', 'moderator']);
    }

    /**
     * Get the user's avatar URL.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/avatars/' . $this->avatar);
        }
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=3B82F6&background=EBF4FF';
    }

    /**
     * Get the user's profile URL.
     */
    public function getProfileUrlAttribute(): string
    {
        return route('wiki.users.show', $this->username ?: $this->id);
    }

    /**
     * Update the user's last activity timestamp.
     */
    public function updateLastActivity(): void
    {
        $this->update(['last_activity_at' => now()]);
    }

    /**
     * Ban the user.
     */
    public function ban(string $reason = null, \DateTime $until = null): bool
    {
        return $this->update([
            'is_banned' => true,
            'ban_reason' => $reason,
            'banned_until' => $until,
        ]);
    }

    /**
     * Unban the user.
     */
    public function unban(): bool
    {
        return $this->update([
            'is_banned' => false,
            'ban_reason' => null,
            'banned_until' => null,
        ]);
    }

    /**
     * Increment reputation score.
     */
    public function incrementReputation(int $amount = 1): void
    {
        $this->increment('reputation_score', $amount);
    }

    /**
     * Decrement reputation score.
     */
    public function decrementReputation(int $amount = 1): void
    {
        $this->decrement('reputation_score', $amount);
    }

    /**
     * Get the user's reputation level.
     */
    public function getReputationLevelAttribute(): string
    {
        $score = $this->reputation_score;
        
        if ($score >= 1000) return 'Expert';
        if ($score >= 500) return 'Advanced';
        if ($score >= 100) return 'Intermediate';
        if ($score >= 10) return 'Beginner';
        
        return 'Newcomer';
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        
        // Assign default role to new users
        static::created(function ($user) {
            // Assign basic user role - contributor only after manual approval/invitation
            $user->assignRole('user');
            $user->givePermissionTo([
                'view articles',
                'view categories', 
                'view tags',
                'view comments',
                'create comments',
                'edit own comments',
                'delete own comments',
                'edit own profile'
                // REMOVED: 'create articles', 'edit own articles', 'delete own articles', 'publish articles'
                // These require contributor role or higher, assigned manually by admins
            ]);
        });
    }

    /**
     * Generate a new invitation token.
     */
    public function generateInvitationToken(): string
    {
        $token = bin2hex(random_bytes(32));
        $this->update(['invitation_token' => $token]);
        return $token;
    }

    /**
     * Get default privacy settings.
     */
    public function getDefaultPrivacySettings(): array
    {
        return [
            'show_email' => false,
            'show_birthday' => false,
            'show_location' => true,
            'show_website' => true,
            'show_job_title' => true,
            'show_company' => true,
            'show_bio' => true,
            'show_social_media' => true,
            'show_articles' => true,
            'show_reputation' => true,
            'show_joined_date' => true,
            'show_last_activity' => false,
        ];
    }

    /**
     * Get privacy settings with defaults.
     */
    public function getPrivacySettings(): array
    {
        return array_merge($this->getDefaultPrivacySettings(), $this->privacy_settings ?? []);
    }

    /**
     * Check if a field should be visible in public profile.
     */
    public function shouldShowField(string $field): bool
    {
        $settings = $this->getPrivacySettings();
        return $settings[$field] ?? false;
    }

    /**
     * Get all social media links.
     */
    public function getSocialMediaLinks(): array
    {
        $links = [];
        
        if ($this->github_username) {
            $links['github'] = [
                'url' => "https://github.com/{$this->github_username}",
                'icon' => 'fab fa-github',
                'label' => 'GitHub'
            ];
        }
        
        if ($this->twitter_username) {
            $links['twitter'] = [
                'url' => "https://twitter.com/{$this->twitter_username}",
                'icon' => 'fab fa-twitter',
                'label' => 'Twitter'
            ];
        }
        
        if ($this->linkedin_username) {
            $links['linkedin'] = [
                'url' => "https://linkedin.com/in/{$this->linkedin_username}",
                'icon' => 'fab fa-linkedin',
                'label' => 'LinkedIn'
            ];
        }
        
        if ($this->instagram_username) {
            $links['instagram'] = [
                'url' => "https://instagram.com/{$this->instagram_username}",
                'icon' => 'fab fa-instagram',
                'label' => 'Instagram'
            ];
        }
        
        return $links;
    }

    /**
     * Get the age from birthday.
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->birthday) {
            return null;
        }
        
        return $this->birthday->diffInYears(now());
    }

    /**
     * Check if profile is complete.
     */
    public function updateProfileCompletion(): void
    {
        $requiredFields = ['name', 'bio'];
        $optionalFields = ['avatar', 'location', 'website'];
        
        $completedRequired = collect($requiredFields)->every(fn($field) => !empty($this->$field));
        $completedOptional = collect($optionalFields)->filter(fn($field) => !empty($this->$field))->count();
        
        $isComplete = $completedRequired && $completedOptional >= 2;
        
        $this->update([
            'profile_completed' => $isComplete,
            'last_profile_update' => now()
        ]);
    }
}
