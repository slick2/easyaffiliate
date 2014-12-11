<?php
///////////////////// TERMS OF USE //////////////////////////
//
//  1. You must keep the link at the bottom of at least the index.php page on the frontend.
//  2. You cannot give AF Free to your friends family or anyone else. Anyone that wants AF Free
//     must signup for the download at articlefriendly.com.
//  3. You may use AF Free on as many of your own sites as you wish, but not for clients or others.
//     They must signup for their own copy of AF Free also.
//
/////////////////////////////////////////////////////////////
if (!ob_start("ob_gzhandler"))
    ob_start();
session_start();

function EscapeString($string) {
    if (is_array($string)) {
        return array_map(__METHOD__, $string);
    }
    if (!empty($string) && is_string($string)) {
        return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $string);
    }
}

if (!get_magic_quotes_gpc()) {
    $_GET = array_map('EscapeString', $_GET);
    $_POST = array_map('EscapeString', $_POST);
    $_COOKIE = array_map('EscapeString', $_COOKIE);
    $_REQUEST = array_map('EscapeString', $_REQUEST);
}
define('AFFREE', 1);
include("system/config.inc.php");
$_GET = array_map('stripslashes', $_GET);
$_POST = array_map('stripslashes', $_POST);
$_COOKIE = array_map('stripslashes', $_COOKIE);
$_REQUEST = array_map('stripslashes', $_REQUEST);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <meta NAME="DESCRIPTION" CONTENT="" />
            <meta NAME="KEYWORDS" CONTENT="" />
            <meta name="robots" content="index, follow" />
            <meta name="distribution" content="Global" />
            <meta NAME="rating" CONTENT="General" />
            <link rel="stylesheet" href="css/style.css" type="text/css" />
            <title>
<?php echo $title; ?> | User Messages
            </title>
    </head>
    <body>
        <div class="content">
            <div class="header_top">
            </div>
            <div class="header">
<?php require_once(INC . '/menu.php'); ?>
                <div class="sf_left">
                <?php require_once(INC . '/logo.php'); ?>
                </div>
            </div>
            <div class="header_bottom">
            </div>
            <div class="subheader">
                <p>
<?php
include("language.php");
?>
                </p>
            </div>
            <div class="header_top">
            </div>
            <div class="left">
                <div class="left_side">
<?php require_once(INC . '/left.php'); ?>
                </div>
                <div class="right_side">
                    <div class="article"><h2>User Message Center</h2>
                        <p>&nbsp;
                        </p>
<?php
if (isset($_SESSION['pic'])) {
    $authName = $_SESSION['name'];
    $authEmail = $_SESSION['email'];


    $photo = $_SESSION['pic'];
    $name = $admin;
    $email = $fromemail;
// The subject ... You may change this to whatever you
// want your email subject to show
    $subject = "Photo Uploaded!";
// The message ... Change this as you wish, but remember to leave
// the \n's right where they are
    $message = "$name,\n\nJust a short note to let you know that an author, $authName uploaded this pic.
             $photo\n\n
             Auto Mailer $title";
    mail($email, $subject, $message, "From: $email");
    echo "<p>Your New Photo</p> <br><br>";
    echo "<center>" . $_SESSION['pic'] . "</center>";
    unset($_SESSION['pic']);
    unset($_SESSION['name']);
    unset($_SESSION['email']);
}

if (isset($_SESSION['msg'])) {
    echo "<p>" . $_SESSION['msg'] . "</p>";
    unset($_SESSION['msg']);
}


if (isset($_REQUEST['logout']) && $_REQUEST['logout'] == 'yes') {
    // Unset all of the session variables.
    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]
        );
    }

    session_destroy();
    echo "<p>You have been logged out. Come back soon!</p>";
}
?>
                        <!-- End index text -->
                    </div>
                    <!-- End Content Area -->
                </div>
            </div>
            <div class="right">
                        <?php require_once(INC . '/right.php'); ?>
            </div>
            <div class="header_bottom">
            </div>
            <div class="footer">
<?php require_once(INC . '/footer.php'); ?>
            </div>
        </div>
    </body>
</html>
<?php
ob_end_flush();