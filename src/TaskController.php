<?php

/*
 * TodoList API corso Udemy
 * Pietro Onesti  * 
 * 2023  * 
 */

/**
 * Description of TaskController
 * questa classe controlla il contenuto della variabile $_SERVER["REQUEST_METHOD"]
 * e decide cosa fare 
 * 
 * @author pietroonesti
 */
class TaskController {
   
    //richiamo il TaskGateway
    public function __construct(private TaskGateway $gateway) {
        
    }
    
    public function processRequest(string $method, ?string $id): void {
//    public function processRequest(string $method, string $id): void {
        
        if ($id == null) {
            
            if ($method == 'GET') {
            
//                echo "index";
                echo json_encode($this->gateway->getAll());
                
                
            }
            elseif ($method == 'POST'){
//                echo "CREATE";
//                print_r($_POST);
                
                $data = (array) json_decode(file_get_contents("php://input"), true);
//                var_dump($data);
                $id = $this->gateway->create($data);
                $this->respondCreated($id);
            }
            
            else {
//                http_response_code(405);
//                header("Allow: GET - POST");
//                echo 'METHOD ERROR';
                $this->respondMethodNotAllowed("GET, POST");
            }
            
        } else {
            
            $task = $this->gateway->get($id);
            
                if ($task === false) {
                    
                    $this->respondNotFound($id);
                    
                    return;
                }
            
            switch ($method) {
                
                case "GET":
//                    echo "show $id";
//                echo json_encode($this->gateway->get($id));
                    echo json_encode($task);
                    break;
                
                case "PATCH";
                    echo "update $id";
                    break;
                
                case "DELETE":
                    echo "delete $id";
                    break;
                
//                case "POST":
//                    echo "post $id";
//                    break;
                
                default : $this->respondMethodNotAllowed("Get,PATCH, DELETE");
            }
        }
    }
    private function respondMethodNotAllowed($allowedmethods): void 
        {
            http_response_code(405);
                header("Allow: $allowedmethods");
                echo 'METHOD ERROR';
    }
    
    private function respondNotFound(string $id): void {
        
        http_response_code(404);
        echo json_encode(["message" => "Task with ID $id not found"]);
    }
    
    private function respondCreated(string $id): void {
        
        http_response_code(201);
        echo json_encode(["message" => "task created", "id: " => $id]);
    }
}
