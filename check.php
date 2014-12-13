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
    $_SESSION['msg'] = "<font color='red'>You must login to access that page. Thank you. </font>";
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

if (!get_magic_quotes_gpc()) {
    $_GET = array_map('EscapeString', $_GET);
    $_POST = array_map('EscapeString', $_POST);
    $_COOKIE = array_map('EscapeString', $_COOKIE);
    $_REQUEST = array_map('EscapeString', $_REQUEST);
}
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
        <title>
            <?php echo $title; ?> | Upload Photo
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
                    <div class="article"><h2>Upload Photo</h2>
                        <p>&nbsp;
                        </p>
                        <?php
                        $action = "";
                        $authID = $_SESSION['uid'];

                        if (isset($_POST["action"])) {
                            unset($imagename);

                            if (empty($_FILES['image_file']['name'])) {
                                $_SESSION['msg'] = "<br><br>Please go back and enter an image file name and try again!<br><br> Thank you";
                                header("location:thankyou.php");
                                die();
                            }
                            if (!isset($_FILES) && isset($HTTP_POST_FILES)) {
                                $_FILES = $HTTP_POST_FILES;
                            }
                            if (!isset($_FILES['image_file'])) {
                                $error["image_file"] = "An image was not found.";
                            }

                            $imagename = basename($_FILES['image_file']['name']);

                            if (empty($imagename)) {
                                $error["imagename"] = "The name of the image was not found.";
                            }

                            if (empty($error)) {
                                $newimage = "author/" . $imagename;

                                $result = @move_uploaded_file($_FILES['image_file']['tmp_name'], $newimage);
                                $mode = "0644";
                                if ($result)
                                    chmod("$newimage", 0644);

                                if (empty($result))
                                    $error["result"] = "There was an error moving the uploaded file.";



                                $exten = substr($imagename, -4);

                                if ($exten != ".jpg" && $exten != "jpeg") {
                                    unlink($newimage);
                                    $authID = $_SESSION['uid'];

                                    $_SESSION['msg'] = "<br><br>We only allow image files with extentions of .jpg or .jpeg at this time.  Please
  convert your photo to this extention and try again!<br><br> Thank you";
                                    header("location:thankyou.php");
                                    die();
                                }

                                $size = getimagesize($newimage);

                                $bad = array(".gif", ".txt", ".doc", ".htm", ".asp", ".bmp", ".clp", ".dcx", ".eps", ".fpx", ".ico", ".iff", ".img",
                                    ".jp2", ".jpc", ".mac", ".msp", ".pmb", ".pcx", ".pgm", "png", ".ppm", ".psd", ".pxr", ".ras", ".sci", ".sct",
                                    ".tga", ".tif", ".tiff", ".ufo", ".wbm", "wbmp", ".wmf", "html", ".zip", "gzip", ".lzh", ".mov", ".mpg", "mpeg",
                                    ".mp2", ".mp3", ".exe", ".cab", ".php", ".ini", ".wmv", ".xml", ".rss", ".tar", ".css", ".sys", ".com", ".bat",
                                    ".tmp", ".dll", ".log", ".dat", ".cmd");

                                if (in_array($exten, $bad)) {
                                    unlink($newimage);

                                    $_SESSION['msg'] = "<br><br>We only allow image files with extentions of .jpg or .jpeg at this time.  Please
  convert your photo to this extention and try again!<br><br> Thank you";
                                    header("location:thankyou.php");
                                    die();
                                }

                                if ($size[0] > 80 || $size[1] > 80) {
                                    unlink($newimage);


                                    $_SESSION['msg'] = "<br><br>Your picture exceeded our limit of 80x80.  Please resize your photo and try again!<br><br> Thank you";
                                    header("location:thankyou.php");
                                    die();
                                }
                            }

                            $authID = $_SESSION['uid'];
                            $final_filename = $imagename;

                            if ($pdo) {
                                $query = "SELECT authPhoto FROM tblauthor WHERE ? = intId";
                                $bind = array($authID);
                                $pics = select_pdo($query, $bind);
                            } else {
                                $authID = safeEscapeString($authID);
                                $pics = $d->fetch("SELECT authPhoto FROM tblauthor WHERE '$authID' = intId");
                            }
                            $toast = $pics[0]['authPhoto'];
                            if (isset($toast)) {
                                $toasted = "author/" . $toast;
                                unlink($toasted);
                            }

                            if ($pdo) {
                                $query = "UPDATE tblauthor SET authPhoto = ? WHERE intId = ?";
                                $bind = array($final_filename, $authID);
                                $result = update_pdo($query, $bind);

                                $query = "SELECT varFirstName,varEmail FROM tblauthor WHERE intId = ?";
                                $bind = array($authID);
                                $row = select_pdo($query, $bind);
                            } else {

                                $final_filename = safeEscapeString($final_filename);
                                $fetch = $d->exec("UPDATE tblauthor SET authPhoto = '$final_filename' WHERE '$authID' = intId");
                                $row = $d->fetch("SELECT varFirstName,varEmail FROM tblauthor WHERE '$authID' = intId");
                            }
                            $_SESSION['name'] = stripslashes($row[0]['varFirstName']) . " " . stripslashes($row['varlastName']);
                            $_SESSION['email'] = stripslashes($row[0]['varEmail']);
                            $_SESSION['pic'] = "<img src=author/$final_filename border='1'>";
                            $_SESSION['msg'] = "<br><br>Your photo has been added. You may change it at any time by going back to upload photo!  Your
  photo will now be included in your bio and may be featured by us at a later date.<br><br> Thank you";
                            header("location:thankyou.php");
                            die();
                        } else {
                            ?>
                            <p>&nbsp;
                            </p>
                            <p>You are allowed one photo which will be shown in your bio and within your articles! Only pictures   with a .jpg or .jpeg extension are allowed.
                            </p>
                            <p>&nbsp;
                            </p>
                            <form method="POST" enctype="multipart/form-data" name="image_upload_form" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                                <p>
                                    <input type="file" name="image_file" size="20" />
                                </p>
                                <p>&nbsp;
                                </p>
                                <p>
                                    <input type="submit" value="Upload Image" name="action" />
                                </p>
                            </form>
                            <?php
                            $authID = $_SESSION['uid'];

                            if ($pdo) {
                                $query = "SELECT authPhoto FROM tblauthor WHERE intId = ?";
                                $bind = array($authID);
                                $row = select_pdo($query, $bind);
                            } else {
                                $authID = safeEscapeString($authID);
                                $fetch = ("SELECT authPhoto FROM tblauthor WHERE intId = '$authID'");
                                $row = $d->fetch($fetch);
                            }
                            $picture = stripslashes(strip_tags($row[0]['authPhoto']));
                            if ($picture) {
                                echo "<br><div align='center'><b>Your Current Picture</b><br><br>";
                                echo "<img src='author/" . $picture . "' border='1'></div><br>";
                            }
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
