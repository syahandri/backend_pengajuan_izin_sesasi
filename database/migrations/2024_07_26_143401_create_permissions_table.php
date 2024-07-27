<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   /**
    * Run the migrations.
    */
   public function up(): void
   {
      Schema::create('permissions', function (Blueprint $table) {
         $table->id();
         $table->foreignIdFor(User::class);
         $table->date('date');
         $table->string('title');
         $table->text('details');
         $table->text('notes')->nullable();
         $table->enum('status', ['0', '1', '2', '3', '4'])->default('0');
         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('permissions');
   }
};
