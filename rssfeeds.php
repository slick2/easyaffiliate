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
$page = 'rssfeeds';
define('AFFREE', 1);
include("system/config.inc.php");
$cleaner = cache_cleanup();
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
                                    <?php echo $title; ?> | RSS Feeds
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
                                                <div class="article"><h2>Rss Feeds</h2>
                                                    <p>&nbsp;
                                                    </p>
                                                    <p>The RSS feed URL for the top 5 newest articles is:</b><br /><br />
                                                        <?php echo $site_URL ?>rss.php?rss=0
                                                        <br>
                                                            <br>                   <b>The RSS feeds for individual categories is:</b><br /><br />
                                                                <?php
                                                                if ($pdo) {
                                                                    $query = "SELECT * FROM tblcategories ORDER BY varCategory";
                                                                    $connection2 = select_pdo($query);
                                                                } else {
                                                                    $connection2 = $d->fetch("SELECT * FROM tblcategories ORDER BY varCategory");
                                                                }
                                                                foreach ($connection2 as $row) {
                                                                    echo "<b>" . $row['varCategory'] . "</b>&nbsp;&nbsp;&nbsp;<img src='images/a3.gif' alt='copy to your feed'>&nbsp;&nbsp;&nbsp;<a href='" . $site_URL . "rss.php?rss=" . $row['intID'] . "'>" . $site_URL . "rss.php?rss=" . $row['intID'] . "</a><br>";
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