<?php 
require_once('DatabaseService.php');
require_once('UserService.php');
require_once('DocumentService.php');
require_once('UserManager.php');
require_once('DocumentsManager.php');
$dbService = new DatabaseService("localhost", "root", "", "wordprocessordb");
$userService = new UserService($dbService);
$userManager = new UserManager($userService);
$docService = new DocumentService($dbService);     
$docManager = new DocumentsManager($docService, $userService);
