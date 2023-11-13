<?php
require_once('../interfaces/UserServiceInterface.php');
require_once('../interfaces/UserManagerInterface.php');
require_once('../Exceptions.php');
require_once('../models/ManagerResponse.php');
require_once('../interfaces/FriendshipServiceInterface.php');
class UserManager implements UserManagerInterface {
    private UserServiceInterface $userService;
    private FriendshipServiceInterface $friendshipService;
    public function __construct(UserServiceInterface $userService, FriendshipServiceInterface $friendshipService) {
        $this->userService = $userService;
        $this->friendshipService = $friendshipService;
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
        // Check if there's a case-sensitive match for the username already in the database
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
                $exceptionMessage = $e->getMessage();
                // If exception message contains "Duplicate entry ... for key 'username'", meaning a case-insensitive match was found
                if(strpos($exceptionMessage, "Duplicate entry") !== false && strpos($exceptionMessage, "for key 'username'") !== false) {
                    return new ManagerResponse("error", "A case-insensitive match for that username '" . $username . "' already exists.");
                }
                else {
                    return new ManagerResponse("error", "A database error occurred: " . $exceptionMessage);
                }
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
            return new ManagerResponse("error", "A database error occurred: " . $e->getMessage());
        }
        
    }

    // On success: returns an array of strings in message of ManagerResponse (usernames that exist in the user's friends list)
    // Throws user-non-existent exception
    public function getFriendsList(string $username): ManagerResponse {
        try {
            if(!$this->userService->userExistsByUsername($username)) {
                throw new UserNonExistentException("User whose friends list you are trying to fetch doesn't exist.");
            }
            $result = $this->friendshipService->getFriendsList($username);
            return new ManagerResponse("success", $result);
        } catch(RuntimeException $e) {
            return new ManagerResponse("error", "A database error occurred: " . $e->getMessage());
        }
    }

    // Throws user-non-existent exception
    public function sendFriendRequest(string $senderUsername, string $receiverUsername): ManagerResponse {
        try {
            // First check if usernames exist 
            if(!$this->userService->userExistsByUsername($receiverUsername)) {
                throw new UserNonExistentException("Cannot send friend request; user with entered username doesn't exist.");
            }
            if(!$this->userService->userExistsByUsername($senderUsername)) {
                throw new UserNonExistentException("Cannot send friend request; the sending user's username was not found.");
            }
            
            // Validate to make sure you're not trying to send friend request to self
            if($senderUsername === $receiverUsername) {
                return new ManagerResponse("error", "Cannot send friend request to self.");
            }
            // Attempt sending the friend request
            $result = $this->friendshipService->sendFriendRequest($senderUsername, $receiverUsername);
            if($result) {
                return new ManagerResponse("success", "Friend request sent.");
            }
            else {
                return new ManagerResponse("error", "You are already friends with this user or have already sent a pending friend request to them.");
            }
        } catch(RuntimeException $e) {
            return new ManagerResponse("error", "A database error occurred: " . $e->getMessage());
        }
    }

    public function acceptFriendRequest(string $senderUsername, string $receiverUsername): ManagerResponse {
        try {
            // Accept friend request
            $result = $this->friendshipService->acceptFriendRequest($senderUsername, $receiverUsername);
            if($result) {
                return new ManagerResponse("success", "Friend request accepted.");
            }
            else {
                return new ManagerResponse("error", "Friend request not found.");
            }

        } catch(RuntimeException $e) {
            return new ManagerResponse("error", "A database error occurred: " . $e->getMessage());
        }
    }

    public function rejectFriendRequest(string $senderUsername, string $receiverUsername): ManagerResponse {
        try {
            $result = $this->friendshipService->rejectFriendRequest($senderUsername, $receiverUsername);
            if($result) {
                return new ManagerResponse("success", "Friend request rejection successful.");
            }
            else {
                return new ManagerResponse("error", "Friend request not found.");
            }
        } catch(RuntimeException $e) {
            return new ManagerResponse("error", "A database error occurred: " . $e->getMessage());
        }
    }

    // Throws user-non-existent exception
    public function getIncomingPendingFriendRequests(string $username): ManagerResponse {
        try {
            if(!$this->userService->userExistsByUsername($username)) {
                throw new UserNonExistentException("User with specified username doesn't exist.");
            }
            $result = $this->friendshipService->getIncomingPendingFriendRequests($username);
            return new ManagerResponse("success", $result);
        } catch(RuntimeException $e) {
            return new ManagerResponse("error", "A database error occurred: " . $e->getMessage());
        }
    }
}