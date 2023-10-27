<?php
require_once('UserServiceInterface.php');
require_once('UserManagerInterface.php');
require_once('Exceptions.php');
require_once('ManagerResponse.php');
class UserManager implements UserManagerInterface {
    private UserServiceInterface $userService;
    public function __construct(UserServiceInterface $userService) {
        $this->userService = $userService;
    }

    public function registerUser(string $username, string $password, string $password_confirm) : ManagerResponse {
        if(empty($username)) {
            return new ManagerResponse("error", "Username required.");
        }
        if (empty($password)) {
            return new ManagerResponse("error", "Password required.");
        }
        if ($password != $password_confirm) {
            return new ManagerResponse("error", "Passwords must match.");
        }
        if($this->userService->userExistsByUsername($username)) {
            throw new UserAlreadyExistsException("Username already taken.");
        }
        else {
            try {
                if($this->userService->registerUser($username, $password)) {
                    return new ManagerResponse("success", "User was successfully registered.");
                }
                else {
                    return new ManagerResponse("error", "There was a problem registering the user.");
                }
            } catch(RuntimeException $e) {
                return new ManagerResponse("error", "A database error occurred: " . $e->getMessage());
            }
        }

    }

    // Returns id of authenticated user in message if successful, error message if user not found
    public function authenticateUser(string $username, string $password) : ManagerResponse {
        try {
            $result = $this->userService->authenticateUser($username, $password); 
            if($result !== null) {
                return new ManagerResponse("success", $result);
            }
            else {
                return new ManagerResponse("error", "Invalid username or password");
            }
        } catch(RuntimeException $e) {
            return new ManagerResponse("error", "A database error occurred: " + $e->getMessage());
        }
        
    }
}