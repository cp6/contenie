<?php
require_once('main.php');

class accountHandler extends main
{
    public string $username;
    public int $uid;
    public array $user_data;

    public function setUserData()
    {
        if (isset($_SESSION['user'])) {
            $this->uid = $_SESSION['user'];
            $db = $this->db_connect(true);
            $select = $db->prepare("SELECT * FROM `accounts` WHERE `uid` = ? LIMIT 1;");
            $select->execute([$this->uid]);
            $this->user_data = $select->fetchAll(PDO::FETCH_ASSOC)[0];
            $this->setUsername();
        } else {
            $this->redirectTo('../login/');
        }
    }

    public function setUsername()
    {
        $this->username = $this->user_data['username'];
    }

    public function accountHomepage()
    {
        $this->pageHead("$this->username account", "$this->username account homepage.", '../', true, true, true, true);
        $this->navBar('account');
        $this->tagOpen('div', 'container');
        $this->outputString("<h1>Welcome $this->username</h1>");
        $this->tagClose('div');
    }

}