<?php 
class FriendRequest {
    private int $request_id;
    private string $sender_username;
    private string $receiver_username;
    private string $status;

    public function getRequestId(): int {
        return $this->request_id;
    }
    public function setRequestId(int $request_id): void{
        $this->request_id = $request_id;
    }
    public function getSenderUsername(): string {
        return $this->sender_username;
    }
    public function setSenderUsername(string $senderUsername): void {
        $this->sender_username = $senderUsername;
    }
    public function getReceiverUsername(): string {
        return $this->receiver_username;
    }
    public function setReceiverUsername(string $receiverUsername): void {
        $this->receiver_username = $receiverUsername;
    }
    public function getStatus(): string {
        return $this->status;
    }
    public function setStatus(string $status): void {
        $this->status = $status;
    }
}