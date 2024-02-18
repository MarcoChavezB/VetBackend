<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MostrarProductosController extends Controller
{

    public function update(Request $request){
        $data = $request->all();
        $nombre = $data['nombre'];
        $descripcion = $data['descripcion'] ?? null;
        $categoria = $data['categoria'] ?? null;
        $precioCompra = $data['precio_compra'] ?? null;
        $precioVenta = $data['precio_venta'] ?? null;
        $tipoProducto = $data['tipo_producto'] ?? null;

        $idCategoria = Categoria::where('categoria', $categoria)->first();

        if(!$idCategoria){
            return response()->json([
                'message' => 'La categoría no existe'
            ], 404);
        }

        $producto = Producto::where('nom_producto', $nombre)->first();

        $producto->descripcion = $descripcion ?? $producto->descripcion;
        $producto->id_categoria = $idCategoria->id ?? $producto->id_categoria;
        $producto->precio_compra = $precioCompra ?? $producto->precio_compra;
        $producto->precio_venta = $precioVenta ?? $producto->precio_venta;
        $producto->tipo_producto = $tipoProducto ?? $producto->tipo_producto;
        $producto->save();

        return response()->json([
            'message' => 'Producto actualizado correctamente'
        ]);
    }

    public function updateOne(Request $request){
        $data = $request->all();
        $nombre = $data['nombre_producto'];
        $cantidad = $data['cantidad_producto'];

        $producto = Producto::where('nom_producto', $nombre)->first();

        if(!$producto){
            return response()->json([
                'message' => 'El producto no existe'
            ], 404);
        }

        $producto->existencias = $producto->existencias + $cantidad;
        $producto->save();

        return response()->json([
            'message' => 'Producto actualizado correctamente'
        ]);
    }

    public function getProductoById($id){
        $producto = Producto::find($id);
        $categoria = Categoria::find($producto->id_categoria);

        return response()->json([
            'producto' => $producto,
            'categoria' => $categoria
        ]);
    }

    public function store(Request $request){
        $data = $request->all();

        $validator = Validator::make($data, [
            'nom_producto' => 'required | min:3 | max:50 | unique:productos,nom_producto',
            'descripcion' => 'required | min:3 | max:255',
            'precio_compra' => 'required | numeric | min:1',
            'tipo_producto' => 'required | in:venta,interno',
            'existencias' => 'required | numeric | min:1',
            'precio_venta' => 'required | numeric | min:1',
            'categoria_producto' => 'required',
        ], [
            'nom_producto.required' => 'El nombre del producto es requerido',
            'nom_producto.min' => 'El nombre del producto debe tener al menos 3 caracteres',
            'nom_producto.max' => 'El nombre del producto debe tener máximo 50 caracteres',
            'nom_producto.unique' => 'El nombre del producto ya existe',
            'descripcion.required' => 'La descripción del producto es requerida',
            'descripcion.min' => 'La descripción del producto debe tener al menos 3 caracteres',
            'descripcion.max' => 'La descripción del producto debe tener máximo 255 caracteres',
            'precio_compra.required' => 'El precio de compra es requerido',
            'precio_compra.numeric' => 'El precio de compra debe ser numérico',
            'precio_compra.min' => 'El precio de compra debe ser mayor a 0',
            'tipo_producto.required' => 'El tipo de producto es requerido',
            'tipo_producto.in' => 'El tipo de producto debe ser venta o interno',
            'existencias.required' => 'Las existencias del producto son requeridas',
            'existencias.numeric' => 'Las existencias del producto deben ser numéricas',
            'existencias.min' => 'Las existencias del producto deben ser mayor a 0',
            'precio_venta.required' => 'El precio de venta es requerido',
            'precio_venta.numeric' => 'El precio de venta debe ser numérico',
            'precio_venta.min' => 'El precio de venta debe ser mayor a 0',
            'categoria_producto.required' => 'La categoría del producto es requerida'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    'errors' => $validator->errors()
                ]
            ], 422);
        }

        // obten el id de la categoria por nombre 
        $categoria = Categoria::where('categoria', $data['categoria_producto'])->first();

        if(!$categoria){
            return response()->json([
                'message' => 'La categoría no existe'
            ], 404);
        }


        $producto = new Producto();
        $producto->nom_producto = $data['nom_producto'];
        $producto->descripcion = $data['descripcion'];
        $producto->precio_compra = $data['precio_compra'];
        $producto->tipo_producto = $data['tipo_producto'];
        $producto->existencias = $data['existencias'];
        $producto->precio_venta = $data['precio_venta'];
        $producto->id_categoria = $categoria->id;
        $producto->save();

        return response()->json([
            'message' => 'Producto registrado correctamente'
        ]);
    }

    public function existencia($name){
        $producto = Producto::where('nom_producto', $name)->first();

        if(!$producto){
            return response()->json([
                'exist' => False
            ]);
        }

        return response()->json([
            'exist' => true
        ]);
    }


    public function mostrarPorductosVenta(){
        $productosVenta = Producto::where('tipo_producto', 'venta')->get();
        return response()->json([
            'productos' => $productosVenta
        ]);
    }

    public function getProductoByName($name){
        $producto = Producto::where('nom_producto', 'like', '%'.$name.'%')->where('tipo_producto', 'venta')->get(); 
        return response()->json([
            'producto' => $producto
        ]);
    }

    public function indexPublic(){
        $resultado = DB::table('productos')
        ->select('id', 'nom_producto', 'descripcion', 'tipo_producto', 'imagen')
        ->selectRaw('MAX(existencias) as existencias')
        ->selectRaw('MAX(precio_venta) as precio_venta')
        ->selectRaw('(MAX(precio_venta) * 0.16) as iva')
        ->selectRaw("CASE WHEN MAX(existencias) <= 0 THEN 'Sin stock' ELSE 'Stock' END as estado")
        ->where('tipo_producto', 'venta')
        ->groupBy('id', 'nom_producto', 'descripcion', 'tipo_producto', 'imagen')
        ->get();

        return response()->json([
            'productos' => $resultado
        ]);
    }

    public function indexInternos(){
        $resultado = DB::table('productos')
        ->select('nom_producto', 'descripcion', 'tipo_producto')
        ->selectRaw('MAX(existencias) as existencias')
        ->selectRaw('MAX(precio_venta) as precio_venta')
        ->selectRaw('(MAX(precio_venta) * 0.16) as iva')
        ->selectRaw("CASE WHEN MAX(existencias) <= 0 THEN 'Sin stock' ELSE 'Stock' END as estado")
        ->where('tipo_producto', 'interno')
        ->groupBy('nom_producto', 'descripcion', 'tipo_producto')
        ->get();

        return response()->json([
            'productos' => $resultado
        ]);
    }

    public function getProductoPublicoByName($name = null){
        if(empty($name)){
            $resultado = DB::table('productos')
            ->select('id', 'nom_producto', 'descripcion', 'tipo_producto', 'imagen')
            ->selectRaw('MAX(existencias) as existencias')
            ->selectRaw('MAX(precio_venta) as precio_venta')
            ->selectRaw('(MAX(precio_venta) * 0.16) as iva')
            ->selectRaw("CASE WHEN MAX(existencias) <= 0 THEN 'Sin stock' ELSE 'Stock' END as estado")
            ->where('tipo_producto', 'venta')
            ->groupBy('id', 'nom_producto', 'descripcion', 'tipo_producto', 'imagen')
            ->get();
    
            return response()->json([
                'productos' => $resultado
            ]);
        }

        $resultados = DB::select("CALL producto_venta_nombre(?)", [$name]);

        return response()->json([
            'productos' => $resultados
        ]);
    }

    public function getProductosRango(Request $request){
        $precioMin = $request->minPrice;
        $precioMax = $request->maxPrice;

        $resultados = DB::select("CALL obtener_productos_publicos_por_rango_precio(?, ?)", [$precioMin, $precioMax]);

        return response()->json([
            'productos' => $resultados
        ]);
    }

    public function getProductoInternoByName($name = null){
        if(empty($name)){
            $resultado = DB::table('productos')
            ->select('nom_producto', 'descripcion', 'tipo_producto')
            ->selectRaw('MAX(existencias) as existencias')
            ->selectRaw('MAX(precio_venta) as precio_venta')
            ->selectRaw('(MAX(precio_venta) * 0.16) as iva')
            ->selectRaw("CASE WHEN MAX(existencias) <= 0 THEN 'Sin stock' ELSE 'Stock' END as estado")
            ->where('tipo_producto', 'interno')
            ->groupBy('nom_producto', 'descripcion', 'tipo_producto')
            ->get();

            return response()->json([
                'productos' => $resultado
            ]);
        }

        $resultados = DB::select("CALL producto_interno_nombre(?)", [$name]);

        return response()->json([
            'productos' => $resultados
        ]);
    }

    public function getCategorias(){
        $categorias = Categoria::all();

        return response()->json([
            'categorias' => $categorias
        ]);
    }
}
