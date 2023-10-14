<?php 
interface DocumentServiceInterface {
    public function openDocument(int $documentId): void;

    public function getDocumentById(int $documentId): ?Document;

    public function addDocument(Document $doc): string;

    public function updateDocumentContents(int $documentId, string $newDelta): string;

    public function getDocumentTitleFromId(int $documentId): string;

    // public function getFriendsDocuments(int $userId, array $friendsList): array;

    // public function getPublicDocuments(): array;

    // public function getMyDocuments(string $username): array;

    // public function convertToPDF(Document $doc): void;
}