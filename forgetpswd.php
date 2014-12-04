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

define('AFFREE', 1);
include("system/config.inc.php");

$_POST = array_map('stripslashes', $_POST);
$_REQUEST = array_map('stripslashes', $_REQUEST);



foreach ($_POST as $key => $value) {
    $_POST[$key] = convert($value);
}

////////////////////////////////////////////////////////////////////////
if (isset($_POST['submit']) && $_POST['submit'] == 'Submit') {
    $varEmailAddress = '';
    $varEmailAddress = $_POST['email'];

    if ($pdo) {
        $query = "SELECT varEmail,varFirstName,varlastName FROM tblauthor WHERE varEmail = ?";
        $bind = array($varEmailAddress);
        $result1 = select_pdo($query, $bind);
    } else {

        $varEmailAddress = safeEscapeString($varEmailAddress);
        $username = "";

        $sql = "SELECT varEmail,varFirstName,varlastName FROM tblauthor WHERE varEmail = '$varEmailAddress'";
        $result1 = $d->fetch($sql);
    }

    if ($result1) {
        $varEmailAddress = stripString($result1[0]['varEmail']);
        $custName = stripString($result1[0]['varFirstName']) . " " . stripString($result1[0]['varlastName']);

        $gen_pass = rand_pass(6);
        $gen_salt = generate_salt();
        $updatefield = shadow($gen_salt . $gen_pass);

        if ($pdo) {
            $query = "UPDATE tblauthor  SET  varPassword = ?, salt= ? WHERE varEmail = ?";
            $bind = array($updatefield, $gen_salt, $varEmailAddress);
            $result = update_pdo($query, $bind);
        } else {
            $sql_upd = "UPDATE tblauthor  SET  varPassword = '" . safeEscapeString($updatefield) . "', salt= '$gen_salt' WHERE varEmail = '" . safeEscapeString($varEmailAddress) . "'";
            $result_upd = $d->exec($sql_upd);
        }
        ////////////////////////    Mail Function  //////////////////////////
        /* recipients */
        $to = stripslashes($varEmailAddress);

        /* subject */
        $sub = "Your password has been reset.";

        /* message */
        $message = '
  <html>
  <head>
  <title>Password Reminder</title>
  </head>
  <body>

  <table>
  <tr>
  <td>Dear  ' . $custName . '</td>
  </tr>
  <tr>
  <td>A request to reset your password was created.  If this was not you, please contact us at ' . $fromemail . '.</td>
  </tr>
  <tr>
  <td>Your password has been reset.</td>
  </tr>
  <tr>
  <td>Kindly, Use following username and password to access your account.</td>
  </tr>
  <tr>
  <td>Email Address : ' . $varEmailAddress . '</td>
  </tr>

  <tr>
  <td>Password : ' . $gen_pass . '</td>
  </tr>
  <tr>
  <td>Please login into the site and Change your password for security purpose.</td>
  </tr>
  <tr>
  <td>Thank you</td>
  </tr>
  <tr>
  <td>Regards</td>
  </tr>
  <tr>
  <td>Admin</td>
  </tr>
  <tr>
  <td>Get the same FREE software that powers this site at http://www.articlefriendly.com.
  Great options, and Free!
  </td>
  </tr>
  </table>
  </body>
  </html>
  ';

        /* To send HTML mail, you can set the Content-type header. */
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

        /* additional headers */

        $headers .= "From: " . $fromemail . "\r\n";




        /* and now mail it */
        mail($to, $sub, $message, $headers);
    } else {
        $_SESSION['msg'] = "Email address was not found!";
        header("location:thankyou.php");
        die();
    }
    //////////////////////////////////////////////////////////////////////////////////
    $_SESSION['msg'] = "Your new password has been sent to your email address.<br>Thank you.";
    header("location:thankyou.php");
    die();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <meta NAME="DESCRIPTION" CONTENT="">
                <meta NAME="KEYWORDS" CONTENT="">
                    <meta name="robots" content="index, follow">
                        <meta name="distribution" content="Global">
                            <meta NAME="rating" CONTENT="General">
                                <link rel="stylesheet" href="css/style.css" type="text/css" />
                                <title><?php echo $title; ?> | Pass Reset</title>
                                <script language="javascript" type="text/javascript" src="js/forgetpswd.js"></script>
                                </head>
                                <body>
                                    <div class="content">
                                        <div class="header_top"></div>
                                        <div class="header">

<?php require_once(INC . '/menu.php'); ?>

                                            <div class="sf_left">
<?php require_once(INC . '/logo.php'); ?>
                                            </div>
                                        </div>
                                        <div class="header_bottom"></div>
                                        <div class="subheader">
                                            <p><?php
include("language.php");
?></p>
                                        </div>
                                        <div class="header_top"></div>
                                        <div class="left">
                                            <div class="left_side">

                                                <?php require_once(INC . '/left.php'); ?>

                                            </div>
                                            <div class="right_side">
                                                <div class="article">
                                                    <h2>Forgotten User/Pass</h2>
                                                    <p>&nbsp;</p>
                                                    <form method="post" name="frmfgtpswd" id="frmfgtpswd" onClick="return confirmsubmit();">
                                                    <table width="100%" border="0" cellpadding="5" cellspacing="2">
                                                        <tr>
                                                            <td colspan="2">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">Enter the email address below that was used to register your Author account.. </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                            <td width="49%"><div align="right">Email:</div></td>
                                                            <td width="51%"><div align="left">
                                                                    <input name="email" type="text" id="email3">
                                                                </div></td>
                                                        </tr>
                                                        <tr><td>&nbsp;</td></tr>
                                                        <tr>
                                                            <td colspan="2"><div align="center">
                                                                    <input type="submit" name="submit" value="Submit" />
                                                                        &nbsp;</div></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">&nbsp;</td>
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
                                        <div class="header_bottom"></div>
                                        <div class="footer">

<?php require_once(INC . '/footer.php'); ?>

                                        </div>
                                    </div>
                                </body>
                                </html>
<?php
ob_end_flush();
?>