<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'usuarios';

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'correo',
        'telefono',
    ];

    /**
     * Relación: Un usuario tiene muchos pedidos.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'id_usuario');
    }
}
