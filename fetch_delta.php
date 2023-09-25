<?php 
$documentId = $_GET['document_id'];

$servername = "localhost";
$user = "root";
$pass= "";
$db = "wordprocessordb";
// Create connection
$conn = new mysqli($servername, $user, $pass, $db);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT delta FROM document WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $documentId);
$stmt->execute();
$stmt->bind_result($serializedDelta);
$stmt->fetch();
$stmt->close();

echo $serializedDelta;

$conn->close();

?>