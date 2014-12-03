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
  
  
  if($_REQUEST['Cat'] == '')
  {
  $_SESSION['msg'] = "Missing Category ID. Exiting...";
	header("location:thankyou.php");
  die();
  }
    if($_REQUEST['Cat'] == -1)
  {
  $_SESSION['msg'] = "<center>Changing the URL to run remote scripts is FORBIDDEN and illegal.  Your IP has been recorded, and sent to the proper authorities.</center>";
		   header("location:thankyou.php");
       die();
  }
  if (!is_numeric ($_REQUEST['Cat']))
  {
   $_SESSION['msg'] = "<center>Changing the URL to run remote scripts is FORBIDDEN and illegal.  Your IP has been recorded, and sent to the proper authorities.</center>";
		   header("location:thankyou.php");
       die();
  }
  if (strlen($_REQUEST['Cat']) > 3)
  {
   $_SESSION['msg'] = "<center>Changing the URL to run remote scripts is FORBIDDEN and illegal.  Your IP has been recorded, and sent to the proper authorities.</center>";
		   header("location:thankyou.php");
       die();
  }
  if (strlen($_REQUEST['Cat']) == '')
  {
  $_SESSION['msg'] = "<center>Changing the URL to run remote scripts is FORBIDDEN and illegal.  Your IP has been recorded, and sent to the proper authorities.</center>";
		   header("location:thankyou.php");
       die();
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
    
 if(isset($_REQUEST['Cat']) && trim($_REQUEST['Cat'])!="")
{
$tmp_cat1 = sanitize_paranoid_string($_REQUEST['Cat']);
$thiscat=$tmp_cat1;
if($pdo)
{
$query = "SELECT varCategory FROM tblcategories WHERE intID = ?";
$bind = array($thiscat);
$fetch = select_pdo($query,$bind);
}else{
$thiscat = safeEscapeString($thiscat);
$fetch = $d->fetch("SELECT varCategory FROM tblcategories WHERE '$thiscat' = intID");
}
$mycat = stripslashes($fetch[0]['varCategory']);
}
$page = ''; 
  
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
      <?php echo $title; ?>
    </title>  
  </head>  
  <body>  
    <div class="content">  
      <div class="header_top">
      </div>  
      <div class="header">     
        <?php require_once(INC.'/menu.php'); ?>     
        <div class="sf_left">  
          <?php require_once(INC.'/logo.php'); ?>  
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
          <?php require_once(INC.'/left.php'); ?>      
        </div>  
        <div class="right_side">  
          <div class="article">  
            <!-- Breadcrumb -->  <h3>  
<?php
  Where($thiscat,$db,$pdo);
                ?>  
</h3>     <h2>Category List</h2>
<?php
/////////////////Pagnation Start //////////////////////
if (isset($_REQUEST['pageno']))
{
$tmp_page1 = sanitize_paranoid_string($_REQUEST['pageno']);
$pageno = $tmp_page1;
} else {
$pageno = 1;
}
$catagId= sanitize_paranoid_string($_REQUEST['Cat']);
if($pdo)
{
$query = "SELECT count(*) FROM tblarticles where intCategory = ? AND intStatus = ?";
$bind = array($catagId,1);
$query_data = select_pdo($query,$bind);
}else{
$catagId = safeEscapeString($catagId);
$query = "SELECT count(*) FROM tblarticles where intCategory = '$catagId' AND intStatus = 1";
$query_data = $d->fetch($query);
}
$numrows = count($query_data);
$rows_per_page = 15;
$lastpage = ceil($numrows/$rows_per_page);
$pageno = (int)$pageno;
if ($pageno < 1) {
$pageno = 1;
} elseif ($pageno > $lastpage) {
$pageno = $lastpage;
}
if($pdo)
{
$query = "SELECT * FROM tblarticles where intCategory = ? AND intStatus = 1 ORDER BY ttSubmitDate DESC LIMIT " .($pageno - 1) * $rows_per_page ."," .$rows_per_page;
$bind = array($catagId,);
$result1 = select_pdo($query,$bind);
}else{
$catagId = safeEscapeString($catagId);
$sql = "SELECT * FROM tblarticles where intCategory = '$catagId' AND intStatus = 1 ORDER BY ttSubmitDate DESC LIMIT " .($pageno - 1) * $rows_per_page ."," .$rows_per_page;
$result1 = $d->fetch($sql);
}
  
  if($result1)
  {$i=0;
  foreach ($result1 as $row)
  {$i=$i+1;
  
  $artname = stripString($row['varArticleTitle']);
  $Categoryid = stripString($row['intCategory']);
  $artsummery = stripString($row['textSummary']);
  $authorId = $row['intAuthorId'];
  
if($pdo)
{
$query = "SELECT * FROM tblauthor where intId = ?";
$bind = array($authorId);
$result2 = select_pdo($query,$bind);
}else{
  $result2 = $d->fetch("SELECT varFirstName,varlastName FROM tblauthor where intId = '".safeEscapeString($authorId)."'");
  }
  if($result2)
  {
  foreach ($result2 as $row2)
  {
  
  $authorname123 = stripString($row2['varFirstName'])." ".stripString($row2['varlastName']);
  $authorname123 = htmlentities($authorname123, ENT_QUOTES, "UTF-8");
  $the_title = stripString($row['varArticleTitle']);
  $the_title = htmlentities($the_title, ENT_QUOTES, "UTF-8");
              ?>  
            <ul>  
<?
  
                ?>  
              <li>
<a href="articledetail.php?artid=<? echo $row['intId'];?>&amp;catid=<? echo $row['intCategory'];?>&amp;title=<?php echo str_replace(" ","-",$the_title) ?>" title="<? echo $the_title;?>"><?php echo $the_title ?></a> 
              <em>By
              </em>:-
              <a href="authordetail.php?autid=<?=$row['intAuthorId']?>">
                <? echo $authorname123;?></a>
              <br>  
              <?php echo $artsummery; ?>  
              <br>  
            </ul>  
<?
  }
  }
  
  }
  }else{
  echo "<br><br><div align='left'>There are no articles in this category at this time, or the article you are looking for is in it's subcategory. 
  If you clicked on the 'Last' article link, <a href='javascript:history.back()'>click here</a> to go back!</div>";
  }
              ?>
            <p align='center'>  
<?php
  $level = sanitize_paranoid_string($_GET['level']);
  if(!empty($result1)){
  if ($pageno == 1) {
  echo " FIRST PREV ";
  } else {
  echo " <a href='categorydetail.php?pageno=1&amp;Cat=".$thiscat."&amp;level=".$level."'>FIRST</a> ";
  $prevpage = $pageno-1;
  echo "<a href='categorydetail.php?pageno=".$prevpage."&Cat=".$thiscat."&level=".$level."'>PREV</a> ";
  }
  
  echo " ( Page $pageno of $lastpage ) ";
  
  if ($pageno == $lastpage) {
  echo " NEXT LAST ";
  } else {
  $nextpage = $pageno+1;
  echo " <a href='categorydetail.php?pageno=".$nextpage."&amp;Cat=".$thiscat."&amp;level=".$level."'>NEXT</a> ";
  
  echo " <a href='categorydetail.php?pageno=".$lastpage."&amp;Cat=".$thiscat."&amp;level=".$level."'>LAST</a> ";
  
  }
  }
                ?>  
            </p>  
            <p>&nbsp;
            </p>  <h2>Most Recent Articles </h2>  
<?
  
if($pdo)
{
$query = "SELECT * FROM tblarticles where intStatus = ? ORDER BY ttSubmitDate DESC LIMIT 0 , ?";
$bind = array(1,$homearticle);
$result3 = select_pdo($query,$bind);
}else{
  $sql = "SELECT * FROM tblarticles where intStatus = 1 ORDER BY ttSubmitDate DESC LIMIT 0 , '".safeEscapeString($homearticle)."'";
  $result3 = $d->fetch($sql);
}  
              ?>  
            <ul style="list-style-image: url(images/a3.gif);">  
<?
  if(isset($result3))
  {
  
  foreach ($result3 as $row)
  {
  $the_title = stripString($row['varArticleTitle']);
  $the_title = htmlentities($the_title, ENT_QUOTES, "UTF-8");
                ?>  
              <li>
              <a href="articledetail.php?artid=<? echo $row['intId'];?>&amp;catid=<? echo $row['intCategory'];?>&amp;title=
                <?php echo str_replace(" ","-",$the_title) ?>" title="
                <? echo $the_title;?>">
                <? echo $the_title;?></a>        
<?	}
  }
                ?>   
            </ul>  
            <!-- End index text -->  
          </div>   
          <!-- End Content Area -->      
        </div>  
      </div>  
      <div class="right">     
        <?php require_once(INC.'/right.php'); ?>      
      </div>  
      <div class="header_bottom">
      </div>  
      <div class="footer">     
        <?php require_once(INC.'/footer.php'); ?>      
      </div>  
    </div>  
  </body>  
</html>  
<?php
   ob_end_flush();
  ?>