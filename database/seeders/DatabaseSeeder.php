<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
   /**
    * Seed the application's database.
    */
   public function run(): void
   {
      User::factory()->create([
         'name' => 'Admin',
         'email' => 'admin@sistem.com',
         'role' => '1',
         'is_verify' => 1,
         'password' => Hash::make('password'),
      ]);

      User::factory()->create([
         'name' => 'Verifikator',
         'email' => 'verifikator@sistem.com',
         'role' => '2',
         'is_verify' => 1,
         'password' => Hash::make('password'),
      ]);

      for ($i=0; $i < 10; $i++) { 
         Permission::create([
            'user_id' => fake('id')->numberBetween(3, 4),
            'title' => fake('id')->sentence(3),
            'date' => Carbon::now(),
            'title' => fake('id')->sentence(4),
            'details' => fake('id')->paragraph(3),
            'status' => '0'
         ]);
      }
   }
}
