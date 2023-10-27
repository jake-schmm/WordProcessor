<?php
require_once('DocumentsManagerInterface.php');
require_once('DocumentServiceInterface.php');
require_once('UserServiceInterface.php');
require_once('Document.php');
require_once('ManagerResponse.php');

class DocumentsManager implements DocumentsManagerInterface {
    private DocumentServiceInterface $docService;
    private UserServiceInterface $userService;
    public function __construct(DocumentServiceInterface $docService, UserServiceInterface $userService) {
        $this->docService = $docService;
        $this->userService = $userService;
    }

    public function openDocument(int $documentId): ManagerResponse {
        try {
            $resultArr = $this->docService->openDocument($documentId);
            // Check if result is empty - i.e. if documentId didn't exist
            if(empty($resultArr)) {
                return new ManagerResponse("error", "There was an error opening the document.");
            }
            else {
                return new ManagerResponse("success", $resultArr);
            }
        } catch(RuntimeException $e) {
            return new ManagerResponse("error", "A database error occurred: " . $e->getMessage());
        }
        
    }

    public function getMyDocuments(string $username): ManagerResponse {
        if(!$this->userService->userExistsByUsername($username)) {
            throw new UserNonExistentException("User doesn't exist.");
        }
        try {
            $result = $this->docService->getMyDocuments($username);
            return new ManagerResponse("success", $result);
        } catch(RuntimeException $e) {
            return new ManagerResponse("error", "A database error occurred: " . $e->getMessage());
        }
        
    }

    public function getMyDocumentsByTitle(string $username, string $title): ManagerResponse {
        if(!$this->userService->userExistsByUsername($username)) {
            throw new UserNonExistentException("User doesn't exist.");
        }
        try {
            $result = $this->docService->getMyDocumentsByTitle($username, $title);
            return new ManagerResponse("success", $result); // return array of documents (could be empty)
        } catch(RuntimeException $e) {
            return new ManagerResponse("error", "A database error occurred: " . $e->getMessage());
        }
        
    }

    public function updateDocumentContents(int $documentId, string $newDelta): ManagerResponse {
        $result = $this->docService->updateDocumentContents($documentId, $newDelta);
        try {
            if($result) {
                return new ManagerResponse("success", "Document contents successfully updated.");
            }
            else {
                // documentId not found
                return new ManagerResponse("error", "There was an error updating the document.");
            }
        } catch(RuntimeException $e) {
            return new ManagerResponse("error", "A database error occurred: " . $e->getMessage());
        }
    }

    public function updateLastSavedWithNow(int $documentId): ManagerResponse {
        $result = $this->docService->updateLastSavedWithNow($documentId);
        try {
            if($result) {
                return new ManagerResponse("success", "Document Last Saved time successfully updated.");
            }
            else {
                // documentId not found
                return new ManagerResponse("error", "There was an error updating the document's Last Saved time.");
            }
        } catch(RuntimeException $e) {
            return new ManagerResponse("error", "A database error occurred: " . $e->getMessage());
        }
    }
    public function getDocumentById(int $documentId): ManagerResponse {
        try {
            $result = $this->docService->getDocumentById($documentId);
            if($result === null) {
                return new ManagerResponse("error", "Document with id " . $documentId . " not found.");
            }
            else {
                return new ManagerResponse("success", $result); // return Document object as message
            }
        } catch(RuntimeException $e) {
            return new ManagerResponse("error", "A database error occurred: " . $e->getMessage());
        }        
    }
    public function deleteDocumentById(int $documentId): ManagerResponse {
        $result = $this->docService->deleteDocumentById($documentId);
        try {
            if($result) {
                return new ManagerResponse("success", "Document successfully deleted.");
            }
            // Check if result is false - i.e. if document with documentId didn't exist 
            else {
                return new ManagerResponse("error", "There was an error deleting the document.");
            }
        } catch(RuntimeException $e) {
            return new ManagerResponse("error", "A database error occurred: " + $e->getMessage());
        }
    }

    /*
    * Return id of document that was added as message of ManagerResponse if successful, error message in message if not
    */
    public function addDocument(Document $doc): ManagerResponse {
        try {
            $result = $this->docService->addDocument($doc);
            if($result === null) {
                return new ManagerResponse("error", "Document insert failed.");
            }
            else {
                return new ManagerResponse("success", $result); 
            }
        } catch(RuntimeException $e) {
            return new ManagerResponse("error", "A database error occurred: " . $e->getMessage());
        }
        
    }






}
?>