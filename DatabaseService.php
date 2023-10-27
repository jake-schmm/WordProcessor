<?php
require_once('DatabaseServiceInterface.php');
class DatabaseService implements DatabaseServiceInterface {

    private $mysqli;
    public function __construct($host, $username, $password, $database) {
        $this->mysqli = new mysqli();
        $connected = $this->mysqli->real_connect($host, $username, $password, $database, NULL, NULL, MYSQLI_CLIENT_FOUND_ROWS);
       
         if (!$connected) {
            die("Connection failed: " . $this->mysqli->connect_error);
        }
    }
    public function executeQuery(string $sql, array $params = [], string $paramTypes= "", string $queryType): mysqli_result | bool {
        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) {
            throw new \RuntimeException("Failed to prepare query: " . $this->mysqli->error);
        }
        // Reference: https://stackoverflow.com/questions/1913899/mysqli-binding-params-using-call-user-func-array
        // Create array with data types and actual param values - Example: ['ii', 2, 3]
        if(!empty($params)) {
            $bindParams = [$paramTypes];
            foreach ($params as &$param) {
                $bindParams[] = &$param;  // Pass each argument by reference 
            }
            $bindSuccess = call_user_func_array(array($stmt, 'bind_param'), $bindParams); // call bind_param on $stmt with $bindParams as an argument 
            if (!$bindSuccess) {
                throw new \RuntimeException("Failed to bind parameters: " . $stmt->error);
            }
        }
        
        if (!$stmt->execute()) {
            throw new \RuntimeException("Failed to execute query: " . $stmt->error);
        }

        if($queryType === "select") {
            $result = $stmt->get_result();
        }
        else {
            $result = ($stmt->affected_rows > 0);
        }
        $stmt->close();
        return $result;
    }

    public function closeConnection(): void {
        if ($this->mysqli) {
            $this->mysqli->close();
        }
    }

    public function getMySqli(): mysqli {
        return $this->mysqli;
    }
}