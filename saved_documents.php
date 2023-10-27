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
   </style>
   <!-- navbar functionality -->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>
<body>
    <?php
        require_once('bootstrap.php');
        require_once('Document.php');
        require_once('Exceptions.php');
        include 'navbar.php'; 
        $error_message = '';
        $docArray = [];
        session_start();
        
        $shouldGetMyDocuments = true;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if($_POST["submit"] == 'open') {
                $result = $docManager->openDocument($_POST["doc_id"]);
                if($result->status === "success") {
                    $_SESSION["opened_from_button"] = "true";
                    $_SESSION["open_document_id"] = $result->message["open_document_id"];
                    $_SESSION["open_document_name"] = $result->message["open_document_name"];
                    header("Location: index.php");
                    exit();
                }
                else {
                    $error_message = $result->message;
                }
            }
            elseif($_POST["submit"] == 'delete') {
                $result = $docManager->deleteDocumentById($_POST["doc_id"]);
                if($result->status === "error") {
                    $error_message = $result->message;
                }
            }
            // Handle when the user clicks on the "Search" button and filtering by name
            elseif($_POST["submit"] == 'filter') {
                $shouldGetMyDocuments = false;
                try {
                    $result = $docManager->getMyDocumentsByTitle($_SESSION["username"], $_POST["documentNameFilterInput"]);
                    if($result->status === "success") {
                        $docArray = $result->message;
                    }
                    else {
                        $error_message = $result->message;
                    }
                }
                catch(UserNonExistentException $e) {
                    $error_message = $e->getMessage();
                }  
            }
        }

        // populate docArray (must place this after code that handles delete so that it updates after delete)
        if($shouldGetMyDocuments) {
            try {
                $result = $docManager->getMyDocuments($_SESSION["username"]); 
                if($result->status === "success") {
                    $docArray = $result->message;
                }
                else {
                    $error_message = $result->message;
                }
            }
            catch(UserNonExistentException $e) {
                $error_message = $e->getMessage();
            }
        }
    ?>
    <div class="container">
        <h1>Saved Documents</h1>
        <form action="saved_documents.php" method="post">
            <label for="documentNameFilterInput">Filter by Document Name (case sensitive):</label>
            <div class="filter-form-elements">
                <input type="text" class="form-control" id="documentNameFilterInput" name="documentNameFilterInput">
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
                $originalLastSaved = $doc->getLast_Saved();
                $dateTime = new DateTime($originalLastSaved);
                $readableLastSaved = $dateTime->format('F d, Y, h:i A');

                echo '<div class="document-row">';
                echo '<form action="saved_documents.php" method="post">';
                echo '<input type="hidden" class="form-control" value="'. $doc->getId() . '" name="doc_id" required>';
                echo htmlspecialchars($doc->getTitle()) . ' | Last Saved: ' . $readableLastSaved;
                echo '<div class="button-container"><button type="submit" name="submit" value="open" class="btn btn-primary"><span class="glyphicon glyphicon-open" aria-hidden="true"> OPEN</button>
                    <button type="button" data-toggle="modal" data-target="#deleteModal-' . $doc->getId() . '"class="btn btn-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> DELETE</button>
                    </div> ';
                echo '</form>';
                echo '</div>';
                ?>
                <div id="deleteModal-<?php echo $doc->getId();?>" class="modal fade" tabindex="-1" role="dialog">
					<div class="modal-dialog">
					<!-- Modal content -->
					    <div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 class="modal-title">Delete</h4>
								</div>
								<div class="modal-body">
									<form action="" method="post">
										<input type="hidden" class="form-control" value="<?php echo $doc->getId()?>" name="doc_id" required>
										<p>Are you sure you want to delete this document?</p>
										<button type="button" data-dismiss="modal" class="btn btn-secondary">No</button>
										<button type="submit" value="delete" name="submit" class="btn btn-danger">Yes</button>
									</form>
								</div>
						</div>
					</div>
				</div>
                <?php
            }
        ?>
        </div>
    </div>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>	
</body>
</html>