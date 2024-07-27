<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
   use HasFactory, Notifiable, HasApiTokens;

   /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */
   protected $guarded = ['id'];
   protected $appends = ['role_name'];

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
         'password' => 'hashed',
      ];
   }

   protected function roleName(): Attribute
   {
      return Attribute::make(
         get: function () {
            return match ($this->role) {
               '0' => 'user',
               '1' => 'admin',
               '2' => 'verifikator',
            };
         }
      );
   }

   public function scopeFilter($q, $verify): void
   {
      $q->when($verify != null, fn ($q) => $q->where('is_verify', $verify));
   }

   public function permissions(): HasMany
   {
      return $this->hasMany(Permission::class);
   }
}
