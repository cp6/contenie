<?php
require_once('../config.php');

class main extends config
{
    public function __construct()
    {
        $this->startSession();
        if (self::SHOW_ERRORS) {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 0);
            ini_set('display_startup_errors', 0);
        }
    }

    public function db_connect(bool $select_only = false, string $db_host = '127.0.0.1'): object
    {
        if ($select_only) {
            $db_user = self::SELECT_DB_USER;
            $db_password = self::SELECT_DB_PASSWORD;
        } else {
            $db_user = self::DB_USER;
            $db_password = self::DB_PASSWORD;
        }
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
        return new PDO("mysql:host=$db_host;dbname=contenie;charset=utf8mb4", $db_user, $db_password, $options);
    }

    public function outputString(string $string)
    {
        echo $string;
    }

    public function tagOpen(string $tag, string $class = '', string $id = '', string $href = '')
    {
        if (empty($class) && empty($id) && empty($href)) {
            $this->outputString("<$tag>");
        } elseif (empty($class) && !empty($id) && empty($href)) {
            $this->outputString("<$tag id='$id'>");
        } elseif (!empty($class) && empty($id) && empty($href)) {
            $this->outputString("<$tag class='$class'>");
        } elseif (empty($class) && empty($id) && !empty($href)) {
            $this->outputString("<$tag href='$href'>");
        } elseif (!empty($class) && empty($id) && !empty($href)) {
            $this->outputString("<$tag class='$class' href='$href'>");
        } elseif (!empty($class) && !empty($id) && !empty($href)) {
            $this->outputString("<$tag class='$class' id='$id' href='$href'>");
        } else {
            $this->outputString("<$tag class='$class' id='$id'>");
        }
    }

    public function tagClose(string $tag, int $amount = 1)
    {
        for ($i = 1; $i <= $amount; $i++) {
            $this->outputString("</$tag>");
        }
    }

    public function cssBootstrap(string $depth = '../')
    {
        $this->outputString('<link rel="stylesheet" href="' . $depth . 'assets/css/bootstrap.min.css"/>');
    }

    public function cssCustom(string $depth = '../')
    {
        $this->outputString('<link rel="stylesheet" href="' . $depth . 'assets/css/custom.min.css"/>');
    }

    public function jsJquery(string $depth = '../')
    {
        $this->outputString('<script src="' . $depth . 'assets/js/jquery.slim.min.js"></script>');
    }

    public function jsBootstrap(string $depth = '../')
    {
        $this->outputString('<script src="' . $depth . 'assets/js/bootstrap.min.js"></script>');
    }

    public function issetCheck(string $type, string $value): bool
    {
        if ($type == 'GET') {
            if (isset($_GET['' . $value . ''])) {
                return true;
            } else {
                return false;
            }
        } elseif ($type == 'POST') {
            if (isset($_POST['' . $value . ''])) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function redirectTo($redirect_to = '../index.php')
    {
        header("Location: $redirect_to");
        die();
    }

    public function startSession()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function setSession(string $session, $value)
    {
        $this->startSession();//Start session if none
        $_SESSION['' . $session . ''] = $value;
    }

    public function killSession()
    {
        session_destroy();
    }

    public function unsetSession(string $session)
    {
        unset($_SESSION['' . $session . '']);
    }

    public function updateLoggedOut()
    {
        $db = $this->db_connect();
        $update = $db->prepare("UPDATE `accounts` SET `logged_out` = NOW() WHERE `uid` = ? LIMIT 1;");
        $update->execute([$_SESSION['user']]);
    }

    public function logout(string $redirect_to = '../index.php')
    {
        $this->updateLoggedOut();
        $this->killSession();
        $this->unsetSession('user');
        $this->redirectTo($redirect_to);
    }

    public function convertBytes(int $bytes, string $convert_to = 'KB', bool $format = true, int $decimals = 2): float
    {
        if ($convert_to == 'KB') {
            $value = ($bytes / 1024);
        } elseif ($convert_to == 'MB') {
            $value = ($bytes / 1048576);
        } elseif ($convert_to == 'GB') {
            $value = ($bytes / 1073741824);
        } elseif ($convert_to == 'TB') {
            $value = ($bytes / 1099511627776);
        } else {
            $value = $bytes;
        }
        if ($format) $value = number_format($value, $decimals);
        return $value;
    }

    /* FORM STUFF */

    public function formBuilder(string $action, string $method = 'post', string $id = '')
    {
        if (empty($id)) {
            $this->outputString("<form action='$action' method='$method'>");
        } else {
            $this->outputString("<form action='$action' method='$method' id='$id'>");
        }
    }

    public function inputBuilder(string $type, string $name_id, string $value = '', string $placeholder = '', int $min = 0, int $max = 9999, string $class = '', bool $required = false)
    {
        if ($required) {
            $req = 'required';
        } else {
            $req = '';
        }
        $this->outputString("<input type='$type' class='form-control $class' name='$name_id' id='$name_id' ");
        if ($value == '' && $placeholder != '') {
            if ($type == 'number') {
                $this->outputString("placeholder='$placeholder' min='$min' max='$max' $req>");
            } else {
                $this->outputString("placeholder='$placeholder' minlength='$min' maxlength='$max' $req>");
            }
        } elseif ($value != '' && $placeholder == '') {
            if ($type == 'number') {
                $this->outputString("value='$value' min='$min' max='$max' $req>");
            } else {
                $this->outputString("value='$value' minlength='$min' maxlength='$max' $req>");
            }
        } elseif ($value == '' && $placeholder == '') {
            if ($type == 'number') {
                $this->outputString("min='$min' max='$max' $req>");
            } else {
                $this->outputString("minlength='$min' maxlength='$max' $req>");
            }
        } else {
            if ($type == 'number') {
                $this->outputString("placeholder='$placeholder' value='$value' min='$min' max='$max' $req>");
            } else {
                $this->outputString("placeholder='$placeholder' value='$value' minlength='$min' maxlength='$max' $req>");
            }
        }
    }

    public function labelBuilder(string $for, string $label)
    {
        $this->outputString("<label for='$for'>$label</label>");
    }

    public function selectOption(string $text, $value, bool $selected = false)
    {
        ($selected) ? $sel = 'selected' : $sel = '';
        $this->outputString("<option value='$value' $sel>$text</option>");
    }

    public function formButtonBuilder(string $text = 'Update', string $name = '', string $class = 'btn btn-primary')
    {
        if (empty($name)) {
            $n = '';
        } else {
            $n = "name='$name'";
        }
        $this->outputString("<button type='submit' class='$class' $n>$text</button>");
    }

    /* HTML stuff */

    public function pageHead(string $title, string $desc, string $depth = '../', bool $bs_css = true, bool $cus_css = true, bool $bs_js = false, bool $jq_js = false, string $style = '')
    {
        $this->outputString('<!DOCTYPE html>');
        $this->outputString('<html lang="en">');
        $this->tagOpen('head');
        $this->outputString("<title>$title</title>");
        $this->outputString('<meta name="viewport" content="width=device-width, initial-scale=1">');
        $this->outputString('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">');
        $this->outputString('<meta http-equiv="X-UA-Compatible" content="IE=edge">');
        $this->outputString('<meta name="description" content="' . $desc . '">');
        if ($bs_css)
            $this->cssBootstrap($depth);
        if ($cus_css)
            $this->cssCustom($depth);
        if ($jq_js)
            $this->jsJquery($depth);
        if ($bs_js)
            $this->jsBootstrap($depth);
        if (!empty($style)) {
            $this->outputString("<style>$style</style>");
        }
        $this->tagClose('head');
    }

    public function closeBodyHtml(string $depth = '../', bool $bs_js = false, bool $jq_js = false)
    {
        if ($jq_js)
            $this->jsJquery($depth);
        if ($bs_js)
            $this->jsBootstrap($depth);
        $this->outputString('</body></html>');
    }

    public function navBar(string $location, int $level = 2)
    {
        $dir_level = $this->dirLevel($level);
        $this->outputString('<nav class="navbar navbar-expand-lg navbar-dark bg-dark rounded">');
        $this->outputString('<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false">');
        $this->outputString('<span class="navbar-toggler-icon"></span></button>');
        $this->outputString('<div class="collapse navbar-collapse justify-content-md-center" id="navbar">');
        $this->outputString('<ul class="navbar-nav">');
        $this->outputString('<li class="nav-item active">');
        $this->outputString('<a class="nav-link" href="' . $dir_level . 'index.php">Home</a></li>');
        $this->outputString('<li class="nav-item dropdown">');
        $this->outputString('<a class="nav-link dropdown-toggle" href="https://' . self::WEBSITE_DOMAIN . '" id="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Menu</a>');
        $this->navMenuItems($location, $level);
        $this->outputString('</li></ul></div></nav>');
        $this->outputString('');
    }

    public function navMenuItems(string $location, int $level = 2)
    {
        $dir_level = $this->dirLevel($level);
        $this->outputString("<div class='dropdown-menu' aria-labelledby='dropdown_main'>");
        $this->outputString("<a class='dropdown-item' href='#'>Home</a>");
        $this->outputString("<div class='dropdown-divider'></div>");
        if ($location != 'account') {
            $this->outputString("<a class='dropdown-item' href='{$dir_level}account/'>Account</a>");
        }
        $this->outputString("<div class='dropdown-divider'></div>");
        $this->outputString("<a class='dropdown-item' href='{$dir_level}logout/'>Logout</a>");
        $this->outputString("</div>");
    }

    public function dirLevel(int $level): string
    {
        if ($level == 2) {
            $dir_level = '../';
        } elseif ($level == 3) {
            $dir_level = '../../';
        } else {
            $dir_level = '';
        }
        return $dir_level;
    }

}