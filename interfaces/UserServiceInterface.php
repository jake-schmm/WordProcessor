<?php
interface UserServiceInterface {
    public function registerUser(string $username, string $password): bool;

    public function authenticateUser(string $username, string $password): int | null;

    public function userExistsByUsername(string $username): bool;

}