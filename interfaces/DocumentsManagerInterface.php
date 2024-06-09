<?php 
interface DocumentsManagerInterface {
    public function openDocument(int $documentId): ManagerResponse;
    public function getMyDocuments(string $username): ManagerResponse;
    public function deleteDocumentById(int $documentId): ManagerResponse;
    public function getMyDocumentsByTitle(string $username, string $title): ManagerResponse;
    public function updateDocumentContents(int $documentId, string $newDelta): ManagerResponse;
    public function updateLastSavedWithNow(int $documentId): ManagerResponse;
    public function getDocumentById(int $documentId): ManagerResponse;

    /*
    * Return id of document that was added as message of ManagerResponse if successful, error message in message if not
    */
    public function addDocument(Document $doc): ManagerResponse;
    public function createForumPost(int $documentId, int $visibility): ManagerResponse;
    public function getPublicPublishedDocuments(): ManagerResponse;

    public function getPublicPublishedDocumentsByTitle(string $title): ManagerResponse;
    public function getFriendsPublishedDocumentsByTitle(string $username, string $title): ManagerResponse;

    public function getMyPublishedDocumentsByTitle(string $username, string $title): ManagerResponse;
}