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

//   if(!isset($_SESSION['uid']) || trim($_SESSION['uid']) == "")
//   {
//   $_SESSION['msg'] = "You must login first!";
//   header("location:thankyou.php");
//   die();
//   }

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
$_GET = array_map('stripslashes', $_GET);
$_POST = array_map('stripslashes', $_POST);
$_COOKIE = array_map('stripslashes', $_COOKIE);
$_REQUEST = array_map('stripslashes', $_REQUEST);

// UPDATE operation of author
$FirstName = "";
$lastName = "";
$Address1 = "";
$Address2 = "";
$Zip = "";
$City = "";
$State = "";
$Country = "";
$Phone = "";
$Fax = "";
$old_bio = '';
$old_web = '';

if (isset($_SESSION['uid']) && trim($_SESSION['uid']) != "") {
    $authorId = $_SESSION['uid'];
    if ($pdo) {
        $query = "SELECT * FROM tblauthor WHERE intId = ?";
        $bind = array($authorId);
        $result = select_pdo($query, $bind);
    } else {
        $authorId = safeEscapeString($_SESSION['uid']);
        $sql = "SELECT * FROM tblauthor WHERE intId ='$authorId'";
        $result = $d->fetch($sql);
    }
    // IF there is not records in database
    if (count($result) <= 0) {
        $_SESSION['msg'] = "No Record Found! Author ID is not valid!";
        header("location:thankyou.php");
        die();
    }

    // If there is records in database it will be store in a variable
    // to identify which author account is updated.
    if ($result) {
        foreach ($result as $row) {
            $a_id = trim($row['intId']);
            $FirstName = stripslashes($row['varFirstName']);
            $lastName = stripslashes($row['varlastName']);
            $Address1 = stripslashes($row['varAddress1']);
            $Address2 = stripslashes($row['varAddress2']);
            $Zip = stripslashes($row['varZip']);
            $City = stripslashes($row['varCity']);
            $State = stripslashes($row['varState']);
            $Country = stripslashes($row['intCountry']);
            $Phone = stripslashes($row['varPhone']);
            $Fax = stripslashes($row['varFax']);
            $old_bio = stripslashes($row['varBio']);
            $old_web = stripslashes($row['website']);
        }
        if ($authorId != $a_id) {
            $_SESSION['msg'] = "Wrong Author ID. Cannot Update.";
            header("location:thankyou.php");
            die();
        }
    }

    if (isset($_POST['Submit']) && trim($_POST['Submit']) == "Update") {
        require_once("htmlpurifier/library/HTMLPurifier.auto.php");
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);

        foreach ($_POST as $key => $value) {
            $_POST[$key] = convert($value);
        }

        $varFirstName = convert($_POST['fname']);
        $varFirstName = $purifier->purify($varFirstName);
        $varlastName = convert($_POST['lname']);
        $varlastName = $purifier->purify($varlastName);
        $varAddress1 = convert($_POST['add1']);
        $varAddress1 = $purifier->purify($varAddress1);
        $varAddress2 = convert($_POST['add2']);
        $varAddress2 = $purifier->purify($varAddress2);
        $varZip = convert($_POST['zip']);
        $varZip = $purifier->purify($varZip);
        $varCity = convert($_POST['city']);
        $varCity = $purifier->purify($varCity);
        $varState = convert($_POST['state']);
        $varState = $purifier->purify($varState);
        $intCountry = convert($_POST['country']);
        $intCountry = $purifier->purify($intCountry);
        $varPhone = convert($_POST['phone']);
        $varPhone = $purifier->purify($varPhone);
        $varFax = convert($_POST['fax']);
        $varFax = $purifier->purify($varFax);
        $bio = convert($_POST['message']);
        $bio = $purifier->purify($bio);
        $web = convert($_POST['web']);
        $web = $purifier->purify($web);


        if ($pdo) {
            $query = "UPDATE tblauthor SET varFirstName = ?,varlastName = ?, varAddress1 = ?,
  varAddress2 = ?, varZip = ?, varCity = ?, varState = ?,
  intCountry = ?, varPhone = ?, varFax = ?, varBio= ?, website = ? WHERE intId = ?";
            $bind = array($varFirstName, $varlastName, $varAddress1, $varAddress2, $varZip, $varCity, $varState, $intCountry, $varPhone, $varFax, $bio, $web, $authorId);
            $result = update_pdo($query, $bind);
        } else {
            $sql_upd = "UPDATE tblauthor SET varFirstName = '" . safeEscapeString($varFirstName) . "',varlastName = '" . safeEscapeString($varlastName) . "',
  varAddress1 = '" . safeEscapeString($varAddress1) . "', varAddress2 = '" . safeEscapeString($varAddress2) . "', varZip = '" . safeEscapeString($varZip) . "',
  varCity = '" . safeEscapeString($varCity) . "', varState = '" . safeEscapeString($varState) . "', intCountry = '" . safeEscapeString($intCountry) . "',
  varPhone = '" . safeEscapeString($varPhone) . "', varFax = '" . safeEscapeString($varFax) . "', varBio= '" . safeEscapeString($bio) . "',
  website = '" . safeEscapeString($web) . "' WHERE intId ='" . safeEscapeString($authorId) . "'";

            $result = $d->exec($sql_upd);
        }

        $_SESSION['msg'] = "Your account was successfully updated.";
        header("location:editaccount.php");
        die();
    }
} else {
    $_SESSION['msg'] = "You must login first!";
    header("location:thankyou.php");
    die();
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
            <?php echo $title; ?> | Edit Account
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
                    <div class="article"><h2>Your Account</h2>

                        <?php
                        if (isset($_SESSION['msg'])) {
                            echo "<p><font color='green'>" . $_SESSION['msg'] . "</font></p>";
                            unset($_SESSION['msg']);
                        }
                        ?>


                        <form name="form11" method="post" action="">
                            <table  border="0" cellspacing="1" cellpadding="1" class="greyborder" align="center">
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style='font-weight:bold;'>
                                        <div align="left">First Name : </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <input name="fname" type="text" id="country19" value="<?= $FirstName; ?>" size="45" />
                                    </td>
                                </tr>
                                <tr>
                                    <td style='font-weight:bold;'>
                                        <div align="left">Last Name : </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input name="lname" type="text" id="country20" value="<?= $lastName; ?>" size="45" />
                                    </td>
                                </tr>
                                <tr>
                                    <td style='font-weight:bold;'>
                                        <div align="left">Address Line 1 : </div>
                                    </td>
                                </tr><tr>
                                    <td>
                                        <input name="add1" type="text" id="country21" value="<?= $Address1; ?>" size="45" />
                                    </td>
                                </tr>
                                <tr>
                                    <td style='font-weight:bold;'>
                                        <div align="left">Address Line 2 : </div>
                                    </td>
                                </tr><tr>
                                    <td>
                                        <input name="add2" type="text" id="country22" value="<?= $Address2; ?>" size="45" />
                                    </td>
                                </tr>
                                <tr>
                                    <td style='font-weight:bold;'>
                                        <div align="left">Zip Code : </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input name="zip" type="text" id="country23" value="<?= $Zip; ?>" size="45" />
                                    </td>
                                </tr>
                                <tr>
                                    <td style='font-weight:bold;'>
                                        <div align="left">City : </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input name="city" type="text" id="country24" value="<?= $City; ?>" size="45" />
                                    </td>
                                </tr>
                                <tr>
                                    <td style='font-weight:bold;'>
                                        <div align="left">State : </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <input name="state" type="text" id="country25" value="<?= $State; ?>" size="45" />
                                    </td>
                                </tr>
                                <tr>
                                    <td style='font-weight:bold;'>
                                        <div align="left">Country : </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="country" id="country">
                                            <option>Select Country</option>
                                            <?php
                                            if ($pdo) {
                                                $query = "SELECT * FROM tblcountry";
                                                $result = select_pdo($query);
                                            } else {
                                                $result = $d->fetch("SELECT * FROM tblcountry");
                                            }
                                            foreach ($result as $row) {
                                                ?>
                                                <option value="<? echo $row['intId'];?>" <? if($row['intId']==$Country){echo "selected";}else{echo "";} ?>><?php echo $row['varCountry']; ?></option>
                                            <?php }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='font-weight:bold;'>
                                        <div align="left">Phone Number : </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input name="phone" type="text" id="country26" value="<?= $Phone; ?>" size="45" />
                                    </td>
                                </tr>
                                <tr>
                                    <td style='font-weight:bold;'>
                                        <div align="left">Fax Number : </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input name="fax" type="text" id="country27" value="<?= $Fax; ?>" size="45" />
                                    </td>
                                </tr>
                                <tr>
                                    <td width="39%" align="left" style='font-weight:bold;'>A little about you:</td>
                                </tr>
                                <tr>
                                    <td width="58%" >
                                        <textarea name=message wrap=physical cols=45 rows=5 onKeyDown="textCounter(this.form.message, this.form.remLen, 300);" onKeyUp="textCounter(this.form.message, this.form.remLen, 300);"><?php echo $old_bio; ?></textarea>
                                        <br />
                                        <input readonly type=text name=remLen size=3 maxlength=3 value="300" /> characters left
                                    </td>
                                </tr>
                                <tr>
                                    <td width="39%" align="left" style='font-weight:bold;'>
                                        Your Website:</td>
                                </tr>
                                <tr>
                                    <td width="58%">
                                        <input name="web" type="text" id="web" size="45" value="<?php echo $old_web ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp; </td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div align="center">
                                            <input type="submit" name="Submit" value="Update" onClick="return editaccount();" />
                                        </div>
                                    </td>
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
