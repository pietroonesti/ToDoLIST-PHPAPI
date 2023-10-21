<?php

/*
 * TodoList API corso Udemy
 * Pietro Onesti  * 
 * 2023  * 
 */

/**
 * Description of TaskGateway
 *
 * @author pietroonesti
 */
class TaskGateway {
    
    private PDO $conn;

    public function __construct(Database $database) {
        
        $this->conn = $database->getConnection();
        
    }
    
//legge tutti i record, viene chiamato da TaskController ->method GET
    public function getAll():array {
        
        $sql = " SELECT * FROM task ORDER BY name";
        
        $stmt = $this->conn->query($sql);
        
//        strada normale        
//        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
//        strada per trasformare il valore numerico del campo booleano in true/false
        
        $data = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC) ){
            
            $row['is_completed'] = (bool) $row['is_completed'];
            
            $data[] = $row;
        }
        
        return $data;
    }
    
//estrae un solo record indicato nella url con task/n
    
    public function get(string $id): array | false {
        
        $sql = "SELECT *
                FROM task
                WHERE id = :id";
       
        $stmt = $this->conn->prepare($sql);
       
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
       
        $stmt->execute();
       
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($data !== false){
            
            $data['is_completed'] = (bool) $data['is_completed'];
        }
        return $data;
    }
    
    public function create (array $data): string  {
        
        $sql = "INSERT INTO task (name, priority, is_completed)"
                . "VALUES (:name, :priority, :is_completed)";
        
        $stmt = $this->conn->prepare($sql);
                
        $stmt->bindValue(":name",$data["name"], PDO::PARAM_STR);
        
        if (empty($data["priority"])) {
            $stmt->bindValue(":priority", null, PDO::PARAM_NULL);
            
        } else {
            
            $stmt->bindValue(":priority", $data["priority"], PDO::PARAM_INT);
        }
        
        $stmt->bindValue(":is_completed", $data["is_completed"] ?? false, PDO::PARAM_BOOL);
        
        $stmt->execute();
        
        return $this->conn->lastInsertId();
    }
}
