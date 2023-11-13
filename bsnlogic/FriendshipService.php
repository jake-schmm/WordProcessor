<?php
require_once('../interfaces/DatabaseServiceInterface.php');
require_once('../interfaces/FriendshipServiceInterface.php');

class FriendshipService implements FriendshipServiceInterface {
    private DatabaseServiceInterface $databaseService;
    public function __construct(DatabaseServiceInterface $databaseService) {
        $this->databaseService = $databaseService;
    }

    // Returns an array of strings (usernames of friends in this user's friends list)
    public function getFriendsList(string $username): array {
        $friendUsernames = [];
        $sql = "SELECT friend_username FROM friends_list WHERE BINARY user_username=?";
        $result = $this->databaseService->executeQuery($sql, [$username], "s", "select");
        while ($row = $result->fetch_assoc()) { 
            $friendUsernames[] = $row["friend_username"];
        }
        return $friendUsernames;
    }

    // Returns true on success, false only if a friend request or friendship already existed. 
    // Throws RuntimeException if problem with insert query 
    public function sendFriendRequest(string $senderUsername, string $receiverUsername): bool {
        // First check if friend request (pending or accepted) exists for this sender and receiver pair
        $friendRequestExists = $this->friendRequestExists($senderUsername, $receiverUsername);
        if($friendRequestExists) {
            return false;
        }
        // This check is necessary if a recipient accepts a friend request to prevent them from sending 
        $friendshipExists = $this->friendshipExists($senderUsername, $receiverUsername);
        if($friendshipExists) {
            return false;
        }
        else {
            // INSERT query will always return either true or cause a RuntimeException. It will never return a value of false 
            $sql = "INSERT INTO friend_requests (sender_username, receiver_username) VALUES (?, ?)";
            $result = $this->databaseService->executeQuery($sql, [$senderUsername, $receiverUsername], "ss", "insert");
            return true;
        }
    }

    // Returns false if sender_username and receiver_username aren't found in friend_requests (i.e. if num_rows affected = 0)
    // True upon successful update and inserts
    public function acceptFriendRequest(string $senderUsername, string $receiverUsername): bool {
        $sql1 = "UPDATE friend_requests SET status='accepted' WHERE BINARY sender_username = ? AND BINARY receiver_username=? AND status = 'pending'";
        $result = $this->databaseService->executeQuery($sql1, [$senderUsername, $receiverUsername], "ss", "update");
        if($result) {
            // Insert two friends_list records: bidirectional
            $sql2 = "INSERT INTO friends_list (user_username, friend_username) VALUES (?, ?)";
            $this->databaseService->executeQuery($sql2, [$senderUsername, $receiverUsername], "ss", "insert");
            $this->databaseService->executeQuery($sql2, [$receiverUsername, $senderUsername], "ss", "insert");
            
            // Remove pending requests between these users if current user accepts first
            // It's ok if this query returns false, meaning no records were found. Just delete any that may exist
            // This query may throw a RuntimeException if something goes wrong at the DatabaseService level, just like any query  
            $sql3 = "DELETE FROM friend_requests WHERE BINARY sender_username=? AND BINARY receiver_username=? AND status = 'pending'";
            $this->databaseService->executeQuery($sql3, [$receiverUsername, $senderUsername], "ss", "delete"); 

            return true; 
        }
        // if no match was found with sender_username and receiver_username in friend_requests table 
        else {
            return false;
        }
    }

    // Deletes friend request record. Returns true upon success
    // Returns false if a match for senderUsername, receiverUsername wasn't found in friend_requests table 
    public function rejectFriendRequest(string $senderUsername, string $receiverUsername): bool {
        $sql = "DELETE FROM friend_requests WHERE BINARY sender_username=? AND BINARY receiver_username=? AND status='pending'";
        $result = $this->databaseService->executeQuery($sql, [$senderUsername, $receiverUsername], "ss", "delete");
        return $result;
    }

    // Gets all pending friend requests that were sent to the user with specified username 
    public function getIncomingPendingFriendRequests(string $username): array {
        $sql = "SELECT * FROM friend_requests WHERE BINARY receiver_username = ? AND status='pending'";
        $result = $this->databaseService->executeQuery($sql, [$username], "s", "select");
        $friendRequests = [];

        while ($row = $result->fetch_assoc()) {
            $friendRequest = new FriendRequest();
            $friendRequest->setRequestId($row['request_id']);
            $friendRequest->setSenderUsername($row['sender_username']);
            $friendRequest->setReceiverUsername($row['receiver_username']);
            $friendRequest->setStatus($row['status']);

            $friendRequests[] = $friendRequest;
        }
        return $friendRequests;
    }

    // Returns true if there's a pending or accepted friend request.
    public function friendRequestExists(string $senderUsername, string $receiverUsername): bool {
        $sql = "SELECT * FROM friend_requests WHERE BINARY sender_username=? AND BINARY receiver_username=?";
        $result = $this->databaseService->executeQuery($sql, [$senderUsername, $receiverUsername], "ss", "select");
        if($result && $result->num_rows > 0) {
            return true;
        }
        return false;
    }

    public function friendshipExists(string $userUsername, string $friendUsername): bool {
        $sql = "SELECT * FROM friends_list WHERE BINARY user_username=? AND BINARY friend_username=?";
        $result = $this->databaseService->executeQuery($sql, [$userUsername, $friendUsername], "ss", "select");
        if($result && $result->num_rows > 0) {
            return true;
        }
        return false;
    }
    
    
}