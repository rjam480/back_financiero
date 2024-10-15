<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'NUEVA EPS(FINANCIERO)',
            'nit' => '900156264',
            'email' => '',
            'password' => 'Adm#0N#0ps2023#*',
            'estado' => 1,
            'is_admin' => 1

        ]);
        
       
    }
}
