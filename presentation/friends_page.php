<!DOCTYPE html>
<html>
<head>
   <title>Online Text Editor</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <style>
        .friend-requests, .friends-list {
                margin-bottom: 20px;
        }
     
   </style>
   <!-- navbar functionality -->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>
<body>
    <?php
        require_once('../bootstrap.php');
        require_once('../models/FriendRequest.php');
        require_once('../Exceptions.php');
        include 'navbar.php'; 
        session_start();
        $requests_error_message = '';
        $list_error_message = '';
        $friend_requests = [];
        $friends_list = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if($_POST["submit"] == 'accept') {
                $result = $userManager->acceptFriendRequest($_POST["sender_username"], $_POST["receiver_username"]);
                if($result->status === "error") {
                    $requests_error_message = $result->message;
                }
            }

            if($_POST["submit"] == "reject") {
                $result = $userManager->rejectFriendRequest($_POST["sender_username"], $_POST["receiver_username"]);
                if($result->status === "error") {
                    $requests_error_message = $result->message;
                }
            }
            
            if($_POST["submit"] == "send-request") {
                try {
                    $result = $userManager->sendFriendRequest($_SESSION['username'], $_POST["recipient_username"]);
                    if($result->status === "error") {
                        $list_error_message = $result->message;
                    }
                } catch(UserNonExistentException $e) {
                    $list_error_message = $e->getMessage();
                }
            }
        }

        // Both of these methods can throw UserNonExistentException and can have error (but return the result upon success)
        $friend_requests = $userManager->getIncomingPendingFriendRequests($_SESSION['username'])->message;
        $friends_list = $userManager->getFriendsList($_SESSION['username'])->message;
    ?>
    <div class="container">
        <div class="friend-requests">
            <h2>Friend Requests</h2>
            <?php if (!empty(trim($requests_error_message))): ?>
            <div class="alert alert-danger" role="alert">
            <?php echo $requests_error_message; ?>
            </div>
            <?php endif; ?>
            <div style="overflow-y: auto; max-height: 400px;">
            <?php foreach ($friend_requests as $request) { ?>
                <form method="post" action="">
                    <span><?php echo htmlspecialchars($request->getSenderUsername()); ?></span>
                    <input type="hidden" class="form-control" value="<?php echo htmlspecialchars($request->getSenderUsername());?>" name="sender_username" required>
                    <input type="hidden" class="form-control" value="<?php echo htmlspecialchars($request->getReceiverUsername());?>" name="receiver_username" required>
                    <button type="submit" name="submit" class = "btn btn-success" value="accept">Accept</button>
                    <button type="submit" name="submit" class = "btn btn-danger" value="reject">Reject</button>
                </form>
            <?php } 
            if(empty($friend_requests)) {
                echo "This user has no friend requests.";
            }
            ?>
            
            </div>
        </div>

        <div class="friends-list">
            <h2>Friends List</h2>
            <?php if (!empty(trim($list_error_message))): ?>
            <div class="alert alert-danger" role="alert">
            <?php echo $list_error_message; ?>
            </div>
            <?php endif; ?>
            <div style="overflow-y: auto; max-height: 400px;">
                <ul>
                    <?php foreach ($friends_list as $friend): ?>
                        <li><?php echo htmlspecialchars($friend); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php
            if(empty($friends_list)) {
                echo "This user has no friends.";
            }?>
        </div>

        <div class="send-request">
            <form method="post" action="">
                <input type="text" name="recipient_username" placeholder="Enter Username">
                <button type="submit" name="submit" class="btn btn-primary" value="send-request">Send Friend Request</button>
            </form>
        </div>

        
            
    </div> <!-- div container -->

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>	
</body>
</html>