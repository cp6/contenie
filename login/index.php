<?php
require_once('../classes/login_handler.php');
$lh = new loginHandler();
if ($lh->loginButtonPressed()) {
    if ($lh->fakeInputNotFilled()) {
        $lh->attemptDetails($_POST['THE_username'], $_POST['THE_password']);
        //$lh->attemptLogin('../account');
    } else {
        $lh->redirectTo('index.php');
    }
} else {
    $lh->loginForm();
}