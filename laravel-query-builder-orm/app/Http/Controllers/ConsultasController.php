<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ConsultasController extends Controller
{
    /**
     * Muestra la lista de todas las consultas disponibles
     *
     * Este método retorna un índice con todas las consultas SQL implementadas
     * en el sistema, proporcionando el nombre y la URL de cada una.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $consultas = [
            [
                'id' => 1,
                'nombre' => 'Pedidos del usuario con ID 2',
                'descripcion' => 'Recupera todos los pedidos asociados al usuario con ID 2',
                'url' => url('/consultas/pedidos-usuario-2')
            ],
            [
                'id' => 2,
                'nombre' => 'Pedidos con información de usuarios',
                'descripcion' => 'Muestra pedidos con datos completos de los usuarios (JOIN)',
                'url' => url('/consultas/pedidos-con-usuarios')
            ],
            [
                'id' => 3,
                'nombre' => 'Pedidos entre $100 y $250',
                'descripcion' => 'Filtra pedidos dentro del rango de precio especificado',
                'url' => url('/consultas/pedidos-rango-precio')
            ],
            [
                'id' => 4,
                'nombre' => 'Usuarios que comienzan con "R"',
                'descripcion' => 'Busca usuarios cuyo nombre inicia con la letra R',
                'url' => url('/consultas/usuarios-con-r')
            ],
            [
                'id' => 5,
                'nombre' => 'Contar pedidos del usuario con ID 5',
                'descripcion' => 'Cuenta el número total de pedidos del usuario con ID 5',
                'url' => url('/consultas/contar-pedidos-usuario-5')
            ],
            [
                'id' => 6,
                'nombre' => 'Pedidos ordenados por total descendente',
                'descripcion' => 'Lista todos los pedidos ordenados de mayor a menor precio',
                'url' => url('/consultas/pedidos-ordenados-desc')
            ],
            [
                'id' => 7,
                'nombre' => 'Suma total de todos los pedidos',
                'descripcion' => 'Calcula el valor total de todos los pedidos en el sistema',
                'url' => url('/consultas/suma-total-pedidos')
            ],
            [
                'id' => 8,
                'nombre' => 'Pedido más económico con usuario',
                'descripcion' => 'Encuentra el pedido de menor valor con información del usuario',
                'url' => url('/consultas/pedido-mas-economico')
            ],
            [
                'id' => 9,
                'nombre' => 'Pedidos agrupados por usuario',
                'descripcion' => 'Agrupa todos los pedidos organizados por usuario',
                'url' => url('/consultas/pedidos-agrupados')
            ]
        ];

        return response()->json([
            'mensaje' => 'Sistema de Consultas - Laravel Query Builder y ORM',
            'total_consultas' => count($consultas),
            'consultas' => $consultas
        ], 200);
    }

    /**
     * Consulta 1: Recupera todos los pedidos del usuario con ID 2
     *
     * Esta consulta utiliza Eloquent ORM para filtrar los pedidos que pertenecen
     * al usuario con ID 2. El método where() permite especificar la condición
     * de búsqueda (id_usuario = 2) y get() ejecuta la consulta retornando
     * una colección con todos los registros que cumplen el criterio.
     *
     * Si el usuario no tiene pedidos, retornará una colección vacía.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function pedidosUsuario2()
    {
        // Consulta usando Eloquent ORM: filtra pedidos por id_usuario = 2
        $pedidos = Pedido::where('id_usuario', 2)->get();

        return response()->json($pedidos);
    }

    /**
     * Consulta 2: Recupera pedidos con información completa de usuarios (JOIN)
     *
     * Esta consulta realiza un JOIN entre las tablas pedidos y usuarios para
     * obtener información detallada de cada pedido junto con los datos del
     * usuario que lo realizó. Se utiliza Query Builder con el método join()
     * para combinar ambas tablas mediante la clave foránea id_usuario.
     *
     * El método select() especifica exactamente qué campos queremos retornar:
     * - De pedidos: producto, cantidad, total
     * - De usuarios: nombre, correo
     *
     * Esta técnica es útil para generar reportes completos sin necesidad de
     * realizar múltiples consultas separadas (evita el problema N+1).
     *
     * Alternativa con Eloquent: Pedido::with('usuario:id,nombre,correo')->get()
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function pedidosConUsuarios()
    {
        // Consulta usando Query Builder con JOIN
        // Combina la tabla pedidos con usuarios mediante la relación id_usuario
        $pedidos = DB::table('pedidos')
            ->join('usuarios', 'pedidos.id_usuario', '=', 'usuarios.id')
            ->select(
                'pedidos.producto',
                'pedidos.cantidad',
                'pedidos.total',
                'usuarios.nombre as nombre_usuario',
                'usuarios.correo as correo_usuario'
            )
            ->get();

        return response()->json($pedidos);
    }

    /**
     * Consulta 3: Recupera pedidos con total entre $100 y $250
     *
     * Esta consulta utiliza Eloquent ORM con el método whereBetween() para
     * filtrar pedidos cuyo total se encuentre dentro de un rango específico.
     * whereBetween() acepta el nombre del campo y un array con dos valores:
     * [valor_mínimo, valor_máximo], ambos inclusive.
     *
     * En este caso, filtramos pedidos donde:
     * total >= 100 AND total <= 250
     *
     * Si no existen pedidos en este rango de precios, retornará una colección
     * vacía (array JSON vacío).
     *
     * Esta técnica es útil para filtrar registros por rangos numéricos o de fechas.
     *
     * Alternativa con Query Builder: DB::table('pedidos')->whereBetween('total', [100, 250])->get()
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function pedidosRangoPrecio()
    {
        // Consulta usando Eloquent ORM: filtra pedidos con total entre 100 y 250 (inclusive)
        $pedidos = Pedido::whereBetween('total', [100, 250])->get();

        return response()->json($pedidos);
    }

    /**
     * Consulta 4: Recupera usuarios cuyo nombre comienza con la letra "R"
     *
     * Esta consulta utiliza Eloquent ORM con el operador LIKE para realizar
     * búsquedas por patrones de texto. El patrón 'R%' significa:
     * - R: el nombre debe comenzar con la letra R (mayúscula)
     * - %: seguido de cualquier secuencia de caracteres
     *
     * El operador LIKE es case-insensitive por defecto en MySQL, por lo que
     * encontrará nombres que empiecen con 'R' o 'r' (Roberto, Ricardo, rosa, etc.)
     *
     * Esta técnica es útil para búsquedas de texto parciales como:
     * - Nombres que empiezan con cierta letra: 'R%'
     * - Nombres que terminan con cierto texto: '%ez'
     * - Nombres que contienen cierto texto: '%car%'
     *
     * Si no existen usuarios que cumplan el criterio, retornará una colección vacía.
     *
     * Alternativa con Query Builder: DB::table('usuarios')->where('nombre', 'LIKE', 'R%')->get()
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function usuariosConR()
    {
        // Consulta usando Eloquent ORM: busca usuarios cuyo nombre empiece con 'R'
        // El patrón 'R%' indica que debe comenzar con R seguido de cualquier texto
        $usuarios = Usuario::where('nombre', 'LIKE', 'R%')->get();

        return response()->json($usuarios);
    }

    /**
     * Consulta 5: Cuenta el número total de pedidos del usuario con ID 5
     *
     * Esta consulta utiliza Eloquent ORM con el método de agregación count()
     * para contar cuántos pedidos tiene un usuario específico. El método count()
     * es una función de agregación que retorna un número entero en lugar de
     * una colección de registros.
     *
     * La consulta se compone de dos partes:
     * 1. where('id_usuario', 5) - Filtra solo los pedidos del usuario con ID 5
     * 2. count() - Cuenta cuántos registros cumplen la condición
     *
     * Si el usuario no tiene pedidos, retornará 0 (cero).
     *
     * Las funciones de agregación como count(), sum(), avg(), min(), max()
     * son muy útiles para obtener estadísticas y métricas sin necesidad de
     * recuperar todos los registros y procesarlos en PHP.
     *
     * Alternativa con Query Builder: DB::table('pedidos')->where('id_usuario', 5)->count()
     * Alternativa con relaciones: Usuario::find(5)->pedidos()->count()
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function contarPedidosUsuario5()
    {
        // Consulta usando Eloquent ORM: cuenta los pedidos del usuario con ID 5
        // count() es una función de agregación que retorna un número entero
        $cantidad = Pedido::where('id_usuario', 5)->count();

        return response()->json([
            'usuario_id' => 5,
            'total_pedidos' => $cantidad
        ]);
    }

    /**
     * Consulta 6: Recupera pedidos ordenados por total en orden descendente
     *
     * Esta consulta utiliza Eloquent ORM con eager loading para obtener todos
     * los pedidos junto con la información de sus usuarios asociados, ordenados
     * por el campo 'total' de mayor a menor (descendente).
     *
     * La consulta se compone de dos partes principales:
     * 1. with('usuario') - Carga anticipada (eager loading) de la relación usuario
     *    para evitar el problema N+1. Esto significa que en lugar de hacer una
     *    consulta por cada pedido para obtener su usuario, se hace una sola
     *    consulta adicional para todos los usuarios relacionados.
     *
     * 2. orderBy('total', 'desc') - Ordena los resultados por el campo 'total'
     *    en orden descendente (de mayor a menor). 'desc' es la abreviatura de
     *    'descending'. Para orden ascendente se usaría 'asc'.
     *
     * Esta técnica es muy útil para generar reportes de ventas, rankings,
     * o cualquier listado que requiera mostrar información ordenada por
     * valores numéricos o fechas.
     *
     * El resultado incluirá todos los campos de los pedidos más un objeto
     * 'usuario' anidado con la información completa del usuario asociado.
     *
     * Alternativa con Query Builder:
     * DB::table('pedidos')
     *   ->join('usuarios', 'pedidos.id_usuario', '=', 'usuarios.id')
     *   ->select('pedidos.*', 'usuarios.nombre', 'usuarios.correo')
     *   ->orderBy('pedidos.total', 'desc')
     *   ->get()
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function pedidosOrdenadosDesc()
    {
        // Consulta usando Eloquent ORM con eager loading y ordenamiento
        // with('usuario') carga la relación para evitar consultas N+1
        // orderBy('total', 'desc') ordena de mayor a menor por el campo total
        $pedidos = Pedido::with('usuario')
            ->orderBy('total', 'desc')
            ->get();

        return response()->json($pedidos);
    }

    /**
     * Consulta 7: Calcula la suma total de todos los pedidos
     *
     * Esta consulta utiliza Eloquent ORM con el método de agregación sum()
     * para calcular el valor total de todos los pedidos en el sistema.
     * El método sum() es una función de agregación que suma todos los valores
     * de un campo específico y retorna un número (decimal o entero).
     *
     * La función sum('total') realiza la siguiente operación SQL:
     * SELECT SUM(total) FROM pedidos
     *
     * Esta consulta es muy útil para obtener métricas financieras como:
     * - Total de ventas del sistema
     * - Ingresos totales
     * - Valor total del inventario
     * - Suma de cualquier campo numérico
     *
     * Si no existen pedidos en la tabla, retornará 0 (cero).
     *
     * Otras funciones de agregación disponibles:
     * - count() - Cuenta registros
     * - avg() - Calcula el promedio
     * - min() - Encuentra el valor mínimo
     * - max() - Encuentra el valor máximo
     *
     * Alternativa con Query Builder: DB::table('pedidos')->sum('total')
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sumaTotalPedidos()
    {
        // Consulta usando Eloquent ORM: suma todos los valores del campo 'total'
        // sum() es una función de agregación que retorna un número decimal
        $sumaTotal = Pedido::sum('total');

        return response()->json([
            'suma_total' => $sumaTotal,
            'mensaje' => 'Suma total de todos los pedidos en el sistema'
        ]);
    }

    /**
     * Consulta 8: Encuentra el pedido más económico con información del usuario
     *
     * Esta consulta utiliza Eloquent ORM para encontrar el pedido con el valor
     * total más bajo (mínimo) en el sistema, incluyendo la información del
     * usuario que realizó ese pedido.
     *
     * La consulta se compone de tres partes principales:
     * 1. with('usuario') - Carga anticipada (eager loading) de la relación usuario
     *    para obtener los datos del usuario asociado al pedido. Esto evita el
     *    problema N+1 y nos permite acceder a información como el nombre del usuario.
     *
     * 2. orderBy('total', 'asc') - Ordena todos los pedidos por el campo 'total'
     *    en orden ascendente (de menor a mayor). 'asc' es la abreviatura de
     *    'ascending', lo que coloca el pedido más económico en la primera posición.
     *
     * 3. first() - Retorna únicamente el primer registro del resultado ordenado,
     *    que en este caso será el pedido con el total más bajo. Si no existen
     *    pedidos en la tabla, retornará null.
     *
     * Esta técnica es muy útil para encontrar valores extremos (mínimos o máximos)
     * en una tabla. Para encontrar el pedido más caro, simplemente cambiaríamos
     * 'asc' por 'desc' en el orderBy.
     *
     * El resultado incluirá todos los campos del pedido más económico más un
     * objeto 'usuario' anidado con la información completa del usuario asociado.
     *
     * Alternativa con Query Builder:
     * DB::table('pedidos')
     *   ->join('usuarios', 'pedidos.id_usuario', '=', 'usuarios.id')
     *   ->select('pedidos.*', 'usuarios.nombre')
     *   ->orderBy('pedidos.total', 'asc')
     *   ->first()
     *
     * Alternativa usando min(): Pedido::where('total', Pedido::min('total'))->with('usuario')->first()
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function pedidoMasEconomico()
    {
        // Consulta usando Eloquent ORM: encuentra el pedido con el total más bajo
        // with('usuario') carga la relación para incluir datos del usuario
        // orderBy('total', 'asc') ordena de menor a mayor por el campo total
        // first() retorna solo el primer registro (el más económico)
        $pedido = Pedido::with('usuario')
            ->orderBy('total', 'asc')
            ->first();

        return response()->json($pedido);
    }

    /**
     * Consulta 9: Agrupa pedidos por usuario mostrando todos sus pedidos
     *
     * Esta consulta utiliza Query Builder con JOIN para combinar las tablas
     * pedidos y usuarios, y luego agrupa los resultados por usuario utilizando
     * el método groupBy() de las colecciones de Laravel.
     *
     * La consulta se compone de varias partes:
     * 1. DB::table('pedidos') - Inicia la consulta desde la tabla pedidos
     *
     * 2. join('usuarios', ...) - Realiza un INNER JOIN con la tabla usuarios
     *    mediante la clave foránea id_usuario. Esto combina los datos de ambas
     *    tablas en un solo resultado.
     *
     * 3. select(...) - Especifica los campos que queremos retornar:
     *    - usuarios.nombre: nombre del usuario
     *    - pedidos.producto: nombre del producto del pedido
     *    - pedidos.cantidad: cantidad de productos
     *    - pedidos.total: total del pedido
     *
     * 4. orderBy('usuarios.nombre') - Ordena los resultados alfabéticamente por
     *    nombre de usuario para facilitar la visualización agrupada.
     *
     * 5. get() - Ejecuta la consulta y obtiene todos los resultados
     *
     * 6. groupBy('nombre') - Agrupa la colección de resultados por el campo 'nombre'
     *    del usuario. Esto crea un array asociativo donde cada clave es el nombre
     *    de un usuario y el valor es una colección con todos sus pedidos.
     *
     * El resultado final es un objeto JSON donde cada usuario tiene un array con
     * todos sus pedidos, mostrando el producto, cantidad y total de cada uno.
     *
     * Esta técnica es muy útil para generar reportes agrupados, resúmenes por
     * categoría, o cualquier visualización que requiera organizar datos relacionados.
     *
     * Ejemplo de estructura del resultado:
     * {
     *   "Roberto García": [
     *     {"nombre": "Roberto García", "producto": "Laptop", "cantidad": 1, "total": 250.00},
     *     {"nombre": "Roberto García", "producto": "Mouse", "cantidad": 2, "total": 50.00}
     *   ],
     *   "María López": [
     *     {"nombre": "María López", "producto": "Teclado", "cantidad": 1, "total": 150.00}
     *   ]
     * }
     *
     * Alternativa con Eloquent:
     * Usuario::with('pedidos:id,producto,cantidad,total,id_usuario')->get()
     * Esta alternativa retornaría una estructura diferente donde cada usuario
     * es un objeto con sus pedidos anidados.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function pedidosAgrupadosPorUsuario()
    {
        // Consulta usando Query Builder con JOIN para combinar pedidos y usuarios
        // join() combina ambas tablas mediante la relación id_usuario
        // select() especifica los campos: nombre usuario, producto, cantidad, total
        // orderBy() ordena alfabéticamente por nombre de usuario
        // groupBy() agrupa la colección resultante por nombre de usuario
        $pedidosAgrupados = DB::table('pedidos')
            ->join('usuarios', 'pedidos.id_usuario', '=', 'usuarios.id')
            ->select(
                'usuarios.nombre',
                'pedidos.producto',
                'pedidos.cantidad',
                'pedidos.total'
            )
            ->orderBy('usuarios.nombre')
            ->get()
            ->groupBy('nombre');

        return response()->json($pedidosAgrupados);
    }
}
