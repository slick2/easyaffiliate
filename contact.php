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

if (isset($_POST['txtMessage'])) {
    $message = $_POST['txtMessage'];
}

if (!get_magic_quotes_gpc()) {
    $_GET = array_map('EscapeString', $_GET);
    $_POST = array_map('EscapeString', $_POST);
    $_COOKIE = array_map('EscapeString', $_COOKIE);
    $_REQUEST = array_map('EscapeString', $_REQUEST);
}
$page = 'contact';
define('AFFREE', 1);
include("system/config.inc.php");
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
        <style type="text/css">
            .capcha{
                background:url("images/captcha.png");
                border:1px solid #DFE8F7;
                -moz-border-radius: 12px 12px / 12px 12px;
                border-radius: 12px 12px / 12px 12px;
            }
        </style>
        <title>
            <?php echo $title; ?> | Contact Us
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
                    <div class="article"><h2>Contact Us</h2>
                        <p>&nbsp;
                        </p>
                        <?php
                        /**
                         * Change the email address to a different email if you wish below.
                         *
                         * $empty_fields_message and $thankyou_message can be changed
                         * if you wish.
                         */
// Change to a different email address if you wish
// Currently set to use your admin entered email
                        $your_email = $fromemail;

// This is what is displayed in the email subject line
// Change it if you want
                        $subject = "Question From $title";

// This is displayed if all the fields are not filled in
                        $empty_fields_message = "<p>Please go back and complete all the fields in the form.</p>";

// This is displayed when the email has been sent
                        $thankyou_message = "<br><br><p>Thank you. Your message has been sent to Admin and we will respond asap.</p>";

// You do not need to edit below this line

                        if (!$_POST) {

                            //lets use md5 to generate a totally random string
                            $md5 = md5(microtime() * mktime());

                            /*
                              We dont need a 32 character long string so we trim it down to 5
                             */
                            $string = substr($md5, 0, 5);

                            $_SESSION['key'] = md5($string);
                            ?>
                            <form method="post" action="contact.php">
                                <p align="center">
                                    <font size="1" color="gray">We are looking forward to hearing from you!
                                    </font>
                                </p>
                                <p align="center"><b>Your Name:</b>
                                    <br />
                                    <input type="text"  style="color:#000000" title="Enter your name" name="txtName" size="35" />
                                </p>
                                <br />
                                <p align="center"><b>Your Email:</b>
                                    <br>
                                        <input  type="text"  style="color:#000000" title="Enter your email address" name="txtEmail" size="35" />
                                </p>
                                <br />
                                <p align="center"><b>Your message:</b>
                                    <br>
                                        <textarea  cols="50" rows="15" title="Enter your message" name="txtMessage"></textarea>
                                </p>
                                <br />
                                <table width="100%">
                                    <tr>
                                        <td align="center" class="capcha" style="padding-left:5px;">
                                            <font color="white" size="3"><b><?php echo $string ?></b>
                                            </font>
                                        </td>
                                        <td align="left" style="padding-left:5px;">&nbsp;&nbsp;&nbsp;Enter the white text&nbsp;&nbsp;
                                            <input name="data" type="text" size="5" value="" />
                                        </td>
                                    </tr>
                                </table>
                                <br>
                                    <p align="center">
                                        <label title="Send your message">
                                            <input type="submit" style="color:#000000" value="Send Email" />
                                        </label>
                                    </p>
                                    <br />
                                    <input type="hidden" value="<?php echo $IP ?>" name="spammer" />
                            </form>
                            <?php
                        } else {

                            // captcha check

                            $session_key = md5($_POST['data']);
                            $session_key2 = $_SESSION['key'];

                            if ($session_key == $session_key2) {
                                $cool = "cool";
                            } else {

                                $_SESSION['msg'] = "<p>Your entry for the captcha code was incorrect! Please <a href='javascript:history.back()'>click here</a> to try again.</p> <p>thank you.</p>";

                                unset($_SESSION['key']);
                                header("location:thankyou.php");
                                die();
                            }

                            $tmp_name = "";
                            $tmp_name = stripslashes(strip_tags($_POST['txtName']));
                            $name = $tmp_name;
                            $email = $_POST['txtEmail'];


                            if (empty($name) || empty($email) || empty($message)) {

                                echo $empty_fields_message;
                                die();
                            }

                            $bad_words = array("intercourse", "preapproved", "Nigeria", "cialis", "holdem", "incest", "levitra", "paxil", "pharmacy", "ringtone", "phentermine",
                                "gay", "shemale", "slot-machine", "xanax", "vioxx", "porn", "sex", "sexy", "lolita", "fuk", "nipple", "nipples", "fist", "fucking", "fetish",
                                "bondage", "upskirt", "sexual", "tgp", "cocksucker", "cocksucking", "cheerleader", "pornography", "fuck", "suck", "piss",
                                "cunt", "pussy", "bitch", "slut", "penis", "ass", "tits", "boob", "boobs", "sucking", "licking", "milf", "oral", "titty", "vagina", "orgasm",
                                "orgasms", "hydrocodone", "ambien", "hardcore", "lesbian", "sluts", "testosterone", "naked", "erection", "chicks", "penile", "ejaculation",
                                "alertz", "blackjack", "buspar", "c0ck", "climax", "cum", "dealz", "erections", "erotic", "fda", "hardcore", "impotance", "keno", "lottery",
                                "manhood", "masturbation", "one-time", "panties", "pen1s", "peniss", "pennis", "plrp", "porn", "reklama", "removethisemail", "roulette", "s.e.x",
                                "schlong", "sexdrive", "sexual", "sildenafil", "testicle", "undressing", "v1agra", "xxx", "x.x.x x.x.x", "p0rn", "pillz", "shit", "motherfucker",
                                "horny", "mp3s", "mp3", "MP3", "ringtone", "pharmacy", "viagra", "cialis", "drugs", "gamble", "ugg", "adipex", "baccarrat", "casino", "loans",
                                "holdem", "incest", "pussy", "shemale", "shoes", "handbag", "tramadol", "keno", "lotto", "nigeria", "nigerian", "boots");
                            // check for bad words in email message and deny if found
                            $title_test = explode(" ", $message);
                            foreach ($title_test as $value) {
                                $value = strtolower($value);
                                if (in_array($value, $bad_words)) {
                                    $_SESSION['msg'] = "<center>Email was NOT accepted due to one or more forbidden word.</center>";
                                    header("location:thankyou.php");
                                    die();
                                }
                            }

                            // Stop the form being used from an external URL
                            // Get the referring URL
                            $referer = $_SERVER['HTTP_REFERER'];
                            // Get the URL of this page
                            $this_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"];
                            // If the referring URL and the URL of this page don't match then
                            // display a message and don't send the email.
                            if ($referer != $this_url) {
                                $_SESSION['msg'] = "<center>You do not have permission to use this script from another URL.</center>";
                                header("location:thankyou.php");
                                die();
                            }


                            //////////// Check for valid domain and bad charactors /////////////////

                            $message = "Message From " . $name . " || " . $email . "\r\n" . $message;

                            $pattern = "/^[\w-]+(\.[\w-]+)*@";
                            $pattern .= "([0-9a-z][0-9a-z-]*[0-9a-z]\.)+([a-z]{2,4})$/i";
                            if (preg_match($pattern, $email)) {
                                $parts = explode("@", $email);
                                if (checkdnsrr($parts[1], "MX")) {
                                    /* To send HTML mail, you can set the Content-type header. */
                                    $headers = "MIME-Version: 1.0\r\n";
                                    $headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";
                                    /* additional headers */
                                    $headers .= "Content-Transfer-Encoding: 8bit\r\n";
                                    $headers .= "From: $email\r\n";
                                    $headers .= "Repy-To: $email\r\n";
                                    $headers .= "X-Mailer: PHP" . phpversion();
                                    $headers .= "";
                                    mail($your_email, $subject, $message, $headers);
                                    echo $thankyou_message;
                                } else {
                                    $_SESSION['msg'] = "The e-mail host is not valid, so no email was sent.  Please go back and enter a valid email address.";
                                    header("location:thankyou.php");
                                    die();
                                }
                            } else {
                                $_SESSION['msg'] = "The e-mail host was not valid, so no email was sent.  Please go back and enter a valid email address.";
                                header("location:thankyou.php");
                                die();
                            }
                        }

/////////////////// END Check for valid domain and bad charactors ////////////////////
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
