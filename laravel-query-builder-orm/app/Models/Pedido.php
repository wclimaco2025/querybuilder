<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'pedidos';

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'producto',
        'cantidad',
        'total',
        'id_usuario',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'cantidad' => 'integer',
        'total' => 'decimal:2',
    ];

    /**
     * Relación: Un pedido pertenece a un usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}
