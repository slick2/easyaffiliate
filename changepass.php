<?php
///////////////////// TERMS OF USE //////////////////////////
//
//  1. You must keep a link to articlefriendly.com at the bottom of at least one page on the frontend pages.
//  2. You cannot give or sell AF Free to your friends family or anyone else. Anyone that wants AF Free
//     must signup for the download at articlefriendly.com.
//  3. You may use AF Free on as many of your own sites as you wish, but not for clients or others.
//     They must signup for their own copy of AF Free also.
//  4. You may not sell or change and claim AF Free as your own.
/////////////////////////////////////////////////////////////
if (!ob_start("ob_gzhandler"))
    ob_start();

session_start();

if (!isset($_SESSION['uid']) || trim($_SESSION['uid']) == "") {
    $_SESSION['msg'] = "<p>Access Denied. Please login first!</p>";
    header("location:thankyou.php");
    die();
}

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

$_POST = array_map('stripslashes', $_POST);
$_REQUEST = array_map('stripslashes', $_REQUEST);

if (isset($_REQUEST['Submit']) && trim($_REQUEST['Submit']) == "Change Password") {
    if ($pdo) {
        $authorId = $_SESSION['uid'];
        $query = "SELECT salt, varPassword FROM tblauthor WHERE intId = ?";
        $bind = array($authorId);
        $result = select_pdo($query, $bind);
    } else {

        $authorId = safeEscapeString($_SESSION['uid']);
        $sql = "SELECT salt, varPassword FROM tblauthor WHERE intId ='$authorId'";
        $result = $d->fetch($sql);
    }

    $salt = $result[0]['salt'];
    $oldpass = stripString($result[0]['varPassword']);


    $tmp_pass = "";
    $tmp_pass = $_REQUEST['old_pass'];
    $oldpassword = shadow($salt.$tmp_pass);
    $tmp_new = "";
    $tmp_new = $_REQUEST['new_pass'];
    $newpassword = shadow($salt.$tmp_new);


    if ($oldpass != $oldpassword) {
        $_SESSION['msg'] = "You old password is not a match for updating, <br>Please go back and enter your old password.";
        header("location:thankyou.php");
        die();
    }

    if ($pdo) {
        $query = "UPDATE tblauthor SET varPassword = ? WHERE intId =?";
        $bind = array($newpassword, $authorId);
        $result = update_pdo($query, $bind);
    } else {

        $newpassword = safeEscapeString($newpassword);
        $authorId = safeEscapeString($authorId);
        $sql_upd = "UPDATE tblauthor SET varPassword = '$newpassword' WHERE intId ='$authorId'";
        $result = $d->exec($sql_upd);
    }
    $_SESSION['msg'] = "You password has been successfully updated.";
    header("location:thankyou.php");
    die();
}

// End Of UpDATE operation
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta NAME="DESCRIPTION" CONTENT="" />
        <meta NAME="KEYWORDS" CONTENT="" />
        <meta name="robots" content="index, follow" />
        <meta name="distribution" content="Global" />
        <meta NAME="rating" CONTENT="General" />
        <link rel="stylesheet" href="css/style.css" type="text/css" />
        <title>
            <?php echo $title; ?> | Change Pass
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
                    <div class="article"><h2>Change Password</h2>
                        <p>&nbsp;
                        </p>
                        <form method="post">
                            <table  border="0" cellspacing="1" cellpadding="1" class="greyborder" align="center">
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td> Old Password : </td>
                                    <td>
                                        <input name="old_pass" type="text" id="country19" value="" size="30" />
                                    </td>
                                </tr>
                                <tr>
                                    <td> New Password : </td>
                                    <td>
                                        <input name="new_pass" type="text" id="country20" value="" size="30" />
                                    </td>
                                </tr>
                                <tr>
                                    <td> Confirm Password : </td>
                                    <td>
                                        <input name="cpass" type="text" id="country21" value="" size="30" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp; </td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div align="center">
                                            <input type="submit" name="Submit" value="Change Password" onClick="return changepassword();" />
                                        </div>
                                    </td>
                            </table>
                        </form>
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
