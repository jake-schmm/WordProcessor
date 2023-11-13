<?php

interface FriendshipServiceInterface {
    public function getFriendsList(string $username): array;

    public function sendFriendRequest(string $senderUsername, string $receiverUsername): bool;

    public function acceptFriendRequest(string $senderUsername, string $receiverUsername): bool;

    public function rejectFriendRequest(string $senderUsername, string $receiverUsername): bool;

    public function getIncomingPendingFriendRequests(string $username): array;

    public function friendRequestExists(string $senderUsername, string $receiverUsername): bool;

    public function friendshipExists(string $userUsername, string $friendUsername): bool;
    
}