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
if ($_REQUEST['artid'] == '') {
    $_SESSION['msg'] = "Missing Article ID. Exiting...";
    header("location:thankyou.php");
    die();
}
$blast = $_REQUEST['artid'];
if (preg_match("/^([0-9.,-]+)$/", $blast)) {
    $blast = "true";
} else {
    $_SESSION['msg'] = "<center>Changing the URL to run remote scripts is FORBIDDEN and illegal.  Your IP has been recorded, and sent to the proper authorities.</center>";
    header("location:thankyou.php");
    die();
}
if ($_REQUEST['artid'] == -1) {
    $_SESSION['msg'] = "<center>Changing the URL to run remote scripts is FORBIDDEN and illegal.  Your IP has been recorded, and sent to the proper authorities.</center>";
    header("location:thankyou.php");
    die();
}
if (!is_numeric($_REQUEST['artid'])) {
    $_SESSION['msg'] = "<center>Changing the URL to run remote scripts is FORBIDDEN and illegal.  Your IP has been recorded, and sent to the proper authorities.</center>";
    header("location:thankyou.php");
    die();
}
if ($_REQUEST['catid'] == '') {
    $_SESSION['msg'] = "Missing Category ID. Exiting...";
    header("location:thankyou.php");
    die();
}
if ($_REQUEST['catid'] == -1) {
    $_SESSION['msg'] = "<center>Changing the URL to run remote scripts is FORBIDDEN and illegal.  Your IP has been recorded, and sent to the proper authorities.</center>";
    header("location:thankyou.php");
    die();
}
if (!is_numeric($_REQUEST['catid'])) {
    $_SESSION['msg'] = "<center>Changing the URL to run remote scripts is FORBIDDEN and illegal.  Your IP has been recorded, and sent to the proper authorities.</center>";
    header("location:thankyou.php");
    die();
}
define('AFFREE', 1);
$page = "articledetail";
include("system/config.inc.php");
$_GET = array_map('stripslashes', $_GET);
$_POST = array_map('stripslashes', $_POST);
$_COOKIE = array_map('stripslashes', $_COOKIE);
$_REQUEST = array_map('stripslashes', $_REQUEST);
if ($pdo) {
    $query = "select textAgreement from tblsettings";
    $result = select_pdo($query);
} else {
    $sql = "select textAgreement from tblsettings";
    $result = $d->fetch($sql);
}
if ($result) {
    foreach ($result as $rows) {
        $adsense = $rows['textAgreement'];
    }
}
if (isset($_REQUEST['artid']) && trim($_REQUEST['artid']) != "") {
    $tmp_art3 = sanitize_paranoid_string($_REQUEST['artid']);
    $articleid = $tmp_art3;
    $tmp_cat4 = sanitize_paranoid_string($_REQUEST['catid']);
    $categid = $tmp_cat4;
    if ($pdo) {
        $query = "SELECT * FROM tblarticles where intId = ? AND intStatus = 1";
        $bind = array($articleid);
        $result = select_pdo($query, $bind, "art_" . $articleid . ".af", 3600);
    } else {
        $result = $d->fetch("SELECT * FROM tblarticles where intId = '" . safeEscapeString($articleid) . "' AND intStatus = 1", "daily", "art_" . $articleid . ".af");
    }
    if (isset($result)) {
        foreach ($result as $row) {
            $submited = $row['ttSubmitDate'];
            $artname = stripString($row['varArticleTitle']);
            $arttext = stripString($row['textArticleText']);
            $artresorce = stripString($row['textResource']);
            $authorId = $row['intAuthorId'];
            $counter = $row['intHit'];
            $wordcount = $row['word_count'];
            if ($_SESSION['artcount'] != $articleid || !isset($_SESSION['artcount'])) {
                $reader = $counter + 1;
                if ($pdo) {
                    $query = "UPDATE tblarticles Set intHit = ? where intId = ?";
                    $bind = array($reader, $articleid);
                    $result = update_pdo($query, $bind);
                } else {
                    $d->exec("UPDATE tblarticles Set intHit = '" . safeEscapeString($reader) . "' where intId = '" . safeEscapeString($articleid) . "'");
                }
                $_SESSION['artcount'] = $articleid;
            } else {
                $reader = $counter;
            }
            if ($pdo) {
                $query = "SELECT * FROM tblauthor where intId = ?";
                $bind = array($authorId);
                $result1 = select_pdo($query, $bind, "auth_" . $authorId . ".af", 3600);
            } else {
                $result1 = $d->fetch("SELECT * FROM tblauthor where intId = '" . safeEscapeString($authorId) . "'", "daily", "auth_" . $authorId . ".af");
            }
            $authorname = stripString(htmlentities($result1[0]['varFirstName'])) . " " . stripString(htmlentities($result1[0]['varlastName']));
            $keywords = stripString(htmlentities($row['varKeywords']));
            $photo = $result1[0]['authPhoto'];
        }
    } else {
        echo "Sorry, but no approved article found.";
    }
}

// $articleid;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta NAME="DESCRIPTION" CONTENT="" />
        <?php
        if (isset($keywords)) {
            ?>
            <meta NAME="KEYWORDS" CONTENT="<?php echo $keywords ?>" />
            <?php
        } else {
            ?>
            <meta NAME="KEYWORDS" CONTENT="article directory script,article friendly,php mysql script,article script" />
            <?php
        }
        ?>
        <meta name="robots" content="index, follow" />
        <meta name="distribution" content="Global" />
        <meta NAME="rating" CONTENT="General" />
        <link rel="alternate" type="application/rss+xml" title="<?php echo $artname ?>" href="<?php echo $site_URL ?>rss.php?rss=
              <?php echo $categid ?> ">
            <script language="javascript" type="text/javascript">
                <!--
            function copy_clip(meintext)
                {
                    if (window.clipboardData)
                    {
                        window.clipboardData.setData("Text", meintext);
                    }
                    else if (window.netscape)
                    {
                        netscape.security.PrivilegeManager.enablePrivilege('UniversalXPConnect');
                        var clip = Components.classes['@mozilla.org/widget/clipboard;1']
                                .createInstance(Components.interfaces.nsIClipboard);
                        if (!clip)
                            return;
                        var trans = Components.classes['@mozilla.org/widget/transferable;1']
                                .createInstance(Components.interfaces.nsITransferable);
                        if (!trans)
                            return;
                        trans.addDataFlavor('text/unicode');
                        var str = new Object();
                        var len = new Object();
                        var str = Components.classes["@mozilla.org/supports-string;1"]
                                .createInstance(Components.interfaces.nsISupportsString);
                        var copytext = meintext;
                        str.data = copytext;
                        trans.setTransferData("text/unicode", str, copytext.length * 2);
                        var clipid = Components.interfaces.nsIClipboard;
                        if (!clip)
                            return false;
                        clip.setData(trans, null, clipid.kGlobalClipboard);
                    }
                    alert("The Article has been copied to your clipboard and you can now paste it where you wish.  Please remember you must keep the author's links and this sites link intact in the article to use it.");
                    return false;
                }
                //-->
            </script>
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
                <?php require_once(INC . '/menu.php'); ?>
                <div class="sf_left">
                    <?php require_once(INC . '/logo.php'); ?>
                </div>
            </div>
            <div class="header_bottom">
            </div>
            <div class="subheader">
                <?php
                include("language.php");
                ?>
            </div>
            <div class="header_top">
            </div>
            <div class="left">
                <div class="left_side">
                    <?php require_once(INC . '/left.php'); ?>
                </div>
                <!--  Center content  -->
                <div class="right_side">
                    <div class="article"><h2><b>
                                <?php echo $artname; ?></b></h2>
                        <p><b>By:
                                <a href="authordetail.php?autid=<?php echo $authorId ?> ">
                                    <?php echo $authorname ?></a></b>
                        </p>
                        <p>Submitted:
                            <?php echo $submited ?>
                        </p>
                        <p>Popularity:
                            <?php
                            if ($pdo) {
                                $query = "SELECT intHit FROM tblarticles where intId = ?";
                                $bind = array($articleid);
                                $result2 = select_pdo($query, $bind);
                            } else {
                                $result2 = $d->fetch("SELECT intHit FROM tblarticles where intId = '" . safeEscapeString($articleid) . "'");
                            }
                            foreach ($result2 as $row2) {
                                $pop = $row2['intHit'];
                            }
                            if ($pop < 5) {
                                echo "<img src='images/0.gif' alt='zero times read'>";
                            } elseif ($pop > 4 and $pop < 10) {
                                echo "<img src='images/1.gif' alt='4 or more times read'>";
                            } elseif ($pop > 9 and $pop < 15) {
                                echo "<img src='images/2.gif' alt='9 or more times read'>";
                            } elseif ($pop > 14 and $pop < 20) {
                                echo "<img src='images/3.gif' alt='14 or more times read'>";
                            } elseif ($pop > 19 and $pop < 30) {
                                echo "<img src='images/4.gif' alt='19 or more times read'>";
                            } elseif ($pop > 29 and $pop < 100) {
                                echo "<img src='images/5.gif' alt='29 or more times read'>";
                            } else {
                                echo "<img src='images/5.gif' alt='99 or more times read'>";
                            }
                            $html = preg_replace('/\s(\w+:\/\/)(\S+)/', ' <a href="\\1\\2" target="_blank">\\1\\2</a>', $artresorce);
                            $html = convert($html);
                            $artresorce = '';
                            if ($adsense > "") {
                                echo "<span style=\"float: left; padding-right: 5px;\">";
                                echo "<script type=\"text/javascript\"><!--\n";
                                echo "google_ad_client = \"$adsense\";\n";
                                echo "google_ad_width = 120;\n";
                                echo "google_ad_height = 240;\n";
                                echo "google_ad_format = \"120x240_as\";\n";
                                echo "google_ad_type = \"text_image\";\n";
                                echo "google_ad_channel = \"\";\n";
                                echo "google_color_border = \"FFFFFF\";\n";
                                echo "google_color_bg = \"FFFFFF\";\n";
                                echo "google_color_link = \"000033\";\n";
                                echo "google_color_text = \"000000\";\n";
                                echo "google_color_url = \"008000\";\n";
                                echo "//--></script>\n";
                                echo "<script type=\"text/javascript\" src=\"http://pagead2.googlesyndication.com/pagead/show_ads.js\">\n";
                                echo "</script>\n";
                                echo "</span>\n";
                                echo "<!-- google_ad_section_start -->\n";
                            }
                            $arttext = convert(str_replace("\n", "<br>", $arttext));
                            ?>
                        </p>
                        <p>&nbsp;
                        </p>
                        <p>
                            <?php echo $arttext; ?>
                        </p>
                        <p>&nbsp;
                        </p><h2>Author Resource</h2>
                        <p>&nbsp;
                        </p>
                        <p>
                            <?php echo str_replace("\n", "<BR>", $html) ?>
                        </p>
                        <p>&nbsp;
                        </p>
                        <p>
                            <a href="<?php echo $site_URL ?>rssdetail.php?rss=<?php echo $articleid ?>" target="_blank" style="text-decoration: none;">
                                <img src="images/icon_Rss.png" alt="[Valid RSS feed]" title="XML Feed For RSS" border="0" />&nbsp;&nbsp;Article Rss Feed</a> -
                            <font size="1">
                                <?php echo $site_URL ?>rssdetail.php?rss=
                                <?php echo $articleid ?>
                            </font>
                        </p>
                        <p>
                            <a href="<?php echo $site_URL ?>rss.php?rss=<?php echo $categid ?>" target="_blank" style="text-decoration: none;">
                                <img src="images/icon_Rss.png" alt="[Valid RSS feed]" title="XML Feed For RSS" border="0" />&nbsp;&nbsp;Category Rss Feed</a> -
                            <font size="1">
                                <?php echo $site_URL ?>rss.php?rss=
                                <?php echo $categid ?>
                            </font>
                        </p>
                        <p>
                            <p>&nbsp;
                            </p><h2>HTML Ready Article. Click on the "Copy" button to copy into your clipboard.</h2><br /><br />
                            <p>

                                <textarea  cols="60" rows="30" title="Article Friendly Ezine Ready Article" name="txtMessage"><?php echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>" ?>
                                    <?php echo "<html><head><title>{$title} | {$artname}</title></head>" ?>
                                    <?php echo "<body><h3>{$artname}</h3><br><br> By: {$authorname}<br /><br />" ?>
                                    <?php echo $arttext . "<br /><br /> <b>Author Resource:-></b>&nbsp;&nbsp;" ?>
                                    <?php echo $html . "<br /><br />" ?>
                                    <?php echo "<b>Article From</b> <a href='" . $site_URL . "'>" . $title . "</a><br />"; ?>
                                    <?php echo "</body></html>" ?></textarea>

                            </p><br />
                            <center>
                                <input type="button" onclick='return copy_clip(document.myform.txtMessage.value)' value="Copy!" /><br /><br />
                                <font color="gray" size="1">Firefox users please select/copy/paste as usual
                                </font>
                            </center>
                        </p>
                        <!-- End center content  -->
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
?>