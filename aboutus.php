<?php
/**
 *
 * @package Article Friendly
 *
 */
///////////////////// TERMS OF USE //////////////////////////
//
//  1. You must keep a link to articlefriendly.com at the bottom of at least one page on the frontend pages.
//  2. You cannot give or sell AF Free to your friends family or anyone else. Anyone that wants AF Free
//     must signup for the download at articlefriendly.com.
//  3. You may use AF Free on as many of your own sites as you wish, but not for clients or others.
//     They must signup for their own copy of AF Free also.
//  4. You may not sell or change and claim AF Free as your own.
/////////////////////////////////////////////////////////////

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
$page = 'about';
require_once("system/config.inc.php");


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
<title><?php echo $title; ?> | About Us</title>
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
<p><?php
include("language.php");
?></p>
</div>
<div class="header_top"></div>
<div class="left">
<div class="left_side">

<?php require_once(INC.'/left.php'); ?>

</div>
<div class="right_side">
<div class="article">
<h2>About Us</h2>
<p>&nbsp;</p>
<p><?php echo $title; ?> is an online article directory for both publishers and authors. Formed in August of 2006,
We are striving to become a leader in the world of online publishing by providing syndication services to website owners, ezine publishers, and more. </p>

<p>In addition to our services for authors, webmasters may download our software that powers this site and start their own article directories.
We encourage you to weigh the benefits of developing your own article directory and challenge you to build a quality resource for your visitors. You can easily
customize our software to create niche directories, or broad directories...you have complete control. </p>
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
</div>
</body>
</html>
<?php
ob_end_flush();
?>