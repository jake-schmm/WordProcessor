<?php 
interface DocumentsManagerInterface {
    public function openDocument(int $documentId): array;
    public function getMyDocuments(string $username): array;
    public function deleteDocumentById(int $documentId): string;

    public function getMyDocumentsByTitle(string $username, string $title): array;
}