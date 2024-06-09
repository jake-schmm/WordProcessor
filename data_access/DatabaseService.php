<?php
require_once('../interfaces/DatabaseServiceInterface.php');
class DatabaseService implements DatabaseServiceInterface {

    private $mysqli;
    public function __construct($host, $username, $password, $database) {
        $this->mysqli = new mysqli();

        // Connect with MYSQLI_CLIENT_FOUND_ROWS flag on, which changes update queries' affected_rows to return 
        //  a positive value if there were any matched rows, not only when rows were actually updated.
        //  Without this flag, update queries would not result in positive affected_rows if new value matches existing value.
        //  This flag will be needed when saving a file twice with the same contents each time - don't return false
        //  for executeQuery inside updateDocumentContents which would result in an error message being displayed.
        //  Only return false when the WHERE clause isn't found.
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

    public function getMySqli(): mysqli {
        return $this->mysqli;
    }
}