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
$page = 'home';
require_once("system/config.inc.php");

if (!isset($homearticle) || !isset($_SESSION['home_art']) || $_SESSION['home_art'] == '') {
    $homearticle = 10;
}

if ($pdo) {
    $query = "SELECT
            tblarticles.intId AS artid1,
            tblarticles.intCategory,
            tblarticles.intAuthorId,
            tblarticles.intStatus,
            tblarticles.varArticleTitle,
            tblarticles.ttSubmitDate,
            tblauthor.varFirstName,
            tblauthor.varlastName
        FROM tblarticles, tblauthor
        WHERE (tblarticles.intStatus = ? AND tblauthor.txtBAN = 'No' AND tblarticles.intAuthorId = tblauthor.intId)
        ORDER BY ttSubmitDate DESC LIMIT 0 , ?";
    $bind = array(1, $homearticle);
    $result1 = select_pdo($query, $bind, "index_newest.af", 3600);
} else {

    $sql = "SELECT
        tblarticles.intId AS artid1,
        tblarticles.intCategory,
        tblarticles.intAuthorId,
        tblarticles.intStatus,
        tblarticles.varArticleTitle,
        tblarticles.ttSubmitDate,
        tblauthor.varFirstName,
        tblauthor.varlastName
    FROM tblarticles, tblauthor
    WHERE (tblarticles.intStatus = 1 AND tblauthor.txtBAN = 'No' AND tblarticles.intAuthorId = tblauthor.intId)
    ORDER BY ttSubmitDate DESC LIMIT 10";

    $result1 = $d->fetch($sql, "daily", "index_newest.af");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <meta NAME="DESCRIPTION" CONTENT="" />
            <meta NAME="KEYWORDS" CONTENT="" />
            <meta name="robots" content="index, follow" />
            <meta name="distribution" content="Global" />
            <meta NAME="rating" CONTENT="General" />
            <link rel="stylesheet" href="css/style.css" type="text/css" />
            <title>
                <?php echo $title; ?>
            </title>
    </head>
    <body>
        <div class="content">
            <div class="header_top">
            </div>
            <div class="header">
                <?php require_once('inc/menu.php'); ?>
                <div class="sf_left">
                    <?php require_once('inc/logo.php'); ?>
                </div>
            </div>
            <div class="header_bottom">
            </div>
            <div class="subheader">
                <p>
                    <?php include("language.php"); ?>
                </p>
            </div>
            <div class="header_top">
            </div>
            <div class="left">
                <div class="left_side">
                    <?php require_once('inc/left.php'); ?>
                </div>
                <div class="right_side">
                    <div class="article">
                        <!-- Change index text below -->
                        <h2>Welcome To The <?php echo $title; ?></h2>
                        <h3>Fresh Article Publishing</h3>
                        <p>
                            <img src="images/books_cut.jpg" alt="pile of books" style='float:left;'/>
                            You are looking at the new AF Free Version of our basic article directory script for PHP & Mysql.
                        </p>
                        <p> This version has many changes, such as it's ability to detect and use the PDO and PDO_Mysql drivers if installed on your hosting, otherwise
                            it uses an OOP connection.  Also the security in other ways have also been bumped up several levels along with new admin options, such as
                            zip backups and management and a css based theme.
                        </p>
                        <p>I've fixed up the code, added new functions (such as a conversion function that converts those nasty MS word characters), and mysql caching to
                            speed up your site and reduce resource usage. All pages are now gzip compressed to also speed things up!</p>
                        <p>Best of all, the script is now <b>FREE and UNENCODED</b>!</p>
                        <p>I'll have the link up to download this script soon on <a href='http://www.articlefriendly.com/'>ArticleFriendly.com</a>!
                        </p>

                        <p>  Best,
                            <br />
                            <br />  Jan Michaels
                        </p>
                        <!-- End index text -->
                    </div>
                    <div class="article">
                        <h2>Newest Articles</h2>
                        <h3>Click the link to view</h3>
                    </div>
                    <div class="grey_top">
                    </div>
                    <div class="grey">
                        <?php
                        if (isset($result1)) :
                            foreach ($result1 as $row) :
                                $intcat = "";
                                $intcat = $row['intCategory'];
                                $date = $row['ttSubmitDate'];
                                $date = substr($date, 0, 9);
                                $the_title = stripString(htmlentities($row['varArticleTitle']));
                                if (strlen($the_title) > 45) :
                                    $title = substr($the_title, 0, 45) . "...";
                                else:
                                    $title = $the_title;
                                endif;
                                $auth_name = $row['varFirstName'] . " " . $row['varlastName'];
                                ?>
                                <p class="highlight">
                                    <a href="articledetail.php?artid=<?php echo $row['artid1']; ?>&amp;catid=<?php echo $row['intCategory']; ?>&amp;title=<?php echo str_replace(" ", "-", $the_title) ?>" title="<?php echo $the_title; ?>"><?php echo $title; ?></a>
                                    &nbsp;&nbsp;By: <?php echo $auth_name ?>
                                </p>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </div>
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
