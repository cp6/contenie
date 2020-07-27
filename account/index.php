<?php
require_once('../classes/account_handler.php');

$acc = new accountHandler();
$acc->setUserData($_SESSION['user']);

echo $acc->username;