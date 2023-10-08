<!DOCTYPE html>
<html>
<head>
    <title>Online Text Editor</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <!-- Main Quill library -->
    <script src="//cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <link href="quill.snow.css" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

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
    </style>
    <!-- navbar functionality -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>
<body>
    <?php 
    require_once('DatabaseService.php');
    include 'navbar.php'; 
    session_start();
    echo $_SESSION["userID"];
    echo $_SESSION['username'];
    if(!isset($_SESSION["open_document_name"])) { // unset this every time you open a page other than editor page
        $_SESSION["open_document_name"] = "New Document";
    }
    $db = new DatabaseService("localhost", "root", "", "wordprocessordb");       
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if($_POST["submit"] == 'save') {  
            $serializedDelta = $_POST['editor_contents'];
            $currentUserId = $_SESSION['userID'];
            $currentTime = date('Y-m-d H:i:s'); // has to match DATETIME data type format in MySQL
            $title = "Example"; // change this
            $db->executeQuery("INSERT INTO document (title, delta, user_id, last_saved) VALUES (?, ?, ?, ?)", 
                [$title, $serializedDelta, $currentUserId, $currentTime], "ssis", "insert");
            

            // $servername = "localhost";
            // $user = "root";
            // $pass= "";
            // $db = "wordprocessordb";
                    
            // // Create connection
            // $conn = new mysqli($servername, $user, $pass, $db);
            // // Check connection
            // if ($conn->connect_error) {
            //     die("Connection failed: " . $conn->connect_error);
            // }
            // $serializedDelta = mysqli_real_escape_string($conn, $_POST['editor_contents']); // escape the JSON data
            // $currentUserId = $_SESSION['userID'];
            // $currentTime = date('Y-m-d H:i:s'); // has to match DATETIME data type format in MySQL
            // $title = "Example"; // change this
            // $stmt = $conn->prepare("INSERT INTO document (title, delta, user_id, last_saved) VALUES (?, ?, ?, ?)");
            // $stmt->bind_param("ssis", $title, $serializedDelta, $currentUserId, $currentTime);
            // $stmt->execute();
    
            // $conn->close();
        }
        
    }
    $db->closeConnection();
    ?>
    <div class="container">
    <h1>Editing "<?php echo $_SESSION["open_document_name"];?>"</h1>
    <form id="editor-form" action="index.php" method="post">
        <div id="editor"></div>
        <input type="hidden" id="editor-contents" name="editor_contents">
        <button type="submit" value="save" name="submit" class="btn btn-default">Save Document</button>
    </form>
    <!-- Load document with id 1 -->
    <button value="load" name="load" class="btn btn-default" onclick="Load(27)">Load Document</button>
    </div>

    <script>
        var quill = new Quill('#editor', {
            theme: 'snow',
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
            if(clickedButton.value === 'save') {
                var editorContents = JSON.stringify(quill.getContents());
                document.getElementById('editor-contents').value = editorContents;
            }
        });
    
        function Load(documentId) {
            console.log("Test");
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if(xhr.readyState === 4 && xhr.status === 200) {
                    var serializedDelta = xhr.responseText;
                    console.log(serializedDelta);
                    var deltaObject= JSON.parse(serializedDelta);
                    quill.setContents(deltaObject);
                }
            };
            xhr.open('GET', 'fetch_delta.php?document_id=' + documentId, true);
            xhr.send();
        }
        
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>	
    
    
</body>

</html>