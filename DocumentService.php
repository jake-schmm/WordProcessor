<?php
require_once('DocumentServiceInterface.php');
require_once('DatabaseServiceInterface.php');

class DocumentService implements DocumentServiceInterface {
    private DatabaseServiceInterface $databaseService;

    public function __construct(DatabaseServiceInterface $databaseService) {
        $this->databaseService = $databaseService;
    }

    public function openDocument(int $documentId): array {
        // if (session_status() == PHP_SESSION_NONE) {
        //     session_start();
        // }
        // $_SESSION["open_document_id"] = $documentId;
        // $_SESSION["open_document_name"] = $this->getDocumentTitleFromId($documentId);
        // header("Location: index.php");
        // exit();
        $result = [];
        $result["open_document_id"] = $documentId;
        $result["open_document_name"] = $this->getDocumentTitleFromId($documentId);
        return $result;
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
            $doc->setAuthor($row['author']);
            $doc->setLast_Saved($row['last_saved']);
            return $doc;
        }
        else {
            return NULL;
        }
    }

    public function addDocument(Document $doc): string {
        $sql = "INSERT INTO document (title, delta, author, last_saved) VALUES (?, ?, ?, ?)";
        $result = $this->databaseService->executeQuery($sql, 
                [$doc->getTitle(), $doc->getDelta(), $doc->getAuthor(), $doc->getLast_Saved()], "ssss", "insert");
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

    public function updateLastSavedWithNow(int $documentId): string {
        $sql = "UPDATE document SET last_saved = ? WHERE id = ?";
        $params = [date('Y-m-d H:i:s'), $documentId];
        $result = $this->databaseService->executeQuery($sql, $params, "si", "update");
        if($result) {
            return "";
        }
        else {
            return "There was an error updating last_saved on the document";
        }
    }

    public function getDocumentTitleFromId(int $documentId): string | bool {
        $sql = "SELECT title FROM document WHERE id = ?";
        $result = $this->databaseService->executeQuery($sql, [$documentId], "i", "select");
        if ($result !== false && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['title'];
        } else {
            //return "Could not get document";
            return false;
        }
    }

    public function deleteDocumentById(int $documentId): bool {
        $sql = "DELETE FROM document WHERE id = ?";
        $result = $this->databaseService->executeQuery($sql, [$documentId], "i", "delete");
        if($result) {
            return true;
        }
        else {
            return false;
        }
    }

    public function getMyDocuments(string $username): array {
        $sql = "SELECT * FROM document WHERE author = ? ORDER BY last_saved DESC";
        $result = $this->databaseService->executeQuery($sql, [$username], "s", "select");
        $documents = []; 

        while ($row = $result->fetch_assoc()) {
            $document = new Document();
            $document->setId($row['id']);
            $document->setTitle($row['title']);
            $document->setAuthor($row['author']);
            $document->setLast_Saved($row['last_saved']);
            $document->setDelta($row['delta']);

            $documents[] = $document;
        }
        return $documents;
    }

    public function getMyDocumentsByTitle(string $username, string $title): array {
        $sql = "SELECT * FROM document WHERE author = ? AND BINARY title LIKE ? ORDER BY last_saved DESC";
        $searchQuery = "%" . $title . "%";
        $result = $this->databaseService->executeQuery($sql, [$username, $searchQuery], "ss", "select");
        $documents = []; 

        while ($row = $result->fetch_assoc()) {
            $document = new Document();
            $document->setId($row['id']);
            $document->setTitle($row['title']);
            $document->setAuthor($row['author']);
            $document->setLast_Saved($row['last_saved']);
            $document->setDelta($row['delta']);

            $documents[] = $document;
        }
        return $documents;
    }
}