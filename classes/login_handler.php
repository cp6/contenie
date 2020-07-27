<?php
require_once('main.php');

class loginHandler extends main
{
    private string $username;
    private string $stated_password;
    private string $real_password;
    private string $ip_address;
    public int $uid;

    public function loginButtonPressed(): bool
    {
        if ($this->issetCheck('POST', 'LoginButton')) {
            return true;
        } else {
            return false;
        }
    }

    public function fakeInputNotFilled(): bool
    {
        if (!empty($_POST['username'])) {
            //Hidden form was filled in...
            return false;
        } else {
            return true;
        }
    }

    public function attemptDetails(string $username, string $password)
    {
        $this->username = $username;
        $this->stated_password = $password;
        $this->ip_address = $_SERVER['REMOTE_ADDR'];
    }

    public function getUserData(): bool
    {
        $db = $this->db_connect();
        $select = $db->prepare("SELECT `uid`, `username`, `password` FROM `accounts` WHERE `username` = ? LIMIT 1;");
        $select->execute([$this->username]);
        if ($select->rowCount() > 0) {//Row found for username
            $result = $select->fetch();
            $this->uid = $result['uid'];
            $this->real_password = $result['password'];
            return true;
        } else {//Username not found
            return false;
        }
    }

    public function checkPasswordCorrect(): bool
    {
        if (password_verify($this->stated_password, $this->real_password)) {
            return true;//Password is correct
        } else {
            return false;//Bad password
        }
    }

    public function doLoginSuccess(): void
    {
        $db = $this->db_connect();
        $update = $db->prepare("UPDATE `accounts` SET `login_count` = (login_count + 1), `last_login_at` = NOW(), `last_login_ip` = ? WHERE `uid` = ? LIMIT 1;");
        $update->execute([$this->ip_address, $this->uid]);
    }

    public function addLoginFailCount(): void
    {
        $db = $this->db_connect();
        $update = $db->prepare("UPDATE `accounts` SET login_fails = (login_fails + 1), `last_fail` = NOW() WHERE `username` = ? LIMIT 1;");
        $update->execute([$this->username]);
    }

    public function addLoginFailAttempt(): void
    {
        $db = $this->db_connect();
        $insert = $db->prepare('INSERT IGNORE INTO `login_attempts` (`username`, `ip`) VALUES (?, ?)');
        $insert->execute([$this->username, $this->ip_address]);
    }

    public function getRecentFailCount(): int
    {
        $db = $this->db_connect();
        $select = $db->prepare("SELECT COUNT(*) as the_count FROM `login_attempts` WHERE `ip` = ? AND `datetime` > (NOW() - INTERVAL 10 MINUTE);");
        $select->execute([$this->ip_address]);
        return $select->fetch()['the_count'];//login fails for IP in the last 10 minutes
    }

    public function attemptLogin(string $redirect_to = '')
    {
        if ($this->getRecentFailCount() >= main::FAIL_ATTEMPTS_ALLOWED) {//IP has had X or more fails in last 10 minutes
            return "IP Address has been locked for 10 minutes";
        }
        if ($this->getUserData()) {//Username found
            if ($this->checkPasswordCorrect()) {//Password is correct
                $this->doLoginSuccess();
                $this->startSession();
                $this->setSession('user', $this->uid);//Set session as uid
                $this->redirectTo($redirect_to);
            } else {//Password is wrong
                $this->addLoginFailCount();//Add 1 onto login fail count
                $this->addLoginFailAttempt();//ip and datetime into login attempt fail logs
                //return "Password is wrong for {$this->username}";//Dont use this, helps brute forcing.
                return "Failed login";//Be vague in error response
            }
        } else {
            //return "Username: {$this->username} not found in DB";
            return "Failed login";//Be vague in error response
        }
    }

    public function loginForm()
    {
        $this->pageHead('Login', 'Login to website name', '../', true, true);
        $this->tagOpen('div', 'container');
        $this->formBuilder('', 'post');
        $this->inputBuilder('text', 'username', '', 'username', 3, 24, 'username');
        $this->inputBuilder('text', 'THE_username', '', 'username', 3, 24, '', true);
        $this->tagOpen('div', 'form-group');
        $this->inputBuilder('password', 'THE_password', '', '', 8, 54, '', true);
        $this->tagClose('div');
        $this->formButtonBuilder('Login', 'LoginButton');
        $this->tagClose('form');
        $this->outputString("</div></body></html>");
    }


}