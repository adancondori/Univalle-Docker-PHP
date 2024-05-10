<?php
// Conexión a la base de datos
$host = 'db';  // Nombre del servicio en Docker Compose
$db   = 'project_development';
$user = 'root';
$pass = 'password';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$method = $_SERVER['REQUEST_METHOD'];


switch ($method) {
    case 'GET':
        return {
            "name": "John",}
        break;
    case 'POST':
        if ($_GET['action'] == 'custom_data') {
        } else {
        }
        break;
    case 'PUT':
        // Código para PUT (Actualizar)
        break;
    case 'DELETE':
        // Código para DELETE (Eliminar)
        break;
    default:
        echo 'Método HTTP no soportado';
        break;
}

?>
