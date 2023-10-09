<?php
require_once('DocumentServiceInterface.php');
require_once('DatabaseServiceInterface.php');

class DocumentService implements DocumentServiceInterface {
    private DatabaseServiceInterface $databaseService;

    public function __construct(DatabaseServiceInterface $databaseService) {
        $this->databaseService = $databaseService;
    }

    public function openDocument(int $documentId): void {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION["open_document_id"] = $documentId;
        $_SESSION["open_document_name"] = $this->getDocumentTitleFromId($documentId);
        header("Location: index.php");
        exit();
    }

    public function getDocumentById(int $documentId): ?Document {
        $sql = "SELECT * FROM document WHERE id = ?";
        $result = $this->databaseService->executeQuery($sql, [$documentId], "i", "select");
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $doc = new Document();
            $doc->setId($row['id']);
            $doc->setTitle($row['title']);
            $doc->setDelta($row['delta']);
            $doc->setUserId($row['user_id']);
            $doc->setLast_Saved($row['last_saved']);
            return $doc;
        }
        else {
            return NULL;
        }
    }

    public function addDocument(Document $doc): string {
        $sql = "INSERT INTO document (title, delta, user_id, last_saved) VALUES (?, ?, ?, ?)";
        $result = $this->databaseService->executeQuery($sql, 
                [$doc->getTitle(), $doc->getDelta(), $doc->getUserId(), $doc->getLast_Saved()], "ssis", "insert");
        if($result) {
            return "Document saved.";
        }
        else {
            return "There was an error saving the document.";
        }
    }

    public function updateDocumentContents(int $documentId, string $newDelta): string {
        $sql = "UPDATE document SET delta = ? WHERE id = ?";
        $params = [$newDelta, $documentId];
        $result = $this->databaseService->executeQuery($sql, $params, "si", "update");
        if($result) {
            return "Document saved.";
        }
        else {
            return "There was an error saving the document";
        }
    }

    public function getDocumentTitleFromId(int $documentId): string {
        $sql = "SELECT title FROM document WHERE id = ?";
        $result = $this->databaseService->executeQuery($sql, [$documentId], "i", "select");
        if ($result !== false && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['title'];
        } else {
            return "Could not get document";
        }
    }

}