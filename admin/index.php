<?php
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

$find = array("1=1", "or 1", "delete", "drop", " or '1'='1", " or '1'='1' -- ",
    " or '1'='1' ({ ", "or '1'='1' /*", "@@version", "1=2", "base64_encode",
    "declare", "md5", "union", "benchmark", "localhost");

foreach ($find as $term) {
    if (in_array($term, array_map('strtolower', $_POST))) {
        die("forbidden word in POST");
    }
}

foreach ($find as $term) {
    if (in_array($term, array_map('strtolower', $_REQUEST))) {
        die("forbidden word in REQUEST");
    }
}


if (isset($_POST)) {
    foreach ($_POST as $key => $value) {
        $value = EscapeString($value);
    }
}

if ($_REQUEST) {
    foreach ($_REQUEST as $key => $value) {
        $value = EscapeString($value);
    }
}

//Prevent local or remote file inclusion vulnerability
$page = "";

if(!empty($_REQUEST['filename']))
{
    $page = strtolower($_REQUEST['filename']);
}




if ($page == "") {
    $page = "index";
}
// make sure our $page variable isn't too long or short.
if (strlen($page) > 30 || strlen($page) < 3) {
    echo "Access Denied for invalid page name!";
    die();
}
// look for common strings used in $page variable
$search_array = array("http", "www", "php", "txt", "ftp", "https", "htm", "file=", "?", "&", "../",
    ".log", ".db", "%00", "\.", "[", "]", "//input", "//filter",
    "data:", "xss=", "shell");

foreach ($search_array as $key => $search_needle) {
    if (stristr($page, $search_needle) != FALSE) {
        echo "Access Denied for invalid page name!";
        die();
    }
}
// look for an ip in the $page variable
if (preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", $page)) {
    echo "Access Denied for invalid page name!";
    die();
}

$page_array = "";
$page_array = array("index", "adminlogin", "adminlogout", "adminuser", "all_articles",
    "app", "approve", "approveallarticles", "approveunapproved", "article_detail",
    "articles", "articlesearch", "artsearch", "author", "author_detail", "authorsearch",
    "authorun", "authorunapproved", "bannedauthor", "body_check", "categories", "country",
    "deleteallarticles", "deleteemail", "deleteunapproved", "dupe_form", "duplicated",
    "empty", "forgetpswd", "license", "links_detail", "mailing", "massmove", "massmove1", "new_links",
    "onemail", "optimizer", "photos", "repairs", "search", "settings", "stats", "thankyou", "title_check",
    "wordcount", "cleanup", "backup", "backup_list", "empty_cache");

if (!in_array($page, $page_array)) {
    die("Page Name Not Found...");
}

$page = urlencode($page);
define('AFFREE', 1);
require_once("system/config.inc.php");
require_once 'system/secure_sess.php';
$ss = new SecureSession();

if ($_POST) {
    foreach ($_POST as $key => $value) {
        if (stristr($value, '\n') === FALSE) {
            $value = nl2br($value);
            $value = stripslashes($value);
        } else {
            $value = n2br($value);
            $value = stripslashes($value);
        }
    }
}
if ($_REQUEST) {
    foreach ($_REQUEST as $key => $value) {
        if (stristr($value, '\n') === FALSE) {
            $value = nl2br($value);
            $value = stripslashes($value);
        } else {
            $value = n2br($value);
            $value = stripslashes($value);
        }
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD>
        <title>articlefriendly.com | admin cpanel</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="css/articles.css" rel="stylesheet" type="text/css">
    </HEAD>

    <body>
        <center>

            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td><table width="100%"  border="0" cellspacing="0" cellpadding="1">
                            <tr valign="top">
                                <td colspan="2"><?php include ("top/top.php"); ?></td>
                            </tr>
                            <tr valign="top">
                                <td width="15%"><? include("left/left.php");?></td>
                                <td width="85%"><? include("middle/".$page.".php");?></td>
                            </tr>
                        </table></td>
                </tr>
                <tr>
                    <td align='center' valign="middle"><? include("footer/footer.php");?></td>
                </tr>
            </table>

        </center>
    </body>
</HTML>
<?php
ob_end_flush();
?>
