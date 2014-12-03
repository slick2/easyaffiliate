<?php
if (!$_POST)
{
echo "You do not have authority to view this page!";
die();
}
$ip = $_SERVER["REMOTE_ADDR"] ;

// check for contentcrooner.com's ip address. Die if not right
if($ip != '174.129.237.211')
{
die("bad IP");
}

if(isset($_POST['title']) && isset($_POST['article']) && isset($_POST['resource_box']))
{
$_POST['title'] = n2br($_POST['title']);
$_POST['article'] = n2br($_POST['article']);
$_POST['description']  = n2br($_POST['description']);
$_POST['keywords']  = n2br($_POST['keywords']);
$_POST['resource_box']  = n2br($_POST['resource_box']);
}

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

$_POST = array_map('EscapeString',$_POST);
$_REQUEST = array_map('EscapeString',$_REQUEST);
}

define('AFFREE', 1);
require_once("system/config.inc.php");


$_POST = array_map('stripslashes',$_POST);
$_REQUEST = array_map('stripslashes',$_REQUEST);

if($pdo)
{
$tmp_email = $_POST['email'];
$query = "SELECT intId FROM tblauthor WHERE varEmail = ?";
$bind = array($tmp_email);
$result = select_pdo($query,$bind);
}else{

$result = $d->fetch("SELECT intId FROM tblauthor WHERE varEmail='".safeEscapeString($_POST['email'])."'");
}
if($result)
{
$user_id=$result[0]['intId'];
}
else
{
die();

}

if(stristr($_POST['category'], '/'))
{
$cat_sent = explode('/', $_POST['category']);
$last = array_pop($cat_sent);
}else{
$last = $_POST['category'];
}

if($pdo)
{
$query = "SELECT intID FROM tblcategories WHERE varCategory=?";
$bind = array($last);
$result = select_pdo($query,$bind);
if($result)
{
$category_id=$result[0]['intID'];
}else{
die();
}
}else{
$result=$d->fetch("SELECT intID FROM tblcategories WHERE varCategory='".safeEscapeString($last)."'");
if($result)
{
$category_id=$result[0]['intID'];
}else{
die();
}
}

if ($_POST['title'] == "Test Title"){

$category_id = 1;

}
if ($category_id == 0){
print ("<a href='http://www.articlefriendly.com'>Article Friendly NO CAT ID HERE</a>");
die();
}



if($pdo)
{
$a_title = convert($_POST['title']);
$a_art = convert($_POST['article']);
$a_desc = convert($_POST['description']);
$a_key = convert($_POST['keywords']);
$a_res = convert($_POST['resource_box']);

$query = "INSERT INTO tblarticles(intAuthorId, intCategory, varArticleTitle, textArticleText, intStatus, textSummary, varKeywords, textResource,
ttSubmitDate) VALUES(?, ?, ?, ?, '1',
?,?,?, '".date("Y-m-d G:i:s")."')";
$bind = array($user_id,$category_id,$a_title,$a_art,$a_desc,$a_key,$a_res);
$result = select_pdo($query,$bind);

}else{

$a_title = convert(safeEscapeString($_POST['title']));
$a_art = convert(safeEscapeString($_POST['article']));
$a_desc = convert(safeEscapeString($_POST['description']));
$a_key = convert(safeEscapeString($_POST['keywords']));
$a_res = convert(safeEscapeString($_POST['resource_box']));

$result =  $d->fetch("INSERT INTO tblarticles(intAuthorId, intCategory, varArticleTitle, textArticleText, intStatus, textSummary, varKeywords, textResource,
ttSubmitDate) VALUES('$user_id', '$category_id', '$a_title', '$a_art', '1',
'$a_desc','$a_key',
'$a_res', '".date("Y-m-d G:i:s")."')");
}
if($result)
{
echo "Submission Success";
die();
}else{
error_log('Could not insert into database');
echo "Submission Failure";
die();
}
?>
