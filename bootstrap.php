<?php 
require_once('data_access/DatabaseService.php');
require_once('bsnlogic/UserService.php');
require_once('bsnlogic/DocumentService.php');
require_once('bsnlogic/ForumService.php');
require_once('bsnlogic/FriendshipService.php');
require_once('manager/UserManager.php');
require_once('manager/DocumentsManager.php');
$dbService = new DatabaseService("localhost", "root", "", "wordprocessordb");
$userService = new UserService($dbService);
$friendshipService = new FriendshipService($dbService);
$userManager = new UserManager($userService, $friendshipService);
$docService = new DocumentService($dbService); 
$forumService = new ForumService($dbService);    
$docManager = new DocumentsManager($docService, $userService, $friendshipService, $forumService);
