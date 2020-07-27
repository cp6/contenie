<?php
require_once('main.php');

class accountHandler extends main
{
    public string $username;
    public int $uid;
    public array $user_data;
    public bool $is_admin = false;
    public bool $is_mod = false;
    public bool $is_subbed_user = false;
    public bool $is_user = false;

    public function setUserData()
    {
        if (isset($_SESSION['user'])) {
            $this->uid = $_SESSION['user'];
            $db = $this->db_connect(true);
            $select = $db->prepare("SELECT * FROM `accounts` WHERE `uid` = ? LIMIT 1;");
            $select->execute([$this->uid]);
            $this->user_data = $select->fetchAll(PDO::FETCH_ASSOC)[0];
            $this->setUsername();
            $this->setUserType();
        } else {
            $this->redirectTo('../login/');
        }
    }

    public function setUsername()
    {
        $this->username = $this->user_data['username'];
    }

    public function setUserType()
    {
        $ac_type = $this->user_data['account_type'];
        if ($ac_type == 1) {
            $this->is_user = true;
        } elseif ($ac_type == 2) {
            $this->is_subbed_user = true;
        } elseif ($ac_type == 3) {
            $this->is_mod = true;
        } elseif ($ac_type == 4) {
            $this->is_admin = true;
        }
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