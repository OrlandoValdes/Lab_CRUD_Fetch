<?php

// Importa la conexión a la base de datos (PDO)
require_once "conexion.php";

// Clase Producto que extiende la conexión DB
class Producto extends DB
{
    // Propiedades que representan los campos de la tabla productos
    public $id;
    public $codigo;
    public $producto;
    public $precio;
    public $cantidad;
    public $descripcion;

    // =========================================
    // INSERTAR NUEVO PRODUCTO
    // =========================================
    public function guardar()
    {
        // Sentencia SQL para insertar datos
        $sql = "INSERT INTO productos
            (codigo, producto, descripcion, precio, cantidad)
            VALUES (?, ?, ?, ?, ?)";

        // Prepara la consulta
        $stmt = $this->conexion->prepare($sql);

        // Ejecuta la consulta con los valores del objeto
        return $stmt->execute([
            $this->codigo,
            $this->producto,
            $this->descripcion,
            $this->precio,
            $this->cantidad
        ]);
    }

    // =========================================
    // ACTUALIZAR PRODUCTO EXISTENTE
    // =========================================
    public function editar()
    {
        // SQL de actualización por ID
        $sql = "UPDATE productos
                SET codigo=?,
                    producto=?,
                    descripcion=?,
                    precio=?,
                    cantidad=?
                WHERE id=?";

        // Prepara la consulta
        $stmt = $this->conexion->prepare($sql);

        // Ejecuta con los valores del objeto
        return $stmt->execute([
            $this->codigo,
            $this->producto,
            $this->descripcion,
            $this->precio,
            $this->cantidad,
            $this->id
        ]);
    }

    // =========================================
    // BUSCAR PRODUCTO POR ID
    // =========================================
    public function buscar($id)
    {
        // Consulta SQL con parámetro
        $sql = "SELECT * FROM productos WHERE id=?";

        // Prepara la consulta
        $stmt = $this->conexion->prepare($sql);

        // Ejecuta con el ID recibido
        $stmt->execute([$id]);

        // Devuelve un solo registro como arreglo asociativo
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // =========================================
    // LISTAR TODOS LOS PRODUCTOS
    // =========================================
    public function listar()
    {
        // Consulta para traer todos los productos ordenados por ID descendente
        $sql = "SELECT * FROM productos ORDER BY id DESC";

        // Ejecuta directamente la consulta
        $stmt = $this->conexion->query($sql);

        // Devuelve todos los registros
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================================
    // ELIMINAR PRODUCTO POR ID
    // =========================================
    public function eliminar($id)
    {
        // SQL de eliminación
        $sql = "DELETE FROM productos WHERE id=?";

        // Prepara la consulta
        $stmt = $this->conexion->prepare($sql);

        // Ejecuta con el ID
        return $stmt->execute([$id]);
    }

    // =========================================
    // PAGINACIÓN DE PRODUCTOS
    // =========================================
    public function paginar($inicio, $limite)
    {
        // SQL con LIMIT para paginación
        $sql = "SELECT * FROM productos
                ORDER BY id DESC
                LIMIT ?, ?";

        // Prepara la consulta
        $stmt = $this->conexion->prepare($sql);

        // Bind de parámetros como enteros (IMPORTANTE en PDO)
        $stmt->bindValue(1, (int)$inicio, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$limite, PDO::PARAM_INT);

        // Ejecuta la consulta
        $stmt->execute();

        // Devuelve resultados
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================================
    // CONTAR TOTAL DE REGISTROS
    // =========================================
    public function totalRegistros()
    {
        // Consulta para contar productos
        $sql = "SELECT COUNT(*) total FROM productos";

        // Ejecuta y obtiene el valor del total
        return $this->conexion
                    ->query($sql)
                    ->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // =========================================
    // BÚSQUEDA POR TEXTO (CODIGO O PRODUCTO)
    // =========================================
    public function buscarTexto($texto)
    {
        // SQL con LIKE para búsqueda parcial
        $sql = "SELECT * FROM productos 
                WHERE producto LIKE :texto 
                OR codigo LIKE :texto";

        // Prepara la consulta
        $stmt = $this->conexion->prepare($sql);

        // Agrega comodines para búsqueda parcial
        $param = "%$texto%";

        // Asigna el valor al parámetro
        $stmt->bindValue(':texto', $param, PDO::PARAM_STR);

        // Ejecuta la consulta
        $stmt->execute();

        // Devuelve todos los resultados encontrados
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}