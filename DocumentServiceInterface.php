<?php 
interface DocumentServiceInterface {
    
    // Returns an array containing open_document_id and open_document_name 
    public function openDocument(int $documentId): array;

    public function getDocumentById(int $documentId): ?Document;

    public function addDocument(Document $doc): string;

    public function updateDocumentContents(int $documentId, string $newDelta): string;

    public function updateLastSavedWithNow(int $documentId): string;

    public function getDocumentTitleFromId(int $documentId): string | bool;

    public function deleteDocumentById(int $documentId): string;

    // public function getFriendsDocuments(int $userId, array $friendsList): array;

    // public function getPublicDocuments(): array;

    public function getMyDocuments(string $username): array;

    public function getMyDocumentsByTitle(string $username, string $title): array;

    // public function convertToPDF(Document $doc): void;
}