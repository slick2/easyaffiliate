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
    <title>
      <?php echo $title; ?> | Search
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
          <div class="article"><h2>Search Result</h2>
            <p>&nbsp;
            </p>
<?php
if(isset($_REQUEST['q']) && trim($_REQUEST['q']) != "")
{
$tmp_key = ""	;
$tmp_key = strip_tags($_REQUEST['q']);
$keyword = $tmp_key;
if(strlen($keyword) < 3)
{
$_SESSION['msg'] = "Sorry, but your search term was below 3 characters. Please lengthen your search term.";
header("location:thankyou.php");
die();
}
if(strlen($keyword) > 50)
{
$_SESSION['msg'] = "Sorry, but your search term was above 50 characters. Please shorten your search term.";
header("location:thankyou.php");
die();
}
$searchstring = strip_tags($_REQUEST['q']);
if($pdo)
{
$query = "SELECT intId,varArticleTitle,intCategory,textSummary,intAuthorId FROM tblarticles WHERE varArticleTitle like ? AND 
intStatus = ? OR textArticleText like ? AND intStatus = ? 
ORDER BY ttSubmitDate DESC LIMIT 50";
$bind = array("%".$keyword."%",1,"%".$keyword."%",1);
$result1 = select_pdo($query,$bind);
}else{
$keyword = safeEscapeString($keyword);
$sql = "SELECT intId,varArticleTitle,intCategory,textSummary,intAuthorId FROM tblarticles WHERE varArticleTitle like '%$keyword%' 
AND intStatus = '1' OR textArticleText like '%$keyword%' AND intStatus = '1' 
ORDER BY ttSubmitDate DESC LIMIT 50";
$result1 = $d->fetch($sql);
}
if($result1)
{
foreach ($result1 as $row)
{
$artname = stripString(convert($row['varArticleTitle']));
$Categoryid = $row['intCategory'];
$artsummery = stripString(convert($row['textSummary']));
$authorId = $row['intAuthorId'];
if($pdo)
{
$query = "SELECT * FROM tblauthor where intId = ?";
$bind = array($authorId);
$result2 = select_pdo($query,$bind);
}else{
$result2 = $d->fetch("SELECT * FROM tblauthor where intId = '$authorId'");
}
if($pdo)
{
$query = "SELECT varCategory FROM tblcategories WHERE intID = ?";
$bind = array($Categoryid);
$cat_sql = select_pdo($query,$bind);
}else{
$cat_sql = $d->fetch("SELECT varCategory FROM tblcategories WHERE intID = '".safeEscapeString($Categoryid)."'");
}
if($result2)
{$i=0;
$j=0;
foreach ($result2 as $row2)
{
//$i=$i+1;
$authorname123 = stripString($row2['varFirstName'])." ".stripString($row2['varlastName']);
foreach ($cat_sql as $row3)
{
$category_name = stripString($row3['varCategory']);
            ?>
            <ul>
<?
              ?>
              <li>
              <a href="articledetail.php?artid=<? echo $row['intId'];?>&catid=<? echo $row['intCategory'];?>"> 
                <?php echo stripString(convert($row['varArticleTitle']));?></a>
              <em>By 
              </em>:- 
              <a href="authordetail.php?autid=<?=$row['intAuthorId']?>">
                <?php echo $authorname123;?></a>
              <br>
              <?php echo $artsummery; ?>
              <br><b>In Category</b> - <i>
                <?php echo $category_name ?></i>
              <br>
            </ul>
<?php
}
}
}
}
}
else
{
$_SESSION['msg'] = "Sorry, No Results Found for ".htmlentities($keyword, ENT_QUOTES, "UTF-8").".";
header("location:thankyou.php");
die();
}
}
            ?></td>
            </tr>
            </table>
            <p class="fonttitle">Most Recent Articles 
            </p>
            <p>
<?
if($pdo)
{
$query = "SELECT intTotalArticleinHome FROM tblsettings";
$result = select_pdo($query,"","total_artinhome.af",3600);
}else{
$result = $d->fetch("SELECT intTotalArticleinHome FROM tblsettings","daily","total_artinhome.af");
}
$homearticle = $result[0]['intTotalArticleinHome'];
if($pdo)
{
$query = "SELECT intId,intCategory,varArticleTitle FROM tblarticles where intStatus = ? ORDER BY ttSubmitDate DESC LIMIT 0 , ?";
$bind = array(1,$homearticle);
$result1 = select_pdo($query,$bind,"most_recent_art.af",3600);
}else{
$sql = "SELECT intId,intCategory,varArticleTitle
FROM tblarticles where intStatus = 1 ORDER BY ttSubmitDate DESC LIMIT 0 , '".safeEscapeString($homearticle)."'";
$result1 = $d->fetch($sql,"daily",md5("most_recent_art").".af");
}
              ?>
              <ul>
<?
if(isset($result1))
{
foreach ($result1 as $row)
{
                ?>
                <li>
                <a href="articledetail.php?artid=<? echo $row['intId'];?>&catid=<? echo $row['intCategory'];?>"> 
                  <? echo stripslashes(utf8_encode($row['varArticleTitle']));?></a>
                <br>
<?	}
}
                ?>
              </ul>
            </p>  
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