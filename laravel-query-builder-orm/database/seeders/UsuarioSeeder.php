<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear 5 usuarios con datos variados
        // Incluye usuarios con nombres que empiezan con R (Roberto y Ricardo)

        Usuario::create([
            'nombre' => 'Roberto García',
            'correo' => 'roberto@example.com',
            'telefono' => '2452-0101'
        ]);

        Usuario::create([
            'nombre' => 'María López',
            'correo' => 'maria@example.com',
            'telefono' => '7458-0102'
        ]);

        Usuario::create([
            'nombre' => 'Carlos Martínez',
            'correo' => 'carlos@example.com',
            'telefono' => '2365-0103'
        ]);

        Usuario::create([
            'nombre' => 'Ana Rodríguez',
            'correo' => 'ana@example.com',
            'telefono' => '7496-0104'
        ]);

        Usuario::create([
            'nombre' => 'Ricardo Fernández',
            'correo' => 'ricardo@example.com',
            'telefono' => '2147-0105'
        ]);
    }
}
