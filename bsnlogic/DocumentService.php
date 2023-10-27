<?php
require_once('../interfaces/DocumentServiceInterface.php');
require_once('../interfaces/DatabaseServiceInterface.php');

class DocumentService implements DocumentServiceInterface {
    private DatabaseServiceInterface $databaseService;

    public function __construct(DatabaseServiceInterface $databaseService) {
        $this->databaseService = $databaseService;
    }

    // Returns an empty array if unsuccessful, or an array with document id and name if successful
    public function openDocument(int $documentId): array {
        $result = [];
        
        $title = $this->getDocumentTitleFromId($documentId);
        if (is_string($title)) {
            $result["open_document_id"] = $documentId;
            $result["open_document_name"] = $title;
        }
        return $result;
    }

    // Returns null if documentId doesn't exist in database. Otherwise, return Document with id
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

    public function addDocument(Document $doc): ?int {
        $sql = "INSERT INTO document (title, delta, author, last_saved) VALUES (?, ?, ?, ?)";
        $result = $this->databaseService->executeQuery($sql, 
                [$doc->getTitle(), $doc->getDelta(), $doc->getAuthor(), $doc->getLast_Saved()], "ssss", "insert");
        if($result) {
            return $this->databaseService->getMySqli()->insert_id;
        }
        else {
            return null;
        }
    }

    public function updateDocumentContents(int $documentId, string $newDelta): bool {
        $sql = "UPDATE document SET delta = ? WHERE id = ?";
        $params = [$newDelta, $documentId];
        $result = $this->databaseService->executeQuery($sql, $params, "si", "update");
        return $result; // true if num_rows affected > 0, false otherwise (i.e. if documentId was not found)
    }

    public function updateLastSavedWithNow(int $documentId): bool {
        $sql = "UPDATE document SET last_saved = ? WHERE id = ?";
        $params = [date('Y-m-d H:i:s'), $documentId];
        $result = $this->databaseService->executeQuery($sql, $params, "si", "update");
        return $result; // true if num_rows affected > 0, false otherwise (i.e. if documentId was not found)
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