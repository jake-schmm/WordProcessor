<?php
require_once('DocumentsManagerInterface.php');
require_once('DocumentServiceInterface.php');
class DocumentsManager implements DocumentsManagerInterface {
    private DocumentServiceInterface $docService;
    public function __construct(DocumentServiceInterface $docService) {
        $this->docService = $docService;
    }

    public function openDocument(int $documentId): array {
        return $this->docService->openDocument($documentId);
    }

    public function getMyDocuments(string $username): array {
        // first validate username using userExistsByUsername from UserService
        return $this->docService->getMyDocuments($username);
    }








}
?>