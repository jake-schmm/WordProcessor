<!DOCTYPE html>
<html>
<head>
   <title>Online Text Editor</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <!-- Main Quill library -->
    <script src="//cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <link href="../css/quill.snow.css" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">

    <style>
        #editor {
            height: 400px;
            width: 100%;
        }
        .ql-toolbar {
            width: 100%;
        }
        h1 {
            text-align: center;
        }
        .button-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            padding-top: 1%;
        }
        <?php
        session_start();
        $readOnly = isset($_SESSION['read_only_editor']);
        // hide editor buttons if in read_only mode 
        if($readOnly) {
            echo '.hideOnReadOnly {display:none;}';
        }
        ?>
    </style>
    <!-- navbar functionality -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>
<body>
    <?php 
    function PersistEditorContentsAfterSubmit() {
        echo '<script>;
        $(document).ready(function(){
            var savedContent = ' . $_POST["editor_contents"] . 
            '; quill.setContents(savedContent);
        });
        </script>';
    }
    
    require_once('../bootstrap.php');
    require_once('../models/Document.php');
    require_once('../models/Visibility.php');
    include 'navbar.php'; 
    date_default_timezone_set('America/New_York'); // for last_saved datetime inserts
    $error_message = "";
    $readOnly = isset($_SESSION['read_only_editor']) ? 'true' : 'false';
    unset($_SESSION["read_only_editor"]);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Save As button clicked
        if($_POST["submit"] == 'saveAs') {
            $_SESSION["editor_menu_button_clicked"] = true;
            $doc = new Document();
            $doc->setTitle($_POST['documentTitle']);
            $doc->setAuthor($_SESSION['username']);
            $doc->setLast_Saved(date('Y-m-d H:i:s'));
            $doc->setDelta($_POST['editor_contents']);
            $response = $docManager->addDocument($doc);
            if($response->status === "success") {
                $_SESSION["open_document_id"] = $response->message;
                $_SESSION["open_document_name"] = $doc->getTitle();
            }
            else {
                $error_message = $response->message;
            }
            
            // Persist the contents of the quill editor after saving
            PersistEditorContentsAfterSubmit();
        }
        // Save button clicked
        if($_POST["submit"] == 'save') {  
            $_SESSION["editor_menu_button_clicked"] = true;
            // If the document is new (unsaved)
            if (!isset($_SESSION["open_document_id"])) {
                // Persist contents of the quill editor and DISPLAY MODAL when client runs (right after server-side code runs)
                PersistEditorContentsAfterSubmit();
                echo "<script type='text/javascript'>
                    $(document).ready(function(){
                        $('#myModal').modal('show');
                    });
                </script>";
            }
            // If already on an opened document - overwrite 
            else {
                $updateDocContentsResult = $docManager->updateDocumentContents($_SESSION["open_document_id"], $_POST["editor_contents"]);
                if($updateDocContentsResult->status === "error") {
                    $error_message = $updateDocContentsResult->message;
                }
                else {
                    // Only update last_saved time if save was successful
                    $updateLastSavedResult = $docManager->updateLastSavedWithNow($_SESSION["open_document_id"]);
                    if($updateLastSavedResult->status === "error") {
                        $error_message = $updateLastSavedResult->message;
                    }
                }
                // Persist the contents of the quill editor after saving
                PersistEditorContentsAfterSubmit();
            }
        }
        // Publish Document button clicked
        if($_POST["submit"] == 'publish') {
            $_SESSION["editor_menu_button_clicked"] = true;
            PersistEditorContentsAfterSubmit();
            // Only allow publishing on saved documents
            if(!isset($_SESSION["open_document_id"])) {
                $error_message = "Document must be saved first before publishing.";
            }
            // If the open document has been saved
            else {
                $result = new ManagerResponse("success", "");
                $visibility = $_POST['visibility'] ?? null;

                switch($visibility) {
                    case 'public':
                        // publish to public
                        $result = $docManager->createForumPost($_SESSION["open_document_id"], Visibility::PUBLIC->value); 
                        break;
                    case 'friends':
                        // publish to friends
                        break;
                    case 'myself':
                        // publish to myself
                        $result = $docManager->createForumPost($_SESSION["open_document_id"], Visibility::MYSELF->value); 
                        break;
                    default:
                        break;
                }

                if($result->status === "error") {
                    $error_message = $result->message;
                }
            }
        }
        
    }
    // If 'opened_from_button' is not set and a menu button wasn't the last button clicked, reset document details.
    // This essentially initializes a new document when reloading the page 
    if (!isset($_SESSION["opened_from_button"]) && !isset($_SESSION["editor_menu_button_clicked"])) {
        unset($_SESSION["open_document_id"]);
        unset($_SESSION["open_document_name"]);
    }
    //unset($_SESSION["opened_from_button"]); -- this was moved into javascript section via xhr request

    unset($_SESSION["editor_menu_button_clicked"]);
    

    // If 'open_document_name' is not set, set it to default value
    if(!isset($_SESSION["open_document_name"])) { 
        $_SESSION["open_document_name"] = "New Document";
    }
   
    ?>
    <div class="container">
    <!-- display "Viewing (or Editing) <document_name> at the top -->
    <h1><?php echo ($readOnly === 'true') ? 'Viewing' : 'Editing'; ?> "<?php echo $_SESSION["open_document_name"];?>"</h1>
    <div id="xhrErrorMessage" style="display: none" class="alert alert-danger" role="alert">
    </div>
    <?php if (!empty(trim($error_message))): ?>
        <div class="alert alert-danger" role="alert">
          <?php echo $error_message; ?>
        </div>
        <?php endif; ?>
    <form id="editor-form" action="index.php" method="post">
        <div id="editor"></div>
        <input type="hidden" id="editor-contents" name="editor_contents">
        <!-- <div class="button-container hideOnReadOnly">
            <button type="button" class="btn btn-success">Record Speech</button>
        </div> -->
        <div class="button-container hideOnReadOnly">
            <button type="submit" value="save" name="submit" class="btn btn-default">Save</button>
            <button data-toggle="modal" type='button' class="btn btn-default" data-target="#myModal">Save As</button>
        </div>
        <div class="button-container hideOnReadOnly">
            <label><input type="radio" name="visibility" value="public" required checked> Public</label>
            <label><input type="radio" name="visibility" value="friends"> Friends</label>
            <label><input type="radio" name="visibility" value="myself"> Myself</label>
            <button type="submit" name="submit" value="publish" class="btn btn-default">Publish Document</button>
        </div>
    </form>

    </div>
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content -->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Save As</h4>
				</div>
				<div class="modal-body">
					<form id="saveAs-form" action="" method="post">
						<div class="form-group">
                            <input type="hidden" id="editor-contents-saveAs" name="editor_contents">
							<label for="documentTitle">Enter Document Name:</label>
							<input type="text" class="form-control" value="<?php echo $_SESSION["open_document_name"] ?>" name="documentTitle" required>
						</div>
                        <button type="submit" value="saveAs" name="submit" class="btn btn-default">Save</button>
                    </form>
				</div>
			</div>
        </div>
	</div>
    

    <script>
        var quill = new Quill('#editor', {
            theme: 'snow',
            readOnly: <?php echo $readOnly; ?>,
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    [{'list': 'ordered'}, {'list': 'bullet'}],
                    ['link'],
                    [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
                    [{ 'color': [] }],          // dropdown with defaults from theme
                    [{ 'font': [] }],
                    [{ 'align': [] }],
                ]
            }
        });

        document.getElementById('editor-form').addEventListener('submit', function(event) {
            var clickedButton = event.submitter;
            // add more button values here to persist editor contents upon clicking the button
            if(clickedButton.value === 'save' || clickedButton.value === 'publish') {
                var editorContents = JSON.stringify(quill.getContents());
                document.getElementById('editor-contents').value = editorContents;
            }
        });

        document.getElementById('saveAs-form').addEventListener('submit', function(event) {
            var clickedButton = event.submitter;
            if(clickedButton.value === 'saveAs') {
                var editorContents = JSON.stringify(quill.getContents());
                document.getElementById('editor-contents-saveAs').value = editorContents;
            }
        });
    
        function LoadDocumentContents(documentId) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if(xhr.readyState === 4) {
                    if(xhr.status === 200) {
                        var responseObj = JSON.parse(xhr.responseText);
                        if(responseObj.status === 'success') {
                            var deltaObject= JSON.parse(responseObj.message);
                            quill.setContents(deltaObject);
                        }
                        else {
                            $("#xhrErrorMessage").show();
                            $("#xhrErrorMessage").html(responseObj.message)
                        }   
                    } 
                    // if xhr.status !== 200
                    else {
                        console.log("Error", xhr.status, xhr.statusText);
                    }
                } // readyState === 4
            };
            xhr.open('GET', 'fetch_delta.php?document_id=' + documentId, true);
            xhr.send();
        }

        // Unsets a session variable associated with clicking an Open button for a document from another page
        function UnsetOpenDocumentSessionVariable() {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'unset_session_variable.php', true);
            xhr.onreadystatechange = function() {
                if(xhr.readyState === 4) {
                    if(xhr.status === 200) {
                        console.log("opened_from_button session variable unset succesfully")
                    }
                    else {
                        console.log("Error", xhr.status, xhr.statusText)
                    }
                }
            };
            xhr.send();
        }
       
        // Load the document's contents if open_document_id and opened_from_button are set (i.e. if opening from another page)
        $(document).ready(function(){
            if(<?php echo isset($_SESSION["opened_from_button"]) && isset($_SESSION["open_document_id"]) ? 'true' : 'false'; ?>) {
                LoadDocumentContents(<?php echo isset($_SESSION["open_document_id"]) ? $_SESSION["open_document_id"] : 'null'; ?>);
            }
           
            UnsetOpenDocumentSessionVariable();
        });
        
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>	
    
</body>

</html>