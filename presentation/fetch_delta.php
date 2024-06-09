<?php 
require_once('../bootstrap.php');
require_once('../models/Document.php');
$documentId = $_GET['document_id'];

$result = $docManager->getDocumentById((int)$documentId);

if($result->status === "success") {
    // return Document's contents
    $result->message = $result->message->getDelta();
}
header('Content-Type: application/json');
echo json_encode($result);
?>