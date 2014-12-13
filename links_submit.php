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
    $_SESSION['msg'] = '<font color="red">Sorry, but you must have an account to add your link and be logged in!</font>';
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

// if(isset($_POST['site_desc']))
// {
// $tmp_site = $_POST['site_desc'];
// $_POST['site_desc'] = '';
// }
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
if (getenv('HTTP_CLIENT_IP')) {
    $IP = getenv('HTTP_CLIENT_IP');
} elseif (getenv('HTTP_X_FORWARDED_FOR')) {
    $IP = getenv('HTTP_X_FORWARDED_FOR');
} elseif (getenv('HTTP_X_FORWARDED')) {
    $IP = getenv('HTTP_X_FORWARDED');
} elseif (getenv('HTTP_FORWARDED_FOR')) {
    $IP = getenv('HTTP_FORWARDED_FOR');
} elseif (getenv('HTTP_FORWARDED')) {
    $IP = getenv('HTTP_FORWARDED');
} else {
    $IP = $_SERVER['REMOTE_ADDR'];
}

if ($_POST) {
    $config = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);

    /// Captcha entry check
    $session_key = md5($_POST['data']);
    $session_key2 = $_SESSION['key'];
    if (!$session_key == $session_key2) {

        $_SESSION['msg'] = "<p>Your entry for the captcha code was incorrect! Please <a href='javascript:history.back()'>click here</a> to try again.</p> <p>thank you.</p>";
        unset($_SESSION['key']);
        header("location:thankyou.php");
        die();
    }
    /// End Captcha Check

    if (empty($_POST['user_fname']) || empty($_POST['user_lname']) || empty($_POST['user_email']) || empty($_POST['site_name']) || empty($_POST['site_addy']) || empty($_POST['site_desc'])) {
        $_SESSION['msg'] = "Please fill in all the fields to submit your link.";
        header("location:thankyou.php");
        die();
    }
    if (!preg_match("/(?i)\b((?:https?://|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:'\".,<>?«»“”‘’]))/", $_POST['site_addy'])) {
        $_SESSION['msg'] = "Your site link URL is not valid. Please try again";
        header("location:thankyou.php");
        die();
    }
    $tmp_name = "";
    $tmp_name = $_POST['user_fname'];
    $fname = $purifier->purify($tmp_name);

    $tmp_lname = "";
    $tmp_lname = $_POST['user_lname'];
    $lname = $purifier->purify($tmp_lname);

    $tmp_email = $_POST['user_email'];
    $tmp_email = $purifier->purify($tmp_email);
    $e_check = check_email($tmp_email);

    if (!$e_check) {
        $_SESSION['msg'] = "<p>The email host is not valid.  Please go back and enter a valid email address.</p>";
        header("location:thankyou.php");
        die();
    } else {
        $email = $tmp_email;
    }


    $tmp_site = "";
    $tmp_site = $_POST['site_name'];
    $sitename = $purifier->purify($tmp_site);
    $sitename = $sitename;

    $tmp_addy = $_POST['site_addy'];
    $tmp2_addy = $tmp_addy;
    $siteaddy = $purifier->purify($tmp2_addy);
    $siteaddy = trim($siteaddy);
    $tmp_site = "";

    $sitedesc = $purifier->purify($tmp_site);


    $bad_words = "intercourse preapproved Nigeria cialis holdem incest levitra paxil pharmacy ringtone phentermine gay shemale slot-machine xanax vioxx
 porn sex sexy lolita fuk nipple nipples fist fucking fetish bondage upskirt sexual tgp cocksucker cocksucking cheeleader pornography fuck suck piss
 cunt pussy bitch slut penis ass tits boob boobs tits fucking sucking licking milf oral titty vagina ottawavalleyag ouendopqr ouoocarabaopqr owns1.com
 zamanuha zkjaifvjmopqr zoiuyxyiopqr zteqnopqr zulnizccopqr orgasms orgasm hydrocodone adobe ambien hardcore lesbian sluts testosterone naked erection
 chicks penile ejaculation alertz blackjack buspar c0ck climax cum dealz erections erotic erotik FDA hardcore impotance keno lottery manhood masturbation
 one-time panties pen1s peniss pennis PLRP PORN reklama removethisemail roulette s.e.x schlong sexdrive sexual SEXY sildenafil testicle undressing v1agra
 xxx x.x.x x.x.x. VIAGRA viagra medication pharmacy p0rn pillz shit piss motherfucker horny hotel hotels leeds cheap-hotels-leeds chealhotelsleeds mp3s mp3 MP3 Darian ringtone";

    $bad_submit = explode(" ", $bad_words);

    $name1_test = explode(" ", $fname);
    foreach ($bad_submit as $value) {
        if (in_array($value, $name1_test)) {

            $_SESSION['msg'] = "<center>Submission was NOT accepted!</center>";
            header("location:thankyou.php");
            die();
        }
    }

    $name2_test = explode(" ", $lname);
    foreach ($bad_submit as $value) {
        if (in_array($value, $name2_test)) {

            $_SESSION['msg'] = "<center>Submission was NOT accepted!</center>";
            header("location:thankyou.php");
            die();
        }
    }

    $title_test = explode(" ", $sitename);
    foreach ($bad_submit as $value) {
        if (in_array($value, $title_test)) {

            $_SESSION['msg'] = "<center>Submission was NOT accepted!</center>";
            header("location:thankyou.php");
            die();
        }
    }

    $tmpp = str_replace("//", "/", $siteaddy);
    $site_test = explode("/", $tmpp);
    foreach ($bad_submit as $value) {
        if (in_array($value, $site_test)) {

            $_SESSION['msg'] = "<center>Submission was NOT accepted!</center>";
            header("location:thankyou.php");
            die();
        }
    }

    $desc_test = explode(" ", $sitedesc);
    foreach ($bad_submit as $value) {
        if (in_array($value, $desc_test)) {

            $_SESSION['msg'] = "<center>Submission was NOT accepted!</center>";
            header("location:thankyou.php");
            die();
        }
    }
    if ($pdo) {
        $query = "SELECT * FROM tbllinks WHERE site_addy = ? OR site_name = ?";
        $bind = array($siteaddy, $sitename);
        $sql2 = select_pdo($query, $bind);
    } else {
        $sql2 = $d->fetch("SELECT * FROM tbllinks WHERE site_addy = '$siteaddy' OR site_name = '$sitename'");
    }
    $returned_rows = count($sql2);
    if ($returned_rows == 0) {
        if ($pdo) {
            $query = "INSERT INTO tbllinks ( fname , lname , email ,site_name,
					site_addy , site_desc, sub_date) VALUES (?, ?, ?, ?, ?, ?, NOW())";
            $bind = array($fname, $lname, $email, $sitename, $siteaddy, $sitedesc);
            $result = insert_pdo($query, $bind);
        } else {
            $sql = "INSERT INTO tbllinks ( fname , lname , email ,site_name,
							site_addy , site_desc, sub_date)
							VALUES ('" . safeEscapeString($fname) . "', '" . safeEscapeString($lname) . "', '" . safeEscapeString($email) . "', '" . safeEscapeString($sitename) . "',
              '" . safeEscapeString($siteaddy) . "', '" . safeEscapeString($sitedesc) . "', NOW())";
            $result = $d->exec($sql);
        }

        $to = $fromemail;

        /* subject */
        $subject = "New Link From $title";

        /* message */
        $message = "
						<html>
						<head>
						<title>New Link From $title</title>
						</head>
						<body>
						<table>
						<tr>
						  <td>Dear $admin,</td>
						</tr>
						<tr>
						  <td>Just a short note to let you know you have a new link request from $sitename!<br>
               They submitted the following info:<br><br>
               $fname<br>
	             $lname<br>
	             $email<br>
	             $sitename<br>
	             $siteaddy<br>
	             $sitedesc<br>
	             $IP<br>

							</td>
						</tr>
						<tr>
						  <td><p>Thank You</p><p>Regards</p><p>$title</p></td>
						</tr>
						</table>
						</body>
						</html>
						";

        /* To send HTML mail, you can set the Content-type header. */
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

        /* additional headers */

        $headers .= "From: " . $fromemail . "\r\n";
        //$headers .= "";
        //echo $message;
        //die();

        /* and now mail it */
        mail($to, $subject, $message, $headers);
    }
}
$page = "links";
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
            <?php echo $title; ?> | Terms
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
                    <div class="article"><h2>Add Your Link</h2>
                        <p>&nbsp;
                        </p>
                        <?php ?>
                        <p>
                            <font size="2">Thank you! Your link has been submitted and will be reviewed shortly.
                            </font>
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
