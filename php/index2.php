<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once './Config/Database.php';
include_once './Model/Person.php';

$database = new Database();
$db = $database->getConnection();
$person = new Person($db);

$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$basePath = '/api/';

#$requestMethod = $_SERVER["REQUEST_METHOD"];

// Manejar el tipo de solicitud
switch ($requestMethod) {
    case 'GET':
        if (!empty($_GET['id'])) {
            $person->id = $_GET['id'];
            $data = $person->readOne();
            if ($data) {
                http_response_code(200);
                echo json_encode($data);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "No item found."));
            }
        } else {
            $stmt = $person->read();
            $count = $stmt->rowCount();

            if ($count > 0) {
                $people_arr = array();
                $people_arr["records"] = array();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $person_item = array(
                        "id" => $id,
                        "firstname" => $firstname,
                        "lastname" => $lastname,
                        "email" => $email
                    );

                    array_push($people_arr["records"], $person_item);
                }

                http_response_code(200);
                echo json_encode($people_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "No people found."));
            }
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        
        if (!empty($data->firstname) && !empty($data->lastname) && !empty($data->email)) {
            $person->firstname = $data->firstname;
            $person->lastname = $data->lastname;
            $person->email = $data->email;

            if ($person->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Person was created."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to create person."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Unable to create person. Data is incomplete."));
        }
        break;
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        
        if (!empty($data->id)) {
            $person->id = $data->id;
            $person->firstname = $data->firstname;
            $person->lastname = $data->lastname;
            $person->email = $data->email;

            if ($person->update()) {
                http_response_code(200);
                echo json_encode(array("message" => "Person was updated."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to update person."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Unable to update person. Data is incomplete."));
        }
        break;
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->id)) {
            $person->id = $data->id;

            if ($person->delete()) {
                http_response_code(200);
                echo json_encode(array("message" => "Person was deleted."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to delete person."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Unable to delete person. Data is incomplete."));
        }
        break;
    default:
        // Manejar método no permitido
        http_response_code(405);
        echo json_encode(array("message" => "Method not allowed."));
        break;
}

?>
