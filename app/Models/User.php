<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama_lengkap', 'email', 'no_hp', 'password',
        'role', 'status_akun', 'eo_verified', 'foto_profil',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'eo_verified'       => 'boolean',
        ];
    }

    public function events()       { return $this->hasMany(Event::class, 'pengelola_id'); }
    public function orders()       { return $this->hasMany(Order::class); }
    public function eoApplication(){ return $this->hasOne(EoApplication::class); }
    public function eoWithdrawals(){ return $this->hasMany(EoWithdrawal::class, 'pengelola_id'); }

    public function isPengelola(): bool { return in_array($this->role, ['pengelola', 'admin']); }
    public function isAdmin(): bool     { return $this->role === 'admin'; }
    public function isActive(): bool    { return $this->status_akun === 'active'; }

    public function getNamaPanggilanAttribute(): string
    {
        $parts = explode(' ', trim($this->nama_lengkap));
        return isset($parts[1]) ? $parts[0].' '.$parts[1] : $parts[0];
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->foto_profil) {
            if (str_starts_with($this->foto_profil, 'http')) return $this->foto_profil;
            $path = storage_path('app/public/' . $this->foto_profil);
            if (file_exists($path)) return asset('storage/' . $this->foto_profil);
        }
        return 'https://ui-avatars.com/api/?name='.urlencode($this->nama_panggilan).'&background=F5C400&color=001840&bold=true';
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function wishlistedEvents()
    {
        return $this->belongsToMany(Event::class, 'wishlists')->withTimestamps();
    }
}
