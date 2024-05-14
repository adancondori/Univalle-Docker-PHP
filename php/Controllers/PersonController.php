<?php
require_once './Config/Database.php';
require_once './Model/Person.php';
class PersonController {
    public function index() {
        $db = Database::getInstance()->getConnection();     
        $person = new Person($db);
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

    public function create() {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        
        if (! $this->validatePerson($input)) {
            
            return array('status_code_header' => 'HTTP/1.1 401 Created', 'body' => json_encode(array("message" => "Error Person created")));
        }
        var_dump($input);
        $db = Database::getInstance()->getConnection();     
        $person = new Person($db);
        $person->firstname = $input['firstname'];
        $person->lastname = $input['lastname'];
        $person->email = $input['email'];

        if ($person->create()) {
            return array('status_code_header' => 'HTTP/1.1 201 Created', 'body' => json_encode(array("message" => "Person created")));
        } else {
            return array('status_code_header' => 'HTTP/1.1 422 Unprocessable Entity', 'body' => json_encode(array("message" => "Failed to create person")));
        }
    }
    private function validatePerson($input) {
        return isset($input['firstname']) && isset($input['lastname']) && isset($input['email']);
    }
}
