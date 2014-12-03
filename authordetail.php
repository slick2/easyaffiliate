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
if($_REQUEST['autid'] == '')
  {
  $_SESSION['msg'] = "Missing Author ID. Exiting...";
	header("location:thankyou.php");
  die();
  }
if($_REQUEST['autid'] == -1)
{
$_SESSION['msg'] = "<br><br><font size='3' color='red'>Bad Author ID. Exiting...</font>";
header("location:thankyou.php");
die();
}
if (!is_numeric ($_REQUEST['autid']))
{
$_SESSION['msg'] = "<br><br><font size='3' color='red'>Bad Author ID. Exiting...</font>";
header("location:thankyou.php");
die();
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
 
$tmp_auth1 = sanitize_paranoid_string($_REQUEST['autid']);
$tmp_auth = $tmp_auth1;
$authorId= $tmp_auth;
if($pdo)
{
$query = "SELECT * FROM tblauthor where intId = ? AND intStatus = ?";
$bind = array($authorId,1);
$result1 = select_pdo($query,$bind,"tblauthor".$authorId.".af",3600);
}else{
$authorId = safeEscapeString($authorId);
$result1 = $d->fetch("SELECT * FROM tblauthor where intId = '$authorId' AND intStatus = 1","daily",md5("tblauthor".$authorId).".af)");
}
$authorname = stripString(htmlentities($result1[0]['varFirstName']))." ".stripString(htmlentities($result1[0]['varlastName']));
$photo = $result1[0]['authPhoto'];
$bio = stripString(convert($result1[0]['varBio']));
$web = stripString($result1[0]['website']);
$city = stripString(htmlentities($result1[0]['varCity']));
$state = stripString(htmlentities($result1[0]['varState']));
if($pdo)
{
$query = "SELECT textResource FROM tblarticles where intAuthorId = ? AND intStatus = ? LIMIT ?";
$bind = array($authorId,1,1);
$rows = select_pdo($query,$bind,"txtresource".$authorId.".af",3600);
}else{
$authorId = safeEscapeString($authorId);
$rows = $d->fetch("SELECT textResource FROM tblarticles where intAuthorId = '$authorId' AND intStatus = 1 LIMIT 1","daily",md5("txtresource".$authorId).".af");
}
$res_bio = stripString(convert($rows[0]['textResource']));   
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
      <?php echo $title; ?> | Author Detail
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
          <div class="article"><h2>
              <? echo $authorname?>'s Profile</h2>
            <p>&nbsp;
            </p>
            <table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">                    
              <tr>                      
                <td align="left" width="30%">Author Name : </td>                      
                <td bgcolor="#F0F0F0" align="left" width="70%">
                  <?php echo  $authorname ?> &nbsp;</td>                    
              </tr>                    
              <tr>                      
                <td align="left">City :</td>                      
                <td bgcolor="#F0F0F0" align="left">
                  <?php echo  $city ?> &nbsp;</td>                    
              </tr>                    
              <tr>                      
                <td align="left">State :</td>                      
                <td bgcolor="#F0F0F0" align="left">
                  <?php echo  $state ?> &nbsp;</td>                    
              </tr>                    
              <tr>                    
                <td align="left">Author Bio :</td>                     
                <td bgcolor="#F0F0F0" align="left">
<?php 
                        if ($bio){       
                               echo $bio;
                            }
                        else {
                                echo $res_bio;
                                 }    
                                                  ?>                      </td>                     
              </tr>                     
              <tr>                      
                <td align="left">Author's Website :</td>                      
                <td bgcolor="#F0F0F0" align="left">                      
<?php
                      if ($web){
                      echo $web ;
                      }else{
                      echo "No website submitted.";
                      }
                                        ?>                      </td>                      
              </tr>                 
              <tr>                      
                <td align="left">Photo :</td>                      
                <td align="left">
<?php 
  if($photo > ""){
   echo "<img src='author/".$photo."' style='border:1px solid;color: black;' alt='Article Friendly Author Photo'>";
    }else{
   echo "<img src='images/male_mem.jpg' style='border:1px solid;color: black' width='44' height='44' alt='Article Friendly Author Photo'>";
   }
                     ?></td>                    
              </tr>                    
              <tr>                      
                <td align="left">&nbsp;</td>                      <td>
                  <?php  ?>&nbsp;</td>                    
              </tr>                  
            </table>
<?php
if($pdo)
{
$query = "SELECT varArticleTitle,intCategory,intAuthorId,intId,intCategory
FROM tblarticles where intAuthorId = ? AND intStatus = ? ORDER BY ttSubmitDate DESC";
$bind = array($authorId,1);
$result1 = select_pdo($query,$bind,"auth_articles".$authorId.".af",3600);
}else{
$authorId = safeEscapeString($authorId);
$sql = "SELECT varArticleTitle,intCategory,intAuthorId,intId,intCategory
FROM tblarticles where intAuthorId = '$authorId' AND intStatus = 1 ORDER BY ttSubmitDate DESC";
$result1 = $d->fetch($sql,"daily",md5("auth_articles".$authorId).".af");
}
if($result1)
{
foreach ($result1 as $row)
{
$artname = stripString(htmlentities($row['varArticleTitle']));
$Categoryid = $row['intCategory'];
$authorId = $row['intAuthorId'];
if($pdo)
{
$query = "SELECT varCategory FROM tblcategories where intId = ?";
$bind = array($Categoryid);
$result2 = select_pdo($query,$bind);
}else{
$Categoryid = safeEscapeString($Categoryid);
$result2 = $d->fetch("SELECT * FROM tblcategories where intId = '$Categoryid'");
}
$catname  = stripString($result2[0]['varCategory']);
}
}
            ?>
            <div align="left">My Articles
            </div>
            <br>
            <ul>
<?
if(isset($result1))
{
foreach ($result1 as $row)
{
$the_title = stripString($row['varArticleTitle']);
              ?>
              <li>
              <a href="articledetail.php?artid=<? echo $row['intId'];?>&catid=<? echo $row['intCategory'];?>&title=
                <?php echo htmlentities($the_title) ?>" title="
                <? echo stripString(htmlentities($row['varArticleTitle']));?>">
                <? echo stripString(htmlentities($row['varArticleTitle']));?></a>
<?	}
}
              ?>
            </ul>
            <p>
            </p></td>
            </tr>
            <tr>
              <td class="fonttitle">Most Recent Articles </td>
            </tr>
            <tr><td>
<?php
if($pdo)
{
$query = "SELECT intTotalArticleinHome FROM tblsettings";
$resulting = select_pdo($query,"","total_artinhome.af",3600);
}else{
$resulting = $d->fetch("SELECT intTotalArticleinHome FROM tblsettings","daily","total_artinhome.af");
}
$homearticle = $resulting[0]['intTotalArticleinHome'];
if($pdo)
{
$query = "SELECT intId,intCategory,varArticleTitle FROM tblarticles where intStatus = ? ORDER BY ttSubmitDate DESC LIMIT 0 , ?";
$bind = array(1,$homearticle);
$result11 = select_pdo($query,$bind,"most_recent_art.af",3600);
}else{
$sql = "SELECT intId,intCategory,varArticleTitle
FROM tblarticles where intStatus = 1 ORDER BY ttSubmitDate DESC LIMIT 0 , '$homearticle'";
$result11 = $d->fetch($sql,"daily",md5("most_recent_art").".af");
}
                ?>
                <ul>
<?
if($result11)
{
foreach ($result11 as $rs)
{
$the_title = stripString($rs['varArticleTitle']);
                  ?>
                  <li>
                  <a href="articledetail.php?artid=<? echo $rs['intId'];?>&catid=<? echo $rs['intCategory'];?>&title=
                    <?php echo htmlentities($the_title) ?>" title="
                    <? echo stripString(htmlentities($rs['varArticleTitle']));?>">
                    <? echo stripString(htmlentities($rs['varArticleTitle']));?></a>
                  </li>
<?php
}
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