<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Specialist;
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
            'email' => 'kualexander69@gmail.com',
            'phone' => '9601046879',
            'password' => Hash::make('gio%Angie*'),
            'profile_photo_path' => 'profile-photos/nJEiBP1AsCsx4TcvnD82N1UcG5v8DNv4mc4beaVz.jpg',
        ]);
        $admin->assignRole('Administrador');

        $client = User::create([
            'name' => 'Cliente 1',
            'email' => 's.p.a.r.c.k.0.1.1.9@gmail.com',
            'phone' => '9994358818',
            'password' => Hash::make('Bn12_Al0'),
            'profile_photo_path' => 'profile-photos/yicAP3AxF8JJJlLDmlqMVsoPliTVKXKyh1Fkyej9.png',
        ]);
        $client->assignRole('Cliente');

        $stylist = User::create([
            'name' => 'Estilista 1',
            'email' => 'warrior3011232@gmail.com',
            'phone' => '9983675794',
            'password' =>Hash::make('D18e?ai#4k'),
            'profile_photo_path' => 'profile-photos/H8guYGRfrF27P6agfnhHlhiLXYgeO9ziJXph3lCc.jpg',
        ]);
        $stylist->assignRole('Estilista');

        Specialist::create([
            'user_id' => $stylist->id,
            'specialty' => 'Estilista General',
            'active' => true,
        ]);
    }
}
