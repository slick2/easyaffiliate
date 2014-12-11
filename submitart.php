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

if (!isset($_SESSION['uid']) || trim($_SESSION['uid']) == "") {
    header("location:login.php");
}

// CREATE A RANDOM KEY
function randomPrefix($length) {
    $random = "";

    srand((double) microtime() * 1000000);

    $data = "AbcDE123IJKLMN67QRSTUVWXYZ";
    $data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
    $data .= "0FGH45OP89";

    for ($i = 0; $i < $length; $i++) {
        $random .= substr($data, (rand() % (strlen($data))), 1);
    }

    return $random;
}

$blocker = randomPrefix(8);
$blocker = sha1($blocker);

if (empty($_POST['blocks'])) {
    $_SESSION['block'] = $blocker;
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
if (isset($_POST['f_artsummary'])) {
    if (stristr($_POST['f_artsummary'], '\n') === FALSE) {
        $_POST['f_artsummary'] = nl2br($_POST['f_artsummary']);
        $_POST['f_artbody'] = nl2br($_POST['f_artbody']);
        $_POST['f_artres'] = nl2br($_POST['f_artres']);
    } else {
        $_POST['f_artsummary'] = n2br($_POST['f_artsummary']);
        $_POST['f_artbody'] = n2br($_POST['f_artbody']);
        $_POST['f_artres'] = n2br($_POST['f_artres']);
    }
}
$_GET = array_map('stripslashes', $_GET);
$_POST = array_map('stripslashes', $_POST);
$_COOKIE = array_map('stripslashes', $_COOKIE);
$_REQUEST = array_map('stripslashes', $_REQUEST);


require_once("htmlpurifier/library/HTMLPurifier.auto.php");
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
if (isset($_POST['Submit']) && trim($_POST['Submit']) == "Submit") {
    $block_check = trim($_POST['blocks']);
    $blocked = $_SESSION['block'];

    if ($block_check != $blocked) {
        die("Your session is invalid. Please login!");
    }
    unset($_SESSION['block']);
    
    $userid = $_SESSION['uid'];
    if (trim($_POST['author']) != trim($userid)) {
        $_SESSION['msg'] = "<p>Mismatched User ID! Please re-login.</p>";
        session_regenerate_id(true);
        header("location:thankyou.php");
        die;
    }
    foreach ($_POST as $key => $value) {
        $_POST[$key] = convert($value);
    }
    // check for HTML in the title, summary or body fields
    $title_check = $_POST['f_arttitle'];
    $title_clean = strip_tags($title_check);

    if ($title_clean != $title_check) {
        $_SESSION['msg'] = "Html is not allowed in the article you attempted to submit.  Please remove any html, javascript or php tags and try again.";
        header("location:thankyou.php");
        die;
    }
    $sum_check = $_POST['f_artsummary'];
    $sum_clean = strip_tags($sum_check);

    if ($sum_clean != $sum_check) {
        $_SESSION['msg'] = "Html is not allowed in the article you attempted to submit.  Please remove any html, javascript or php tags and try again.";
        header("location:thankyou.php");
        die;
    }
    $body_check = $_POST['f_artbody'];
    $body_clean = strip_tags($body_check);

    if ($body_clean != $body_check) {
        $_SESSION['msg'] = "Html is not allowed in the article you attempted to submit.  Please remove any html, javascript or php tags and try again.";
        header("location:thankyou.php");
        die;
    }

    // check that category submitted actually exists in the database


    if (isset($_POST['parentId']) && is_numeric($_POST['parentId'])) {
        $check = sanitize_paranoid_string($_POST['parentId']);
        $check = (int) $check;
        if ($pdo) {
            $query = "SELECT intID FROM tblcategories WHERE intID = ?";
            $bind = array($check);
            $RsC = select_pdo($query, $bind);
        } else {
            $check = safeEscapeString($check);
            $query_RsC = "SELECT intID FROM tblcategories WHERE intID = '" . $check . "'";
            $RsC = $d->fetch($query_RsC);
        }
        if (!$RsC) {
            $_SESSION['msg'] = "<p>You have entered a category number outside the categories on this site. Insert Denied.</p>";
            header("location:thankyou.php");
            die();
        }
    }
    $tmp_author = "";
    $author = "";
    $tmp_cat = "";
    $cat = "";
    $tmp_title = "";
    $f_arttitle = "";
    $tmp_sum = "";
    $f_artsummary = "";
    $tmp_body = "";
    $f_artbody = "";
    $f_artres = "";
    $tmp_key = "";
    $f_artkey = "";
    $total = "";
    $over = "";
    $under = "";
    $error = "";

    if (!isset($_POST['author']) || $_POST['author'] == '') {
        $_SESSION['msg'] = "<p>Author Name Missing! Please enter an author name.</p>";
        header("location:thankyou.php");
        die();
    }
    $tmp_author = $_POST['author'];
    $tmp_author = $purifier->purify($tmp_author);
    $author = $tmp_author;
    if (!isset($_POST['parentId']) || $_POST['parentId'] == '') {
        $_SESSION['msg'] = "<p>Category Missing! Please choose a category name.</p>";
        header("location:thankyou.php");
        die();
    }
    $cat = $check;

    if (!isset($_POST['f_arttitle']) || $_POST['f_arttitle'] == '') {
        $_SESSION['msg'] = "<p>No Category Title! Please enter an article title.</p>";
        header("location:thankyou.php");
        die();
    }
    $tmp_title = convert($_POST['f_arttitle']);
    $tmp_title = $purifier->purify($tmp_title);
    $f_arttitle = convert($tmp_title);

    if (!isset($_POST['f_artsummary']) || $_POST['f_artsummary'] == '') {
        $_SESSION['msg'] = "<p>No Summary! Please enter an article summary.</p>";
        header("location:thankyou.php");
        die();
    }

    $tmp_sum = convert($_POST['f_artsummary']);
    $tmp_sum = $purifier->purify($tmp_sum);
    $f_artsummary = $tmp_sum;

    if (!isset($_POST['f_artbody']) || $_POST['f_artbody'] == '') {
        $_SESSION['msg'] = "<p>No Body Text! Please enter an article body text.</p>";
        header("location:thankyou.php");
        die();
    }

    $tmp_body = convert($_POST['f_artbody']);
    $tmp_body = $purifier->purify($tmp_body);
    $f_artbody = $tmp_body;
    if (!isset($_POST['f_artres']) || $_POST['f_artres'] == '') {
        $_SESSION['msg'] = "<p>No Author Resource! Please enter an article author resource.</p>";
        header("location:thankyou.php");
        die();
    }

    $f_artres = $purifier->purify($_POST['f_artres']);
    $f_artres = $f_artres;

    if (!isset($_POST['f_artkey']) || $_POST['f_artkey'] == '') {
        $_SESSION['msg'] = "<p>No Article Keywords! Please enter article keywords.</p>";
        header("location:thankyou.php");
        die();
    }

    $tmp_key = convert($_POST['f_artkey']);
    $tmp_key = $purifier->purify($tmp_key);
    $f_artkey = $tmp_key;
    $total = $_POST['avachars_title'] + $_POST['avachars_body'] + $_POST['avachars_res'];

    $over = $total - $uplimit;
    $under = $total - $downlimit;
    $error = "false";

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
// test article body
    $bad_wordtest = explode(" ", $f_artbody);

    foreach ($bad_words as $term) {
        if (in_array(strtolower($term), array_map('strtolower', $bad_wordtest))) {
            $bad_wordtest = '';
            $_SESSION['msg'] = "<p align='left'><font color='red'>Sorry, but your article contains forbidden word(s) of <b>" . htmlentities($term, ENT_QUOTES, "UTF-8") . "</b> and cannot be accepted at this time.  If you feel this is an error, please contact the site admin thru our contact form.</font></p>";
            header("location:submitart.php");
            die();
        }
    }
    $bad_wordtest = '';
// test summary
    $bad_sumtest = explode(" ", $f_artres);
    foreach ($bad_words as $term) {
        if (in_array(strtolower($term), array_map('strtolower', $bad_sumtest))) {
            $bad_sumtest = '';
            $_SESSION['msg'] = "<p align='left'><font color='red'>Sorry, but your article contains forbidden word(s) of <b>" . htmlentities($term, ENT_QUOTES, "UTF-8") . "</b> and cannot be accepted at this time.  If you feel this is an error, please contact the site admin thru our contact form.</font></p>";
            header("location:submitart.php");
            die();
        }
    }
    $bad_wordtest = '';
    $bad_sumtest = '';
    $bad_words = '';

    if ($total > $uplimit) {
        $error = "true";
        $_SESSION['msg'] = "<p><br><br>Your article has exceeded our maximum word count of <font color='red'>" . $uplimit . "</font> words.  The total wordcount of your article
     is at <font color='red'>" . $total . "</font> which includes your title, body and resource box.  Please edit your article by <font color='red'>" . $over . "</font> words and resubmit.<br><br>Thank you,<br><br>Admin.</p>";
        header("location:thankyou.php");
    } elseif ($total < $downlimit) {
        $error = "true";
        $_SESSION['msg'] = "<p><br><br>Your article is too short!  Please Make sure it is above " . $downlimit . ".<br><br>Admin.</p>";
        header("location:thankyou.php");
    } else {
        if ($pdo) {
            $query = "INSERT INTO tblarticles (intAuthorId,intCategory,varArticleTitle ,textSummary,
										textArticleText,textResource,varKeywords,ttSubmitDate,word_count)
							VALUES (?, ?, ?, ?,
									?, ?, ?, NOW(), ?)";
            $bind = array($author, $cat, $f_arttitle, $f_artsummary, $f_artbody, $f_artres, $f_artkey, $total);
            $result = insert_pdo($query, $bind);
        } else {
            $sql = "INSERT INTO tblarticles (intAuthorId,intCategory,varArticleTitle ,textSummary,
					textArticleText,textResource,varKeywords,ttSubmitDate,word_count)
				 VALUES ('" . safeEscapeString($author) . "', '" . safeEscapeString($cat) . "', '" . safeEscapeString($f_arttitle) . "', '" . safeEscapeString($f_artsummary) . "',
				'" . safeEscapeString($f_artbody) . "', '" . safeEscapeString($f_artres) . "', '" . safeEscapeString($f_artkey) . "', NOW(), '" . safeEscapeString($total) . "')";
            $result = $d->exec($sql);
        }

        $_SESSION['msg'] = "<p>Your article has been added. You may edit it through your author cpanel in the lower right nav bar!<br> Thank you</p>";
        header("location:thankyou.php");
    }
}
?>
<?php
$msg = "";

function Getcatiddel($ParentID, $num, $selected, $db, $check) {
    $value = $ParentID;
    $c = "";
    //caching object
    $d = new db(0);
    if ($pdo) {
        $query = "SELECT * FROM tblcategories WHERE intParentID = ? ORDER BY varCategory ASC";
        $bind = array($ParentID);
        $RsC = select_pdo($query, $bind);
    } else {
        $query_RsC = "SELECT * FROM tblcategories WHERE intParentID = " . safeEscapeString($ParentID) . " ORDER BY varCategory ASC";
        $RsC = $d->fetch($query_RsC);
    }
    $cnt = count($RsC);

    if ($RsC) {
        for ($i = 0; $i < $cnt; $i++) {
            $value = $value . "," . Getcatiddel($RsC[$i]['intID'], $num + 1, $selected, $obj_db, $check);
        }
    }
    return $value;
}

function GetChild($ParentID, $num, $selected, $pdo, $check) {
    if (isset($_SESSION['pdo']) && $_SESSION['pdo'] == true) {
        $pdo = true;
    } else {
        $pdo = false;
    }
    $value = "";
    $c = "";

    $d = new db(0);

    for ($i = 0; $i < $num; $i++) {
        $c = $c . "&nbsp;&nbsp;-&nbsp;&nbsp;";
    }

    if ($pdo) {
        $query = "SELECT intID,varCategory FROM tblcategories WHERE intParentID = ? ORDER BY varCategory ASC";
        $bind = array($ParentID);
        $RsC = select_pdo($query, $bind);
    } else {
        $ParentID = safeEscapeString($ParentID);
        $query_RsC = "SELECT intID,varCategory FROM tblcategories WHERE intParentID = " . $ParentID . " ORDER BY varCategory ASC";
        $RsC = $d->fetch($query_RsC);
    }

    $cnt = count($RsC);

    if ($RsC) {
        for ($i = 0; $i < $cnt; $i++) {
            if ($check == 1) {
                if ($selected == $RsC[$i]['intID']) {
                    $value = $value . "<option value='" . $RsC[$i]['intID'] . "' selected>" . $c . stripString($RsC[$i]['varCategory']) . "</option>";
                } else {
                    $value = $value . "<option value='" . $RsC[$i]['intID'] . "' >" . $c . stripString($RsC[$i]['varCategory']) . "</option>";
                }
            }

            $value = $value . "" . GetChild($RsC[$i]['intID'], $num + 1, $selected, $pdo, $check);
        }
    }
    return $value;
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
<?php echo $title; ?> | Submit Article
        </title>
        <style type="text/css">
            .capcha    {
                background:url("images/captcha.png");
            }
        </style>
        <script language="javascript">
            function cnt(w, x) {
                var y = w.value;
                var r = 0;
                a = y.replace('\n', ' ');
                a = a.split(' ');
                for (z = 0; z < a.length; z++) {
                    if (a[z].length > 0)
                        r++;
                }
                x.value = r;
            }
            function confirmsubmit()
            {
                var condition = true;
                if (document.frmsubart.author.value.length == 0)
                {
                    alert("Please Select Author.");
                    if (condition == true)
                    {
                        document.frmsubart.author.focus();
                    }
                    condition = false;
                    return false;
                }

                if (document.frmsubart.parentId.value == 0)
                {
                    alert("Please Select A Catagory.");
                    if (condition == true)
                    {
                        document.frmsubart.parentId.focus();
                    }
                    condition = false;
                    return false;
                }

                if (document.frmsubart.f_arttitle.value.length == 0)
                {
                    alert("Please enter Article Title.");
                    if (condition == true)
                    {
                        document.frmsubart.f_arttitle.focus();
                    }
                    condition = false;
                    return false;
                }
                if (document.frmsubart.f_artsummary.value.length == 0)
                {
                    alert("Please enter Article Summery.");
                    if (condition == true)
                    {
                        document.frmsubart.f_artsummary.focus();
                    }
                    condition = false;
                    return false;
                }
                if (document.frmsubart.f_artbody.value.length == 0)
                {
                    alert("Please enter Article Body.");
                    if (condition == true)
                    {
                        document.frmsubart.f_artbody.focus();
                    }
                    condition = false;
                    return false;
                }
                if (document.frmsubart.f_artres.value.length == 0)
                {
                    alert("Please enter Resource Box Text.");
                    if (condition == true)
                    {
                        document.frmsubart.f_artres.focus();
                    }
                    condition = false;
                    return false;
                }
                if (document.frmsubart.f_artkey.value.length == 0)
                {
                    alert("Please enter Keyword.");
                    if (condition == true)
                    {
                        document.frmsubart.f_artkey.focus();
                    }
                    condition = false;
                    return false;
                }

            }
            function popup(path, winname, W, H, M, S, b, f)
            {
                var brd = b || 0;
                var tsz = 20;
                f = f || 0;
                var c = (document.all && navigator.userAgent.indexOf("Win") != -1) ? 1 : 0
                var w = window.screen.width / 2;
                var h = window.screen.height / 2;
                var W = W || w;
                W = (typeof (W) == 'string' ? Math.ceil(parseInt(W) * w / 100) : W);
                W += (brd * 2 + 2) * c;
                W += f;
                var H = H || h;
                H = (typeof (H) == 'string' ? Math.ceil(parseInt(H) * h / 100) : H);
                H += (tsz + brd + 2) * c;
                H += f;
                var X = X || Math.ceil((w - W / 2))
                var Y = Y || Math.ceil((h - H / 2))
                //alert(' w=' + w + ' W=' + W + ' h=' + h + ' H=' +H+  ' X=' + X + ' Y=' + Y );
                //  window.open(path, winname, "left="+X+",top="+Y+",width="+W+",height="+H+",menubar="+M+",toolbar=0,scrollbars="+S+",resizable=0");
                var hPopup = window.open(path, null, "left=" + X + ",top=" + Y + ",width=" + W + ",height=" + H + ",menubar=" + M + ",toolbar=0,scrollbars=" + S + ",resizable=0");
                hPopup.focus();
            }
            function preview()
            {
                popup('preview.php', 'preview', 650, 500, 0, 1);
            }
            function getAuthor()
            {
                f = document.frmsubart;
                return f.author.options[f.author.selectedIndex].text;
            }
            function getTitle()
            {
                f = document.frmsubart;
                return f.f_arttitle.value;
            }
            function getContent()
            {
                f = document.frmsubart;
                return f.f_artbody.value;
            }
            function getResources()
            {
                f = document.frmsubart;
                return f.f_artres.value;
            }
        </script>
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
                    <div class="article"><h2>Submit Article</h2>
                        <p>&nbsp;
                        </p>
                        <form name="frmsubart" method="post" action="">

                            <div align='left'>
                                <table width="99%" border="0" align="left" cellpadding="5" cellspacing="2">
                                    <tr>
                                        <td>Author : </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select size="1" name="author">
                                                <option value="<? echo $_SESSION['uid'];?>">
<?php echo $_SESSION['uname']; ?>
                                                </option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Select a Catagory : </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select name="parentId" id="parentId">
                                                <option value="0">Select Category
                                                </option>
                                                <?php {
                                                    $selected = 0;
                                                }
                                                echo GetChild(0, 0, $selected, $db, 1);
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Article Title: </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" name="f_arttitle" size="70"  value=""  onchange="cnt(this, document.frmsubart.avachars_title)" onkeyup="cnt(this, document.frmsubart.avachars_title)" />
                                            <br />
                                            <input readonly type="text" name="avachars_title" size="2" maxlength="3" onchange="cnt(document.frmsubart.f_arttitle, this)" onkeyup="cnt(document.frmsubart.f_arttitle, this)" />
                                            <span class="redInst">Characters left
                                            </span>
                                            <input type="hidden" name="title" value="avachars_title" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td >&nbsp; </td>
                                    </tr>
                                    <tr>
                                        <td> Article Summary: (This will be displayed in our listings) </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <textarea rows="5" name="f_artsummary" cols="55"  onchange="cnt(this, document.frmsubart.avachars_sum)" onkeyup="cnt(this, document.frmsubart.avachars_sum)"></textarea>
                                            <br />
                                            <input readonly type="text" name="avachars_sum" size="3" maxlength="4" onchange="cnt(document.frmsubart.f_artsummary, this)" onkeyup="cnt(document.frmsubart.f_artsummary, this)" />
                                            <span class="redInst">Characters left</span>
                                            <input type="hidden" name="sum" value="avachars_sum" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td >&nbsp; </td>
                                    </tr>
                                    <tr>
                                        <td>Article Body: </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <textarea rows="15" name="f_artbody" cols="55"  onchange="cnt(this, document.frmsubart.avachars_body)" onkeyup="cnt(this, document.frmsubart.avachars_body)"></textarea>
                                            <br />
                                            <input readonly type="text" name="avachars_body" size="3" maxlength="4" onchange="cnt(document.frmsubart.f_artbody)" onkeyup="cnt(document.frmsubart.f_artbody)" />
                                            <span class="redInst">Characters left</span>
                                            <input type="hidden" name="body" value="avachars_body" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>Resource Box:</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <textarea rows="5" name="f_artres" cols="55"  onchange="cnt(this, document.frmsubart.avachars_res)" onkeyup="cnt(this, document.frmsubart.avachars_res)"></textarea>
                                            <br />
                                            <input readonly type="text" name="avachars_res" size="3" maxlength="4" onchange="cnt(this, document.frmsubart.f_artres)" />
                                            <span class="redInst">Characters left</span>
                                            <input type="hidden" name="res" value="avachars_res" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>Keywords:</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" name="f_artkey" size="55" value=""  onchange="cnt(this, document.frmsubart.avachars_key)" onkeyup="cnt(this, document.frmsubart.avachars_key)" />
                                            <br />
                                            <input readonly type="text" name="avachars_key" size="2" maxlength="3" onchange="cnt(this, document.frmsubart.f_artkey)" onkeyup="cnt(this, document.frmsubart.f_artkey)" />
                                            <span class="redInst">Characters left</span>
                                            <input type="hidden" name="key" value="avachars_key" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type='hidden' name='blocks' value="<?php echo $blocker ?>" />
                                            <input type="button" name="Button" value="Preview" onclick="preview();" />
                                            <input type="submit" name="Submit" value="Submit" onClick="return confirmsubmit();" />
                                        </td>
                                    </tr>
                                </table>
                            </div>
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
