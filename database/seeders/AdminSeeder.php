<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Administrador Principal',
            'email' => 'kualexander@gmail.com',
            'phone' => '9601046879',
            'password' => Hash::make('gio%Angie*'),
        ]);

        $admin->assignRole('Administrador');
    }
}
