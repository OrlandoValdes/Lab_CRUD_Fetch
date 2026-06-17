<?php

// Indica que toda la respuesta será en formato JSON
header('Content-Type: application/json');

// Importa el modelo de productos (lógica de base de datos)
require_once "Modelo/Productos.php";

// Obtiene la acción enviada desde el frontend (POST)
$accion = $_POST['accion'] ?? '';

// Estructura base de respuesta estándar
$response = [
    "success" => false,
    "message" => "",
    "errors" => []
];

// =========================================
// CONTROLADOR PRINCIPAL (ROUTER SIMPLE)
// =========================================
switch ($accion)
{

    // =========================================
    // GUARDAR NUEVO PRODUCTO
    // =========================================
    case "Guardar":

        $errores = [];

        // Validaciones básicas de campos obligatorios
        if(empty($_POST['codigo']))
            $errores[] = "Debe ingresar el código";

        if(empty($_POST['producto']))
            $errores[] = "Debe ingresar el producto";

        if(empty($_POST['precio']))
            $errores[] = "Debe ingresar el precio";

        if(empty($_POST['cantidad']))
            $errores[] = "Debe ingresar la cantidad";

        // Si hay errores, se devuelven y se detiene la ejecución
        if(count($errores) > 0)
        {
            $response["errors"] = $errores;
            $response["message"] = "Errores de validación";

            echo json_encode($response);
            exit;
        }

        // Crear instancia del modelo
        $p = new Producto();

        // Asignación de datos al objeto
        $p->codigo = $_POST['codigo'];
        $p->producto = $_POST['producto'];
        $p->descripcion = $_POST['descripcion'];
        $p->precio = $_POST['precio'];
        $p->cantidad = $_POST['cantidad'];

        // Intento de guardar en base de datos
        if($p->guardar())
        {
            $response["success"] = true;
            $response["message"] = "Producto registrado correctamente";
        }

        // Respuesta final
        echo json_encode($response);

    break;

    // =========================================
    // MODIFICAR PRODUCTO EXISTENTE
    // =========================================
    case "Modificar":

        $p = new Producto();

        // Asignación de datos recibidos
        $p->id = $_POST['id'];
        $p->codigo = $_POST['codigo'];
        $p->producto = $_POST['producto'];
        $p->descripcion = $_POST['descripcion'];
        $p->precio = $_POST['precio'];
        $p->cantidad = $_POST['cantidad'];

        // Actualización en base de datos
        if($p->editar())
        {
            $response["success"] = true;
            $response["message"] = "Producto actualizado correctamente";
        }

        echo json_encode($response);

    break;

    // =========================================
    // BUSCAR PRODUCTO POR ID
    // =========================================
    case "Buscar":

        $p = new Producto();

        // Busca un producto específico por ID
        $datos = $p->buscar($_POST['id']);

        // Si existe, lo devuelve
        if($datos)
        {
            echo json_encode([
                "success" => true,
                "data" => $datos
            ]);
        }
        else
        {
            // Si no existe, devuelve mensaje de error
            echo json_encode([
                "success" => false,
                "message" => "Producto no encontrado"
            ]);
        }

    break;

    // =========================================
    // LISTAR TODOS LOS PRODUCTOS
    // =========================================
    case "Listar":

        $p = new Producto();

        echo json_encode([
            "success" => true,
            "data" => $p->listar()
        ]);

    break;

    // =========================================
    // ELIMINAR PRODUCTO
    // =========================================
    case "Eliminar":

        $p = new Producto();

        // Elimina producto por ID
        if($p->eliminar($_POST['id']))
        {
            echo json_encode([
                "success" => true,
                "message" => "Producto eliminado correctamente"
            ]);
        }
        else
        {
            echo json_encode([
                "success" => false,
                "message" => "No se pudo eliminar"
            ]);
        }

    break;

    // =========================================
    // PAGINACIÓN DE PRODUCTOS
    // =========================================
    case "Paginar":

        // Página actual recibida desde frontend
        $pagina = $_POST['pagina'];

        // Cantidad de registros por página
        $limite = 5;

        // Cálculo del inicio para LIMIT
        $inicio = ($pagina - 1) * $limite;

        $p = new Producto();

        echo json_encode([
            "success" => true,
            "data" => $p->paginar($inicio, $limite),
            "total" => $p->totalRegistros()
        ]);

    break;

    // =========================================
    // BÚSQUEDA EN TIEMPO REAL (FILTRO)
    // =========================================
    case "BuscarTexto":

        try {

            $p = new Producto();

            // Texto ingresado en el buscador
            $texto = $_POST['buscar'] ?? '';

            // Si el campo está vacío, devuelve lista vacía
            if ($texto === "") {
                echo json_encode([
                    "success" => true,
                    "data" => []
                ]);
                exit;
            }

            // Llamada al modelo para buscar coincidencias
            $datos = $p->buscarTexto($texto);

            echo json_encode([
                "success" => true,
                "data" => $datos
            ]);

        } catch (Exception $e) {

            // Manejo de errores para evitar respuestas en HTML
            echo json_encode([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }

        exit;

    break;

    // =========================================
    // ACCIÓN NO VÁLIDA
    // =========================================
    default:

        echo json_encode([
            "success" => false,
            "message" => "Acción no válida"
        ]);
}