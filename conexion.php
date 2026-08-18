<?php
class Conexion
{
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $dbname = 'sistema_grafico';
    public $conexion;

    public function __construct()
    {
        // Creamos la conexión usando la clase mysqli
        $this->conexion = new mysqli($this->host, $this->username, $this->password, $this->dbname);

        // Verificar si hubo un error de conexión
        if ($this->conexion->connect_error) {
            die("Error en la conexión a la base de datos: " . $this->conexion->connect_error);
        }

        // Establecer el charset a utf8mb4
        $this->conexion->set_charset("utf8mb4");
    }
}
