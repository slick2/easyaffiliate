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
if(!ob_start("ob_gzhandler")) ob_start();
session_start();
function EscapeString($string)
{
if(is_array($string))
{
return array_map(__METHOD__, $string);
}
if(!empty($string) && is_string($string))
{
return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $string);
}
}
if ( !get_magic_quotes_gpc() ) {
$_GET = array_map('EscapeString',$_GET);
$_POST = array_map('EscapeString',$_POST);
$_COOKIE = array_map('EscapeString',$_COOKIE);
$_REQUEST = array_map('EscapeString',$_REQUEST);
}
define('AFFREE', 1);
include("system/config.inc.php");
$_GET = array_map('stripslashes',$_GET);
$_POST = array_map('stripslashes',$_POST);
$_COOKIE = array_map('stripslashes',$_COOKIE);
$_REQUEST = array_map('stripslashes',$_REQUEST);
if(!isset($_SESSION['uid']) || trim($_SESSION['uid']) == ""){
header("location:login.php");
die();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <meta name="generator" content="HTML Tidy for Windows (vers 25 March 2009), see www.w3.org" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="DESCRIPTION" content="" />
    <meta name="KEYWORDS" content="" />
    <meta name="robots" content="index, follow" />
    <meta name="distribution" content="Global" />
    <meta name="rating" content="General" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <title>
      <?php echo $title; ?> | Article Detail
    </title>
    <script language="javascript" type="text/javascript" src="js/editaccount.js">
</script>
  </head>
  <body>
    <div class="content">
      <div class="header_top"></div>
      <div class="header">
        <?php require_once(INC.'/menu.php'); ?>
        <div class="sf_left">
          <?php require_once(INC.'/logo.php'); ?>
        </div>
      </div>
      <div class="header_bottom"></div>
      <div class="subheader">
        <p>
          <?php
          include("language.php");
                    ?>
        </p>
      </div>
      <div class="header_top"></div>
      <div class="left">
        <div class="left_side">
          <?php require_once(INC.'/left.php'); ?>
        </div>
        <div class="right_side">
          <div class="article">
            <h2>
              Article Detail
            </h2>
            <p>
              &nbsp;
            </p><?php
            if(isset($_REQUEST['a']) && trim($_REQUEST['a'])==4)
            {
            if(isset($_REQUEST['artid']) && trim($_REQUEST['artid'] != ""))
            {
            $blast = $_REQUEST['artid'];
            if (!is_numeric ( $blast)) {
            $_SESSION['msg'] = "<center>Changing the URL to run remote scripts is FORBIDDEN and illegal.  You IP has been recorded, and sent to the proper authorities.</center>";
            header("location:thankyou.php");
            die();
            }
            $tmp_art = "";
            $tmp_art = sanitize_paranoid_string($_REQUEST['artid']);
            $articleid =  $tmp_art;
            if($pdo)
            {
            $query = "select * from tblarticles WHERE intId= ?";
            $bind = array($articleid);
            $sql = select_pdo($query,$bind);
            }else{
            $articleid = safeEscapeString($articleid);
            $sql_select = "select * from tblarticles WHERE intId='".safeEscapeString($articleid)."'";
            $sql = $d->fetch($sql_select);
            }
            if(count($sql)>0)
            {
            foreach($sql as $row)
            {
            $title = stripString(convert($row['varArticleTitle']));
            $sum =   stripString(convert($row['textSummary']));
            $key = stripString(convert($row['varKeywords']));
            $tbody = stripString(convert($row['textArticleText']));
            $resource = stripString(convert($row['textResource']));
            }
                        ?>
            <table border="0" cellspacing="3" cellpadding="2" align="center">
              <tr class="line_top">
                <td width="95%">
                  <div align="center">
                    Article Detail
                  </div>
                </td>
                <td width="5%">
                  <div align="right">
                    <a class="line_top" href="myarticle.php">Back</a>
                  </div>
                </td>
              </tr>
            </table>
            <table width="90%" border="0" align="center" cellpadding="2" cellspacing="3" class="greyborder">
              <tr>
                <td valign="top">
                  <b>Article Title</b>
                </td>
              </tr>
              <tr>
                <td>
                  <?php echo $title; ?>
                </td>
              </tr>
              <tr>
                <td valign="top">
                  <b>Summary</b>
                </td>
              </tr>
              <tr>
                <td>
                  <?php echo $sum ;?>
                </td>
              </tr>
              <tr>
                <td valign="top">
                  <b>Keywords</b>
                </td>
              </tr>
              <tr>
                <td>
                  <?php echo $key ;?>
                </td>
              </tr>
              <tr>
                <td valign="top">
                  <b>Description</b>
                </td>
              </tr>
              <tr>
                <td>
                  <textarea name="textarea" cols="39" rows="17">
<?php echo $tbody ;?>
</textarea>
                </td>
              </tr>
              <tr>
                <td valign="top">
                  <b>Resources</b>
                </td>
              </tr>
              <tr>
                <td>
                  <textarea name="textarea2" cols="39" rows="5">
<?php echo $resource ;?>
</textarea>
                </td>
              </tr>
              <tr>
                <td>
                  &nbsp;
                </td>
                <td>
                  &nbsp;
                </td>
              </tr>
            </table><?php
            }
            }
            }
            ?>
            <!-- End index text -->
          </div>
          <!-- End Content Area -->
        </div>
      </div>
      <div class="right">
        <?php require_once(INC.'/right.php'); ?>
      </div>
      <div class="header_bottom"></div>
      <div class="footer">
        <?php require_once(INC.'/footer.php'); ?>
      </div>
    </div><?php
    ob_end_flush();
    ?>
  </body>
</html>