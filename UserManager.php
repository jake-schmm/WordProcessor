<?php
require_once('UserServiceInterface.php');
require_once('UserManagerInterface.php');
require_once('Exceptions.php');
class UserManager implements UserManagerInterface {
    private UserServiceInterface $userService;
    public function __construct(UserServiceInterface $userService) {
        $this->userService = $userService;
    }

    public function registerUser(string $username, string $password, string $password_confirm) : array {
        if(empty($username)) {
            return ["status" => "error", "message" => "Username required."];
        }
        if (empty($password)) {
            return ["status" => "error", "message" => "Password required."];
        }
        if ($password != $password_confirm) {
            return ["status" => "error", "message" => "Passwords must match."];
        }
        if($this->userService->userExistsByUsername($username)) {
            throw new UserAlreadyExistsException("Username already taken.");
        }
        else {
            if($this->userService->registerUser($username, $password)) {
                return ["status" => "success", "message" => "User was successfully registered."];
            }
            else {
                return ["status" => "error", "message" => "There was a problem registering the user."];
            }
        }

    }

    // Returns id of authenticated user if successful, null if user is not found
    public function authenticateUser(string $username, string $password) : ?int {
        return $this->userService->authenticateUser($username, $password); 
    }
}