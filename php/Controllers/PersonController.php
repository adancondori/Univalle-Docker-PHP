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
        echo 'Storing a person';
    }
}
