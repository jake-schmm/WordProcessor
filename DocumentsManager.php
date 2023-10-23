<?php
require_once('DocumentsManagerInterface.php');
require_once('DocumentServiceInterface.php');
require_once('UserServiceInterface.php');
class DocumentsManager implements DocumentsManagerInterface {
    private DocumentServiceInterface $docService;
    private UserServiceInterface $userService;
    public function __construct(DocumentServiceInterface $docService, UserServiceInterface $userService) {
        $this->docService = $docService;
        $this->userService = $userService;
    }

    public function openDocument(int $documentId): array {
        return $this->docService->openDocument($documentId);
    }

    public function getMyDocuments(string $username): array {
        if(!$this->userService->userExistsByUsername($username)) {
            throw new UserNonExistentException("User doesn't exist.");
        }
        return $this->docService->getMyDocuments($username);
    }

    public function getMyDocumentsByTitle(string $username, string $title): array {
        if(!$this->userService->userExistsByUsername($username)) {
            throw new UserNonExistentException("User doesn't exist.");
        }
        return $this->docService->getMyDocumentsByTitle($username, $title);
    }

    public function deleteDocumentById(int $documentId): string {
        $result = $this->docService->deleteDocumentById($documentId);
        if($result) {
            return "Document successfully deleted.";
        }
        else {
            return "There was an error deleting the document.";
        }
    }






}
?>