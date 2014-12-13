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
$page = 'login';
define('AFFREE', 1);
include("system/config.inc.php");

$_POST = array_map('stripslashes', $_POST);
$_REQUEST = array_map('stripslashes', $_REQUEST);

if (actionfrmcheck('action', "8") && actionfrmcheck('script', "logout")) {
    unset($_SESSION['uid']);
    unset($_SESSION['uname']);
    unset($_SESSION['email']);
    unset($_SESSION['city']);
    unset($_SESSION['country']);
    unset($_SESSION['state']);
    unset($_SESSION['url']);
    unset($_SESSION['phone']);
    unset($_SESSION['cell']);
    unset($_SESSION['memtype']);

    header("location:login.php");
    die();
}
if (actionfrmcheck('Submit', "Login")) {
// captcha check

    $session_key = stripslashes(md5($_POST['data']));
    $session_key2 = stripslashes($_SESSION['key']);

    if (!$session_key == $session_key2) {

        $_SESSION['msg'] = "<p>Your entry for the captcha code was incorrect! Please <a href='javascript:history.back()'>click here</a> to try again.</p> <p>thank you.</p>";

        unset($_SESSION['key']);
        header("location:thankyou.php");
        die();
    }
    $varLogginIP = $_SERVER['REMOTE_ADDR'];
    if ($pdo) {
        $query = "SELECT varIPNUM, txtBAN FROM tblauthor WHERE varIPNUM = ?";
        $bind = array($varLogginIP);
        $sql_IP = select_pdo($query, $bind);
    } else {
        $sql_IP = $d->fetch("SELECT varIPNUM, txtBAN FROM tblauthor WHERE varIPNUM = '" . safeEscapeString($varLogginIP) . "'");
    }

    if ($sql_IP) {
        $ban = $row[0]['txtBAN'];
        if ($ban == "Yes") {
            $_SESSION['msg'] = "Your membership has been cancelled by Admin Staff. <br>
							Please <a href='contact.php'>click here</a> to contact us if you feel this is an error.<br><br>
              Thank you,<br><br>
              Admin Staff";
            header("location:thankyou.php");
            die();
        }
    }
    $tmp_name = "";
    $tmp_name = stripslashes(trim($_REQUEST['uname']));
    $username = $tmp_name;
    $tmp_pass = "";
    $tmp_pass = stripslashes(trim($_REQUEST['pswd']));
    $password = $tmp_pass;


    $len_uname = strlen($username);
    $len_pass = strlen($password);

    if (empty($username) || empty($password)) {
        $_SESSION['msg'] = "You must enter both fields to login!";
        header("location:thankyou.php");
        die();
    }

    if ($len_uname > 35 || $len_uname < 3) {
        $_SESSION['msg'] = "Your username is too long or too short.";
        header("location:thankyou.php");
        die();
    }
    if ($len_pass > 35 || $len_pass < 3) {
        $_SESSION['msg'] = "Your password is too long or too short.";
        header("location:thankyou.php");
        die();
    }


    if ($pdo) {
        $query = "SELECT intId,varPassword,varFirstName,varlastName,salt FROM tblauthor WHERE varEmail = ? AND intStatus = ?";
        $bind = array($username, 1);
        $result = select_pdo($query, $bind);
    } else {
        $sql = "SELECT intId,varPassword,varFirstName,varlastName,salt FROM tblauthor WHERE varEmail ='" . safeEscapeString($username) . "' AND intStatus = '1'";
        $result = $d->fetch($sql);
    }

    if ($result) {

        $salt = $result[0]['salt'];

        $password = shadow($salt . $password);
        $pass_check = $result[0]['varPassword'];

        if ($password != $pass_check) {
            $_SESSION['msg'] = "Invalid username/password or you are not an active author! Please try again, or use our contact form.";
            header("location:thankyou.php");
            die();
        }

        $userid = $result[0]['intId'];
        $varFirstName = stripString($result[0]['varFirstName']);
        $varLastName = stripString($result[0]['varlastName']);


        $_SESSION['uid'] = $userid;
        $_SESSION['uname'] = $varFirstName . " " . $varLastName;
        $_SESSION['email'] = $varEmail;
        $_SESSION['city'] = $city;
        $_SESSION['country'] = $country;
        $_SESSION['state'] = $state;
        $_SESSION['url'] = $url;
        $_SESSION['phone'] = $phone;
        $_SESSION['fax'] = $fax;

        session_regenerate_id(true);
        $_SESSION['msg'] = "You are successfully Logged In.";
        header("location:thankyou.php");
        die();
    } else {
        $_SESSION['msg'] = "Invalid username/password or you are not an active author! Please try again, or use our contact form.";
        header("location:thankyou.php");
    }
}
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
            <?php echo $title; ?> | Login
        </title>
        <style type="text/css">   .capcha    {     background:url("images/captcha.png");     border:1px solid #DFE8F7;     -moz-border-radius: 12px 12px / 12px 12px;     border-radius: 12px 12px / 12px 12px;     }
        </style>
    </head>
    <body>
        <script language="javascript" src="js/left.js" type="text/javascript"></script>
        <?php
        if (!$_POST) {

            //lets use md5 to generate a totally random string
            $md5 = md5(microtime() * mktime());

            /*
              We dont need a 32 character long string so we trim it down to 5
             */
            $string = substr($md5, 0, 5);

            $_SESSION['key'] = md5($string);
        }
        ?>
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
                    <div class="article"><h2>Author Login</h2>
                        <p>&nbsp;
                        </p>
                        <form  name="frmlogin" method="post" action="" >
                            <table width="99%"  border="0" align="left" cellpadding="2" cellspacing="1">
                                <tr>
                                    <td width="29%" >
                                        <div align="right">Username
                                        </div>
                                    </td>
                                    <td width="12%">
                                        <div align="center">:
                                        </div>
                                    </td>
                                    <td width="59%">
                                        <input name="uname" type="text" id="uname" size="30" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div align="right">Password
                                        </div>
                                    </td>
                                    <td>
                                        <div align="center">:
                                        </div>
                                    </td>
                                    <td>
                                        <input name="pswd" type="password" id="pswd" size="30" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div align="right">
                                        </div>
                                    </td>
                                    <td>
                                        <div align="center">
                                        </div>
                                    </td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align="center" class="capcha" style="padding-left:5px;" width="30%">
                                        <font color="white" size="3"><b>
                                                <?php echo $string ?></b>
                                        </font>
                                    </td>
                                    <td align="left" width="69%" style="padding-left:5px;" colspan='2'>&nbsp;&nbsp;&nbsp;Enter the white text&nbsp;&nbsp;
                                        <input name="data" type="text" size="5" value="" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div align="right">
                                        </div>
                                    </td>
                                    <td>
                                        <div align="center">
                                        </div>
                                    </td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <div align="center">
                                            <input type="Submit" name="Submit" value="Login" onClick="return confirmsubmit();">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <div align="left"><br />
                                            <a href="signup.php">register here. </a> <br /> <br />
                                            <a href="forgetpswd.php">Forgot username or password.</a>
                                        </div>
                                    </td>
                                </tr>
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