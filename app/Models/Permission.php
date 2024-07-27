<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
   use HasFactory;
   
   protected $guarded = ['id'];
   protected $appends = ['detail_status'];

   public function user(): BelongsTo
   {
      return $this->belongsTo(User::class);
   }

   protected function detailStatus(): Attribute
   {
      return Attribute::make(
         get: function() {
            return match ($this->status) {
               '0' => 'Pending',
               '1' => 'Diterima',
               '2' => 'Direvisi',
               '3' => 'Ditolak',
               '4' => 'Dibatalkan'
            };
         }
      );
   }

   public function scopeStatus($q, $status): void
   {
      $q->when($status != null, fn($q) => $q->where('status', $status));
   }
}
