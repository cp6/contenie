<?php

class main
{
    public function __construct(bool $show_errors = true)
    {
        if ($show_errors) {
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
            $db_user = 'select_only';
            $db_password = '';
        } else {
            $db_user = 'root';
            $db_password = '';
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

    public function pageHead(string $title, string $desc, string $depth = '../', bool $bs_css = true, bool $cus_css = true, bool $bs_js = false, bool $jq_js = false)
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
        $this->tagClose('head');
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

    public function formButtonBuilder(string $text = 'Update', string $class = 'btn btn-primary')
    {
        $this->outputString("<button type='submit' class='$class'>$text</button>");
    }

}