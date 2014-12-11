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
$page = 'top';
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
            <?php echo $title; ?> | Top Articles
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
                    <div class="article"><h2>Top Articles</h2>
                        <p>&nbsp;
                        </p>
                        <table  border="0" align="center" cellpadding="0" cellspacing="0">
                            <tr>
                                <td align="left" class="inputlabel">
                                    <br />
                                    <br />
                                    <?php
                                    if (isset($_SESSION['home_art']) || $_SESSION['home_art'] > '') {
                                        $homearticle = $_SESSION['home_art'];
                                    } else {
                                        $homearticle = 10;
                                    }
                                    if ($pdo) {
                                        $query = "SELECT tblarticles.intId AS artid1, tblarticles.intHit, tblarticles.intCategory, tblarticles.intAuthorId, tblarticles.intStatus,
  tblarticles.textSummary, tblarticles.varArticleTitle, tblauthor.varFirstName, tblauthor.varlastName, tblauthor.intId
  FROM tblarticles, tblauthor where (tblarticles.intStatus = ? AND tblarticles.intAuthorId = tblauthor.intId)
  ORDER BY intHit DESC LIMIT 0 , ?";
                                        $bind = array(1, $homearticle);
                                        $result1 = select_pdo($query, $bind, "topart.af", 3600);
                                    } else {

                                        $sql = "SELECT tblarticles.intId AS artid1, tblarticles.intHit, tblarticles.intCategory, tblarticles.intAuthorId, tblarticles.intStatus,
  tblarticles.textSummary, tblarticles.varArticleTitle, tblauthor.varFirstName, tblauthor.varlastName, tblauthor.intId
  FROM `tblarticles`, `tblauthor` where (tblarticles.intStatus = 1 AND tblarticles.intAuthorId = tblauthor.intId)
  ORDER BY `intHit` DESC LIMIT 10";
                                        $result1 = $d->fetch($sql, "daily", "topart.af");
                                    }
                                    ?>
                                    <ul>
                                        <?php
                                        if ($result1) {

                                            foreach ($result1 as $row) {
                                                $sum_text = stripslashes($row['textSummary']);

                                                $sum_text = convert($sum_text);

                                                $the_title = stripString(convert($row['varArticleTitle']));
                                                ?>
                                                <li>
                                                    <a href="articledetail.php?artid=<? echo $row['artid1'];?>&amp;catid=<? echo $row['intCategory'];?>&amp;title=
                                                       <?php echo str_replace(" ", "-", $the_title) ?>" title="
                                                       <? echo $the_title ;?>">
                                                        <? echo $the_title ;?></a>&nbsp;&nbsp;
                                                    <font size="1">By:
                                                    </font>&nbsp;&nbsp;
                                                    <a href="authordetail.php?autid=<?php echo $row['intAuthorId'] ?>">
                                                        <?php echo stripString(htmlentities($row['varFirstName'])) . " " . stripString(htmlentities($row['varlastName'])); ?></a>&nbsp;&nbsp;
                                                    <font size="1">Views:
                                                        <?php echo $row['intHit']; ?>
                                                    </font>
                                                    <br />
                                                    <?php echo $sum_text; ?>
                                                    <br />
                                                    <br />
                                                    <?php
                                                }
                                            } else {
                                                echo "<p>Sorry but there was an error. The admin has been notified. Please try again.</p>";
                                            }
                                            ?>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="fonttitle">Most Recent Articles </td>
                            </tr>
                            <tr>
                                <td align="left">
                                    <?php
                                    if ($pdo) {
                                        $query = "SELECT intId,varArticleTitle,intCategory FROM tblarticles where intStatus = 1 ORDER BY ttSubmitDate DESC LIMIT 0 , ?";
                                        $bind = array($homearticle);
                                        $result2 = select_pdo($query, $bind, "most_recent_art.af", 3600);
                                    } else {

                                        $sql = "SELECT intId,varArticleTitle,intCategory FROM tblarticles where intStatus = 1 ORDER BY ttSubmitDate DESC LIMIT 0 , '$homearticle'";
                                        $result2 = $d->fetch($sql, "daily", "most_recent_art.af");
                                    }
                                    ?>
                                    <ul style="list-style-image: url(images/a3.gif);">
                                        <?php
                                        if (isset($result2)) {

                                            foreach ($result2 as $row) {
                                                $the_title = stripString(convert($row['varArticleTitle']));
                                                ?>
                                                <li>
                                                    <a href="articledetail.php?artid=<? echo $row['intId'];?>&amp;catid=<? echo $row['intCategory'];?>&amp;title=
                                                       <?php echo str_replace(" ", "-", $the_title) ?>" title="
                                                       <? echo $the_title;?>">
                                                        <?php echo $the_title; ?></a>
                                                </li>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </ul>
                                </td>
                            </tr>
                        </table>
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
