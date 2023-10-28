<!DOCTYPE html>
<html>
<head>
   <title>Online Text Editor</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <style>
    h1 {
        text-align: center;
    }
    .document-row {
        border: 1px solid;
        padding: 20px;
        margin: 20px 0;
        text-align: center;
        background: #c9c9c9;
        font-size: 20px;
    }
    .button-container {
        display: flex;
        justify-content: center;
        gap: 10px;
    }
    #documentNameFilterInput {
        width: 20%;
    }
    .filter-form-elements {
        display: flex;
        gap: 10px;
    }
    .openButton {
        margin-left: 10px;
    }
    body {
        /* https://uigradients.com/#Windy */
        background: #acb6e5; /* fallback for old browsers */
        background: -webkit-linear-gradient(to right, #acb6e5, #86fde8); /* Chrome 10-25, Safari 5.1-6 */
        background: linear-gradient(to right, #acb6e5, #86fde8); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    }
   </style>
   <!-- navbar functionality -->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>
<body>
    <?php
        require_once('../bootstrap.php');
        require_once('../models/Document.php');
        include 'navbar.php'; 
        $error_message = '';
        $docArray = [];
        session_start();
        
        $shouldLoadInitialDocuments = true;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if($_POST["submit"] == 'filter') {
                $shouldLoadInitialDocuments = false;
                $result = new ManagerResponse("success", []);
                $visibility = $_POST['visibility'] ?? null;

                switch($visibility) {
                    case 'public':
                        // get public published documents
                        $result = $docManager->getPublicPublishedDocumentsByTitle($_POST["documentNameFilterInput"]);
                        break;
                    case 'friends':
                        // get friends published documents
                        break;
                    case 'myself':
                        // get my published documents
                        try {
                            $result = $docManager->getMyPublishedDocumentsByTitle($_SESSION["username"], $_POST["documentNameFilterInput"]);
                        } catch(UserNonExistentException $e) {
                            $error_message = "Tried to get my published documents, but user doesn't exist.";
                        }
                        break;
                    default:
                        break;
                }

                // Handle result - display documents if successful, error message if unsuccessful
                if($result->status === "success") {
                    $docArray = $result->message;
                }
                else if($result->status === "error") {
                    $error_message = $result->message;
                }
            }

            if($_POST["submit"] == "open") {
                // Return an array with open_document_id and open_document_name that you will set session variables to
                // (or error if unable to open document)
                $result = $docManager->openDocument($_POST["doc_id"]);
                if($result->status === "success") {
                    // If the document you're opening from the forum doesn't belong to you, open in readonly mode
                    // (Compare the document's author to current user)
                    $getDocumentResult = $docManager->getDocumentById($_POST["doc_id"]);
                    if($getDocumentResult->status === "success") {
                        if($getDocumentResult->message->getAuthor() !== $_SESSION["username"]) {
                            $_SESSION["read_only_editor"] = "true";
                        }
                        $_SESSION["opened_from_button"] = "true";
                        $_SESSION["open_document_id"] = $result->message["open_document_id"];
                        $_SESSION["open_document_name"] = $result->message["open_document_name"];
                        header("Location: index.php");
                        exit();
                    }
                    else if($getDocumentResult->status === "error") {
                        $error_message = $getDocumentResult->message;
                    }
                }
                else { // openDocument not successful
                    $error_message = $result->message;
                }
            }
        }
        if($shouldLoadInitialDocuments) {
            $result = $docManager->getPublicPublishedDocuments();
            if($result->status === "success") {
                $docArray = $result->message;
            }
            else if($result->status === "error") {
                $error_message = $result->message;
            }
        }
    ?>
    <div class="container">
        <h1>Welcome to the Forum</h1>
        <form action="forum.php" method="post">
            <div class="filter-form-elements">
                <label for="documentNameFilterInput">Filter Posts:</label>
                <label><input type="radio" name="visibility" value="public" required checked> Public</label>
                <label><input type="radio" name="visibility" value="friends"> Friends</label>
                <label><input type="radio" name="visibility" value="myself"> Myself</label>
            </div>
            <div class="filter-form-elements">
                <input type="text" class="form-control" placeholder="Document Name" id="documentNameFilterInput" name="documentNameFilterInput">
                <button type="submit" name="submit" value="filter" class = "btn btn-primary">Search</button>
            </div>
        </form>
        <br/>
        <?php if (!empty(trim($error_message))): ?>
        <div class="alert alert-danger" role="alert">
          <?php echo $error_message; ?>
        </div>
        <?php endif; ?>
        <div style="overflow-y: auto; max-height: 800px;">
        <?php 
            foreach ($docArray as $doc) {
            ?>
                <div class="document-row">
                        <form action="forum.php" method="post">
                            <input type="hidden" class="form-control" value="<?php echo $doc->getId()?>" name="doc_id" required>
                            <?php echo htmlspecialchars($doc->getTitle()); ?> | Author: <?php echo htmlspecialchars($doc->getAuthor()); ?>
                            <button type="submit" name="submit" value="open" class="btn btn-primary openButton"><span class="glyphicon glyphicon-open" aria-hidden="true"> OPEN</button>
                        </form>
                </div>
            <?php
            }
        ?>
        </div>
            
    </div>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>	
</body>
</html>