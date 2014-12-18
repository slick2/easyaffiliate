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
                                                <div class="article"><h2>Confirm Account</h2>
                                                    <p>&nbsp;
                                                    </p>
                                                    <?php
                                                    $cond = "";
                                                    $cond = "false";
                                                    if (isset($_REQUEST['id']) && $_REQUEST['id'] > '') {
                                                        if ($pdo) {
                                                            $ID = stripslashes($_REQUEST['id']);
                                                            $query = "SELECT varFirstName,varlastName,website,varBio,varEmail FROM tblauthor WHERE sha1(intId) = ? AND intStatus = ?";
                                                            $bind = array($ID, 0);
                                                            $results = select_pdo($query, $bind);
                                                        } else {
                                                            $ID = safeEscapeString($_REQUEST['id']);
                                                            $sql = "SELECT varFirstName,varlastName,website,varBio,varEmail FROM tblauthor WHERE sha1(intId) = '$ID' AND intStatus = 0";
                                                            $results = $d->fetch($sql);
                                                        }

                                                        foreach ($results as $row) {
                                                            $fname = stripslashes($row['varFirstName']);
                                                            $lname = stripslashes($row['varlastName']);
                                                            $web = stripslashes($row['website']);
                                                            $bio = stripslashes($row['varBio']);
                                                            $new_email = stripslashes($row['varEmail']);
                                                        }
                                                        if (count($results) > 0) {
                                                            if ($pdo) {
                                                                $query = "UPDATE tblauthor SET intStatus = ?, dtRegisteredDate = NOW() WHERE sha1(intId) = ?";
                                                                $bind = array(1, $ID);
                                                                $res = update_pdo($query, $bind);
                                                            } else {
                                                                $sql = "UPDATE tblauthor SET intStatus = '1', dtRegisteredDate = NOW() WHERE sha1(intId) ='$ID'";
                                                                $res = $d->exec($sql);
                                                            }
                                                            if ($res) {
                                                                $cond = "true";
                                                                $to = $fromemail;

                                                                /* subject */
                                                                $subject = 'New User From ' . $title;

                                                                /* message */
                                                                $message = '
  New User From ' . $title . '
  Dear ' . $admin . ',
  Just a short note to let you know a new author,  ' . $fname . '  ' . $lname . '  has successfully signed up!
  Bio : ' . $bio . '
  Website : ' . $web . '
  Regards auto mailer';

                                                                /* To send HTML mail, you can set the Content-type header. */
                                                                $headers = "MIME-Version: 1.0\r\n";
                                                                $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

                                                                /* additional headers */

                                                                $headers = "From: $fromemail \r\n";
                                                                $headers .= "Repy-To: $fromemail\r\n";
                                                                //$headers .= "";
                                                                //echo $message;
                                                                //die();

                                                                /* and now mail it */
                                                                mail($to, $subject, $message, $headers);


                                                                /// Welcome email sent to author after confirmation

                                                                /* subject */
                                                                $subject = 'Welcome to ' . $title;

                                                                /* message */
                                                                $message = '
  Dear ' . $fname . ',
  Just a short note to welcome you to our community!  Once you have signed into your new author account,
  you will see in the lower right nav bar a list of options for your account.  If you have any questions,
  please feel free to ask us and we will help you asap.

  Thank you,

  admin
  ' . $title . '

  #######################################################
  Get the same script we are using for FREE at:
  http://www.articlefriendly.com.
  You can also get AF Pro or Ultimate starting at $29.99!
  #######################################################';

                                                                /* To send HTML mail, you can set the Content-type header. */
                                                                $headers = "MIME-Version: 1.0\r\n";
                                                                $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

                                                                /* additional headers */

                                                                $headers .= "From: $fromemail \r\n";
                                                                $headers .= "Repy-To: $fromemail\r\n";
                                                                //$headers .= "";
                                                                //echo $message;
                                                                //die();

                                                                /* and now mail it */
                                                                mail($new_email, $subject, $message, $headers);
                                                            }
                                                        } else {
                                                            $cond = "false";
                                                        }
                                                    } else {
                                                        $cond = "invalid";
                                                    }
                                                    ?>
                                                    <p>
                                                        <?php
                                                        if ($cond == "true") {
                                                            echo "Welcome back!<br><br>Your Account as been activated!<br><br>Please look in the right nav bar for your author cpanel, where you can view/edit or
                                                        delete your submitted articles.";
                                                            ?>
                                                            <a href="login.php">Click Here</a> to log in!
                                                            <?php
                                                        } elseif ($cond == "false") {
                                                            echo "<font color='red'>Error in the activation of your account. Please <a href='contact.php'>Click here</a> to contact our support staff!</font>";
                                                        } else {
                                                            echo "<font color='red'>You are trying to Access An Invalid Page</font>";
                                                        }
                                                        ?>
                                                    </p>
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
                                ?>