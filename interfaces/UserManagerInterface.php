<?php
interface UserManagerInterface {
    // Returns a ManagerResponse with (status (error or success), message)
    public function registerUser(string $username, string $password, string $password_confirm) : ManagerResponse;

    public function authenticateUser(string $username, string $password) : ManagerResponse;

    

}