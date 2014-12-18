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
if (isset($_POST['Submit']) && $_POST['Submit'] == 'Send') {
    require_once("htmlpurifier/library/HTMLPurifier.auto.php");
    $config = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);
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
    if (!isset($_POST['yourEmail']) || $_POST['yourEmail'] == '') {
        $_SESSION['msg'] = "<p>Your email is empty. Please enter a valid email address.</p>";
        header("location:thankyou.php");
        die();
    }
    if (isset($_SESSION['friend'])) {
        $_SESSION['friend'] = $_SESSION['friend'] + 1;
    }



    if ($_SESSION['friend'] > 4) {
        $_SESSION['msg'] = "Sorry, but you are limited to 3 emails per day.";
        header("location:thankyou.php");
        die();
    }

    $sub = strlen($_POST['Sub']);
    if ($sub > 100 || $sub < 2) {
        $_SESSION['msg'] = "Sorry, but your tell a friend subject is above 100 characters or below 2.";
        header("location:thankyou.php");
        die();
    }

    $main = strlen($_POST['Msg']);
    if ($main > 350 || $main < 12) {
        $_SESSION['msg'] = "Sorry, but your tell a friend message is above 350 characters or below 12.";
        header("location:thankyou.php");
        die();
    }

    $e_check = check_email($_POST['yourEmail']);
    if (!$e_check) {
        $_SESSION['msg'] = "Sorry, but your tell a friend email address for your friend is not valid. Please enter a valid email address";
        header("location:thankyou.php");
        die();
    }

    $e_check = check_email($_POST['myEmail']);
    if (!$e_check) {
        $_SESSION['msg'] = "Sorry, but your tell a friend email address is not valid. Please enter a valid email address for yourself";
        header("location:thankyou.php");
        die();
    }

    $to = "";
    $to = $purifier->purify($_POST['yourEmail']);
    $to = stripslashes(strip_tags($_POST['yourEmail']));
    $sub = "";
    $sub = $purifier->purify($_POST['Sub']);
    $sub = stripslashes(strip_tags($_POST['Sub']));
    $msg = "";
    $msg = $purifier->purify($_POST['Msg']);
    $msg = stripslashes(strip_tags($_POST['Msg']));
    $head = "";
    $head = "From: " . $purifier->purify($_POST['myEmail']);
    $head .= "Repy-To: " . $purifier->purify($_POST['myEmail']) . "\r\n";

    $bad_words = array("intercourse", "preapproved", "Nigeria", "cialis", "holdem", "incest", "levitra", "paxil", "pharmacy", "ringtone", "phentermine",
        "gay", "shemale", "slot-machine", "xanax", "vioxx", "porn", "sex", "sexy", "lolita", "fuk", "nipple", "nipples", "fist", "fucking", "fetish",
        "bondage", "upskirt", "sexual", "tgp", "cocksucker", "cocksucking", "cheerleader", "pornography", "fuck", "suck", "piss",
        "cunt", "pussy", "bitch", "slut", "penis", "ass", "tits", "boob", "boobs", "sucking", "licking", "milf", "oral", "titty", "vagina", "orgasm",
        "orgasms", "hydrocodone", "ambien", "hardcore", "lesbian", "sluts", "testosterone", "naked", "erection", "chicks", "penile", "ejaculation",
        "alertz", "blackjack", "buspar", "c0ck", "climax", "cum", "dealz", "erections", "erotic", "fda", "hardcore", "impotance", "keno", "lottery",
        "manhood", "masturbation", "one-time", "panties", "pen1s", "peniss", "pennis", "plrp", "porn", "reklama", "removethisemail", "roulette", "s.e.x",
        "schlong", "sexdrive", "sexual", "sildenafil", "testicle", "undressing", "v1agra", "xxx", "x.x.x x.x.x", "p0rn", "pillz", "shit", "motherfucker",
        "horny", "mp3s", "mp3", "MP3", "ringtone", "pharmacy", "viagra", "cialis", "drugs", "gamble", "ugg", "adipex", "baccarrat", "casino", "loans",
        "holdem", "incest", "pussy", "shemale", "shoes", "handbag", "tramadol", "keno", "lotto", "nigerian", "boots");
// test subject
    $bad_wordtest = explode(" ", $_POST['Sub']);
    foreach ($bad_words as $term) {
        if (in_array(strtolower($term), array_map('strtolower', $bad_wordtest))) {
            $bad_wordtest = '';
            $_SESSION['msg'] = "<p align='left'><font color='red'>Sorry, but your article contains forbidden word(s) of <b>" . htmlentities($term, ENT_QUOTES, "UTF-8") . "</b> and cannot be accepted at this time.  If you feel this is an error, please contact the site admin thru our contact form.</font></p>";
            header("location:submitart.php");
            die();
        }
    }
// test message
    $bad_wordtest = '';
    $bad_wordtest = explode(" ", $_POST['Msg']);
    foreach ($bad_words as $term) {
        if (in_array(strtolower($term), array_map('strtolower', $bad_wordtest))) {
            $bad_wordtest = '';
            $_SESSION['msg'] = "<p align='left'><font color='red'>Sorry, but your article contains forbidden word(s) of <b>" . htmlentities($term, ENT_QUOTES, "UTF-8") . "</b> and cannot be accepted at this time.  If you feel this is an error, please contact the site admin thru our contact form.</font></p>";
            header("location:submitart.php");
            die();
        }
    }
    $bad_wordtest = '';
    if (mail($to, $sub, $msg, $head)) {
        $_SESSION['msg'] = "Thank you <br> An Email has been sent to your friend.";
        if (!isset($_SESSION['friend'])) {
            $_SESSION['friend'] = 1;
        }
        header("location:thankyou.php");
        die();
    } else {
        $_SESSION['msg'] = "<p>Message delivery failed... please try again.</p>";
        header("location:thankyou.php");
        die();
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
            <?php echo $title; ?> | Tell a friend
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
                    <div class="article"><h2>Tell A Friend</h2>
                        <p>&nbsp;
                        </p>
                        <form name="form1" method="post" action="">
                            <table  align="left" border="0" cellpadding="5" cellspacing="2">
                                <tr align="center">
                                    <td colspan="2">  Tell your friend About
                                        <?php echo $title ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div align="right">  Your Email Address :</div>
                                    </td>
                                    <td>
                                        <div align="left">
                                            <input name="myEmail" type="text" id="myEmail" size="30">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div align="right">  Your Friends Email Address:</div>
                                    </td>
                                    <td>
                                        <div align="left">
                                            <input name="yourEmail" type="text" id="yourEmail" size="30">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div align="right">  Subject:</div>
                                    </td>
                                    <td>
                                        <div align="left">
                                            <input name="Sub" type="text" id="Sub" size="30">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top">
                                        <div align="right">  Message:
                                        </div>
                                    </td>
                                    <td>
                                        <div align="left">
                                            <textarea name="Msg" cols="30" rows="5" id="Msg">Hey you!,
                                                I found a great site which has tons of articles. Check out this article:
                                                <?php echo $details; ?>  and its at the link given below. <?php echo $site_URL; ?>
                                                best,
                                                your friend.
                                            </textarea>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div align="center">
                                            <input type="submit" name="Submit" value="Send">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>  Attempts: </td>
                                    <td>
                                        <?php echo $_SESSION['friend'] ?>
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
