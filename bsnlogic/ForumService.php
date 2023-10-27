<?php
require_once('../interfaces/DatabaseServiceInterface.php');
require_once('../interfaces/ForumServiceInterface.php');

class ForumService implements ForumServiceInterface {
    private DatabaseServiceInterface $databaseService;
    public function __construct(DatabaseServiceInterface $databaseService) {
        $this->databaseService = $databaseService;
    }

    /*
     * This method will always either return true or throw a RuntimeException, as it is an insert query.
     * It will never return false.
     * document_id and visibility are foreign keys, so executing the query on an invalid document_id or visibility will throw a RuntimeException
     *  in the layer below (the Data Access layer).
     */
    public function createForumPost(int $documentId, int $visibility): bool {
        $sql = "INSERT INTO document_visibility (document_id, visibility_level_id) VALUES (?, ?)";
        $result = $this->databaseService->executeQuery($sql, [$documentId, $visibility], "ii", "insert");
        return $result;
    }


}