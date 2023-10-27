<?php 
interface DocumentServiceInterface {
    
    // Returns an array containing open_document_id and open_document_name 
    public function openDocument(int $documentId): array;

    public function getDocumentById(int $documentId): ?Document;

    // Return id of the document that was added OR null if nothing gets inserted 
    public function addDocument(Document $doc): ?int;

    public function updateDocumentContents(int $documentId, string $newDelta): bool;

    public function updateLastSavedWithNow(int $documentId): bool;

    public function getDocumentTitleFromId(int $documentId): string | bool;

    public function deleteDocumentById(int $documentId): bool;

    // public function getFriendsDocuments(int $userId, array $friendsList): array;

    // public function getPublicDocuments(): array;

    public function getMyDocuments(string $username): array;

    public function getMyDocumentsByTitle(string $username, string $title): array;

    // public function convertToPDF(Document $doc): void;
}