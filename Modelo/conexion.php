<?php

// Clase base de conexión a la base de datos usando PDO
class DB {

    // =========================================
    // CONFIGURACIÓN DE LA BASE DE DATOS
    // =========================================

    // Dirección del servidor de base de datos
    private $host = "localhost";

    // Nombre de la base de datos
    private $dbname = "productosdb";

    // Usuario de la base de datos
    private $user = "root";

    // Contraseña del usuario de la base de datos
    private $pass = "";

    // Objeto de conexión PDO (protegido para uso en clases hijas)
    protected $conexion;

    // =========================================
    // CONSTRUCTOR: ESTABLECE LA CONEXIÓN
    // =========================================
    public function __construct()
    {
        try {
            // Crear nueva instancia de PDO con los datos de conexión
            $this->conexion = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname;charset=utf8",
                $this->user,
                $this->pass
            );

            // Configurar el modo de error para que lance excepciones
            $this->conexion->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

        } catch(PDOException $e){

            // Si ocurre un error en la conexión, se detiene la ejecución
            // y se muestra el mensaje del error
            die($e->getMessage());
        }
    }
}