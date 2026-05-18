<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $service1 = Service::create([
            'name' => 'Corte de Cabello Dama',
            'description' => 'Incluye lavado, tratamiento hidratante exprés y estilizado final.',
            'category' => 'Estilismo',
            'price' => '180.00',
            'duration' => '45',
        ]);

        $service2 = Service::create([
            'name' => 'Tinte Global / Cambio de Tono',
            'description' => 'Aplicación de color uniforme con productos de alta gama para proteger tu fibra capilar.',
            'category' => 'Estilismo',
            'price' => '650.00',
            'duration' => '120',
        ]);

        $service3 = Service::create([
            'name' => 'Efectos de Color (Balayage / Mechas)',
            'description' => 'Diseño de aclaración personalizado. Requiere diagnóstico previo en el salón.',
            'category' => 'Estilismo',
            'price' => '1300.00',
            'duration' => '180',
        ]);

        $service4 = Service::create([
            'name' => 'Tratamiento de Keratina Alaciante',
            'description' => 'Elimina el frizz por completo, aporta brillo espejo y un lacio natural.',
            'category' => 'Estilismo',
            'price' => '980.00',
            'duration' => '150',
        ]);

        $service5 = Service::create([
            'name' => 'Peinado y Maquillaje Social',
            'description' => 'Paquete completo para eventos sociales con productos de larga duración.',
            'category' => 'Estilismo',
            'price' => '750.00',
            'duration' => '90',
        ]);

        $service6 = Service::create([
            'name' => 'Esmaltado en Gelish (Manos)',
            'description' => 'Aplicación de color semipermanente en uña natural con una amplia variedad de tonos.',
            'category' => 'Pedicura',
            'price' => '160',
            'duration' => '40',
        ]);

        $service7 = Service::create([
            'name' => 'Uñas Acrílicas (Set Nuevo Básico)',
            'description' => 'Estructura en tamaño corto o mediano con esmaltado liso de tu elección.',
            'category' => 'Pedicura',
            'price' => '380',
            'duration' => '90',
        ]);

        $service8 = Service::create([
            'name' => 'Uñas Acrílicas con Diseño Completo',
            'description' => 'Incluye efectos (espejo, ojo de gato), cristales o arte pintado a mano alzada.',
            'category' => 'Pedicura',
            'price' => '480',
            'duration' => '120',
        ]);

        $service9 = Service::create([
            'name' => 'Manicura Rusa Combinada',
            'description' => 'Limpieza profunda de cutículas con torno para un acabado limpio y duradero.',
            'category' => 'Pedicura',
            'price' => '220',
            'duration' => '50',
        ]);

        $service10 = Service::create([
            'name' => 'Pedicura Spa Premium',
            'description' => 'Tina de hidromasaje, exfoliación, remoción de callosidades, mascarilla e hidratación.',
            'category' => 'Pedicura',
            'price' => '320',
            'duration' => '60',
        ]);
    }
}
