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
$page = "links";
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
        <title>
            <?php echo $title; ?> | Links
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
                    <div class="article"><h2>Partner Links</h2><h3>
                            <a href="link_add.php"><b>Add Your Link</b></a></h3>
                        <?php
                        if (isset($_REQUEST['pageno'])) {
                            $pageno = sanitize_paranoid_string($_REQUEST['pageno']);
                        } else {
                            $pageno = 1;
                        }
                        if ($pdo) {
                            $query = "SELECT intNumber FROM tbllinks WHERE status = 1";
                            $query_data = select_pdo($query);
                        } else {
                            $query = "SELECT intNumber FROM tbllinks WHERE status = 1";
                            $query_data = $d->fetch($query);
                        }
                        $numrows = count($query_data);
                        $rows_per_page = 25;
                        $lastpage = ceil($numrows / $rows_per_page);
                        $pageno = (int) $pageno;
                        if ($pageno < 1) {
                            $pageno = 1;
                        } elseif ($pageno > $lastpage) {
                            $pageno = $lastpage;
                        }

                        if ($pdo) {
                            $query = "SELECT * FROM tbllinks where status = ? ORDER BY sub_date LIMIT " . ($pageno - 1) * $rows_per_page . "," . $rows_per_page;
                            $bind = array(1);
                            $result2 = select_pdo($query, $bind);
                        } else {
                            $sql2 = "SELECT * FROM tbllinks where status = 1 ORDER BY sub_date LIMIT " . ($pageno - 1) * $rows_per_page . "," . $rows_per_page;
                            $result2 = $d->fetch($sql2);
                        }
                        if ($result2) {
                            $i = 0;

                            foreach ($result2 as $row) {
                                $i = $i + 1;
                                ?>
                                <img src="images/a3.gif" alt='pointer image'>                              &nbsp;&nbsp;Submitted:
                                    <?php echo $row['sub_date']; ?>                               -
                                    <a href="<? echo $row['site_addy'];?>">
                                        <font size="2">
                                            <?php echo stripString(htmlentities($row['site_name'])); ?>
                                        </font></a>
                                    <br>
                                        <font color="#938C8C">
                                            <?php echo stripString(convert(htmlentities($row['site_desc']))); ?>
                                        </font>
                                        <br>
                                            <br>
                                                <?php
                                            }
                                            echo "<hr>";
                                            if ($pageno == 1) {
                                                echo " FIRST PREV ";
                                            } else {
                                                echo " <a href='links.php?pageno=1'>FIRST</a> ";
                                                $prevpage = $pageno - 1;
                                                echo " <a href='links.php?pageno=$prevpage'>PREV</a> ";
                                            }
                                            echo " ( Page $pageno of $lastpage ) ";
                                            if ($pageno == $lastpage) {
                                                echo " NEXT LAST ";
                                            } else {
                                                $nextpage = $pageno + 1;
                                                echo " <a href='links.php?pageno=$nextpage'>NEXT</a> ";
                                                echo " <a href='links.php?pageno=$lastpage'>LAST</a> ";
                                            }
                                        } else {
                                            ?>
                                            <ul>
                                                <li>There is no links at this time. Why not add yours and be the first?
                                                </li>
                                            </ul>
                                            <?php
                                        }
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
                                        ?>