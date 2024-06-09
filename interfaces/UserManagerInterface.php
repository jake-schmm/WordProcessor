<?php
interface UserManagerInterface {
    // Returns a ManagerResponse with (status (error or success), message)
    public function registerUser(string $username, string $password, string $password_confirm) : ManagerResponse;

    public function authenticateUser(string $username, string $password) : ManagerResponse;

    public function getFriendsList(string $username): ManagerResponse;

    public function sendFriendRequest(string $senderUsername, string $receiverUsername): ManagerResponse;

    public function acceptFriendRequest(string $senderUsername, string $receiverUsername): ManagerResponse;

    public function rejectFriendRequest(string $senderUsername, string $receiverUsername): ManagerResponse;

    public function getIncomingPendingFriendRequests(string $username): ManagerResponse;

}