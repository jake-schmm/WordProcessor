<?php
require_once('../interfaces/DocumentsManagerInterface.php');
require_once('../interfaces/DocumentServiceInterface.php');
require_once('../interfaces/UserServiceInterface.php');
require_once('../interfaces/FriendshipServiceInterface.php');
require_once('../interfaces/ForumServiceInterface.php');
require_once('../models/Document.php');
require_once('../models/ManagerResponse.php');

class DocumentsManager implements DocumentsManagerInterface {
    private DocumentServiceInterface $docService;
    private UserServiceInterface $userService;
    private ForumServiceInterface $forumService;
    private FriendshipServiceInterface $friendshipService;
    public function __construct(DocumentServiceInterface $docService, UserServiceInterface $userService, FriendshipServiceInterface $friendshipService, ForumServiceInterface $forumService) {
        $this->docService = $docService;
        $this->userService = $userService;
        $this->friendshipService = $friendshipService;
        $this->forumService = $forumService;
    }

    public function openDocument(int $documentId): ManagerResponse {
        try {
            $resultArr = $this->docService->openDocument($documentId);
            // If documentId didn't exist
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

    public function createForumPost(int $documentId, int $visibility): ManagerResponse {
        try {
            // Because the underlying query is insert, result will always be true, never false. 
            // In all other cases, the method will throw a RuntimeException. i.e. if documentId or visibility are invalid values (because they are foreign keys)
            $result = $this->forumService->createForumPost($documentId, $visibility);
            return new ManagerResponse("success", "Publishing document to forum was successful.");

        } catch(RuntimeException $e) {
            if(str_contains($e->getMessage(), "Duplicate entry") && str_contains($e->getMessage(), "PRIMARY")) {
                return new ManagerResponse("error", "This document already has been published with visibility " . $this->intToEnumName($visibility));
            }
            return new ManagerResponse("error", "A database error occurred: " . $e->getMessage());
        }
    }

    // Helper method
    private function intToEnumName(int $intValue): ?string {
        foreach (Visibility::getAllValues() as $enumValue) {
            if ($intValue === $enumValue->value) {
                return $enumValue->name;
            }
        }
        return null; // Return null if no matching enum name is found
    }

    public function getPublicPublishedDocuments(): ManagerResponse {
        try {
            $result = $this->docService->getPublicPublishedDocuments();
            return new ManagerResponse("success", $result); // return array of documents (could be empty)
        } catch(RuntimeException $e) {
            return new ManagerResponse("error", "A database error occurred: " . $e->getMessage());
        }
    }

    public function getPublicPublishedDocumentsByTitle(string $title): ManagerResponse {
        try {
            $result = $this->docService->getPublicPublishedDocumentsByTitle($title);
            return new ManagerResponse("success", $result); // return array of documents (could be empty)
        } catch(RuntimeException $e) {
            return new ManagerResponse("error", "A database error occurred: " . $e->getMessage());
        }
    }

    public function getFriendsPublishedDocumentsByTitle(string $username, string $title): ManagerResponse {
        if(!$this->userService->userExistsByUsername($username)) {
            throw new UserNonExistentException("User whose friends list you're trying to fetch doesn't exist.");
        }
        try {
            $friendsList = $this->friendshipService->getFriendsList($username);
            foreach($friendsList as $friend) {
                if(!$this->userService->userExistsByUsername($friend)) {
                    throw new UserNonExistentException("At least one friend has a username that doesn't belong to a user");
                }
            }
            $result = $this->docService->getFriendsPublishedDocumentsByTitle($friendsList, $title);
            return new ManagerResponse("success", $result);
        } catch(RuntimeException $e) {
            return new ManagerResponse("error", "A database error occured: " . $e->getMessage());
        }
    }

    public function getMyPublishedDocumentsByTitle(string $username, string $title): ManagerResponse {
        if(!$this->userService->userExistsByUsername($username)) {
            throw new UserNonExistentException("User doesn't exist.");
        }
        try {
            $result = $this->docService->getMyPublishedDocumentsByTitle($username, $title);
            return new ManagerResponse("success", $result); // return array of documents (could be empty)
        } catch(RuntimeException $e) {
            return new ManagerResponse("error", "A database error occurred: " . $e->getMessage());
        }
    }
    
    public function convertToPDF(string $documentHtmlContent): void {
        $this->docService->convertToPDF($documentHtmlContent);
    }


}
?>