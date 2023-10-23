<?php
interface UserManagerInterface {
    // Returns an array of [status (error or success), message] 
    public function registerUser(string $username, string $password, string $password_confirm) : array;

    public function authenticateUser(string $username, string $password) : ?int;

    

}