<?php 
require_once('DatabaseService.php');
require_once('DocumentService.php');
require_once('Document.php');
$documentId = $_GET['document_id'];

$db = new DatabaseService("localhost", "root", "", "wordprocessordb");  
$docService = new DocumentService($db);  
$doc = $docService->getDocumentById((int)$documentId);
echo $doc->getDelta();
$db->closeConnection();

?>