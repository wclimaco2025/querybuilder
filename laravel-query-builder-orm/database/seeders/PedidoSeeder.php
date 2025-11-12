<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pedido;

class PedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear pedidos con totales variados (50-300 dólares)
        // Usuario con ID 2 (María López) tendrá múltiples pedidos
        // Usuario con ID 5 (Ricardo Fernández) tendrá al menos un pedido

        Pedido::create([
            'producto' => 'Laptop Dell',
            'cantidad' => 1,
            'total' => 250.00,
            'id_usuario' => 2
        ]);

        Pedido::create([
            'producto' => 'Mouse Logitech',
            'cantidad' => 2,
            'total' => 50.00,
            'id_usuario' => 2
        ]);

        Pedido::create([
            'producto' => 'Teclado Mecánico',
            'cantidad' => 1,
            'total' => 150.00,
            'id_usuario' => 1
        ]);

        Pedido::create([
            'producto' => 'Monitor Samsung',
            'cantidad' => 1,
            'total' => 300.00,
            'id_usuario' => 5
        ]);

        Pedido::create([
            'producto' => 'Webcam HD',
            'cantidad' => 1,
            'total' => 80.00,
            'id_usuario' => 3
        ]);

        Pedido::create([
            'producto' => 'Auriculares Bluetooth',
            'cantidad' => 1,
            'total' => 120.00,
            'id_usuario' => 2
        ]);

        Pedido::create([
            'producto' => 'Disco Duro Externo',
            'cantidad' => 1,
            'total' => 95.00,
            'id_usuario' => 4
        ]);
    }
}
