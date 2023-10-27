<?php
require_once('../interfaces/UserServiceInterface.php');
require_once('../interfaces/DatabaseServiceInterface.php');

class UserService implements UserServiceInterface {
    private DatabaseServiceInterface $databaseService;
    public function __construct(DatabaseServiceInterface $databaseService) {
        $this->databaseService = $databaseService;
    }
    public function registerUser(string $username, string $password): bool {
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $inserted = $this->databaseService->executeQuery($sql, [$username, $password], "ss", "insert");
        return $inserted;
    }

    // Returns the user id of the authenticated user if successful, null if user is not found
    public function authenticateUser(string $username, string $password): int | null {
        $sql = "SELECT * FROM users WHERE BINARY username=? AND BINARY password=?";
        $result = $this->databaseService->executeQuery($sql, [$username, $password], "ss", "select");
        if($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            return $row["user_id"];
        }
        else {
            return null; // no user found 
        }
    }

    public function userExistsByUsername(string $username): bool {
        $sql = "SELECT * FROM users WHERE username=?";
        $result = $this->databaseService->executeQuery($sql, [$username], "s", "select");
        if($result && $result->num_rows > 0) {
            return true;
        }
        return false;
    }

}