<?php
require_once('../classes/account_handler.php');

$acc = new accountHandler();
$acc->setUserData();

echo $acc->username;