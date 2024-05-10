<?php
$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '/api/person':
    case '/api/person/':
        require __DIR__ . '/api/person.php';
        break;
    default:
        http_response_code(404);
        echo "Not Found";
        break;
}
?>
