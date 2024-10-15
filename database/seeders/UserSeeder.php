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
            'name' => 'administrador',
            'nit' => '9999999999',
            'email' => '',
            'password' => '4dminist4dorN43ps',
            'estado' => 1,
            'is_admin' => 1

        ]);
        
       
    }
}
