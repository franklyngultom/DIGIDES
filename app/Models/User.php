<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, LogsActivity, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar_path',
        'is_active',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /**
     * Activity log configuration.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'phone', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('user_management')
            ->setDescriptionForEvent(fn (string $eventName) => "User {$this->name} was {$eventName}");
    }

    /**
     * Accessor for user avatar URL.
     */
    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->avatar_path && file_exists(public_path('storage/'.$this->avatar_path))) {
                    return asset('storage/'.$this->avatar_path);
                }

                if ($this->avatar_path && file_exists(public_path($this->avatar_path))) {
                    return asset($this->avatar_path);
                }

                if (!$this->avatar_path && $this->email === 'staff@desa.id' && file_exists(public_path('images/avatar-jack.jpg'))) {
                    return asset('images/avatar-jack.jpg');
                }

                $encodedName = urlencode($this->name);

                return "https://ui-avatars.com/api/?name={$encodedName}&background=114443&color=d4ed31&bold=true";
            }
        );
    }

    /**
     * Accessor for primary role name.
     */
    protected function roleName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->roles->first()?->name ?? 'Tanpa Role'
        );
    }

    /**
     * Scope active users.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope search by keyword.
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (blank($search)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    /**
     * Relationship to CitizenProfile.
     */
    public function citizenProfile(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CitizenProfile::class);
    }

    /**
     * Helper to check if user is registered as citizen (Masyarakat).
     */
    public function isMasyarakat(): bool
    {
        return $this->hasRole('Masyarakat');
    }
}
