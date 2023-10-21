<?php 
interface DocumentsManagerInterface {
    public function openDocument(int $documentId): array;
    public function getMyDocuments(string $username): array;



}