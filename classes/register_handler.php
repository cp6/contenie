<?php
require_once('main.php');

class registerHandler extends main
{
    private string $username;
    private string $stated_password;
    private string $email;
    private int $uid;
    private string $key;

    public function submittedValues(string $username, string $password, string $email)
    {
        $this->username = $username;
        $this->stated_password = $password;
        $this->email = $email;
    }

    public function attemptRegister(): string
    {
        if ($this->validateUsername() == 1 && $this->validatePassword() && $this->validateEmail()) {
            $this->insertAccount();
            if (self::REQUIRE_EMAIL_ACTIVATION) {
                $this->generateActivateKey();
                //$this->sendVerifyEmail();
                return "Account registered, please check for activation email";
            } else {
                $this->manualActivateAccount();
                return "Account registered and activated";
            }
        } elseif ($this->validateUsername() == 2) {
            return "Username already exists, please choose another one";
        } elseif ($this->validateUsername() == 3) {
            return "{$this->validateUsername()} Username must be between 3 and 24 characters in length";
        }
    }

    public function validateUsername(int $min = 3, int $max = 24): int
    {
        $db = $this->db_connect();
        if (strlen($this->username) >= $min && strlen($this->username) <= $max) {
            $select = $db->prepare("SELECT `uid` FROM `accounts` WHERE `username` = ? LIMIT 1;");
            $select->execute([$this->username]);
            if ($select->rowCount() > 0) {
                //Username already exists
                return 2;
            } else {
                //Can use username
                return 1;
            }
        } else {
            return 3;
        }
    }

    public function validatePassword(int $min = 8, int $max = 54): bool
    {
        if (strlen($this->stated_password) >= $min && strlen($this->stated_password) <= $max) {
            return true;
        } else {
            return false;
        }
    }

    public function validateEmail(): bool
    {
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) && strlen($this->email) <= 60) {
            return true;//Valid email address
        } else {
            return false;
        }
    }

    public function insertAccount(): void
    {
        $hashed_password = password_hash($this->stated_password, PASSWORD_DEFAULT);//Hash the submitted password
        $db = $this->db_connect();
        $insert = $db->prepare("INSERT IGNORE INTO `accounts` (`username`, `password`, `email`) VALUES (?,?,?)");
        $insert->execute([$this->username, $hashed_password, $this->email]);//Create the user you defined in the form
        $this->uid = $db->lastInsertId();
    }

    public function manualActivateAccount(): void
    {
        $db = $this->db_connect();
        $update = $db->prepare("UPDATE `accounts` SET `activated` = 1 WHERE `uid` = ? LIMIT 1;");
        $update->execute([$this->uid]);
    }

    public function generateActivateKey(): void
    {
        $this->key = substr(md5(rand()), 0, 24);
        $db = $this->db_connect();
        $insert_key = $db->prepare("INSERT IGNORE INTO `activate_keys` (`key`, `uid`) VALUES (?, ?)");
        $insert_key->execute([$this->key, $this->uid]);
    }

    public function verifyAccount(string $key): bool
    {
        $db = $this->db_connect();
        $select = $db->prepare("SELECT `uid` FROM `activate_keys` WHERE `key` = ? LIMIT 1;");
        $select->execute([$key]);
        if ($select->rowCount() > 0) {//Row found for key
            $result = $select->fetch();
            $update = $db->prepare("UPDATE `users` SET `activated` = 1 WHERE `uid` = ? LIMIT 1;");
            $update->execute([$result['uid']]);
            $delete = $db->prepare("DELETE FROM `activate_keys` WHERE `key` = ? LIMIT 1;");
            $delete->execute([$key]);
            return true;//Account activated
        } else {
            return false;//Key is invalid
        }
    }
}