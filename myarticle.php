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
 if(!isset($_SESSION['uid']) || trim($_SESSION['uid']) == ""){
  header("location:login.php");
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
    $_POST = array_map('stripslashes',$_POST);
    $_REQUEST = array_map('stripslashes',$_REQUEST);
    
if (isset($_REQUEST['pageno'])) { // check if we are supposed to get another page from the pagnation results or is this the first page?
$pageno = sanitize_paranoid_string($_REQUEST['pageno']); // It's a continued page
} else {
$pageno = 1;  // Nope, it's the first page of results
}
$pageno = (int)$pageno;
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
      <?php echo $title; ?> | My Article
    </title>  
<script language="javascript" type="text/javascript" src="js/editaccount.js"></script>  
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
          <div class="article"><h2>My Article</h2>
            <p>&nbsp;
            </p>
            <table width="90%"  border="0" align="center" cellpadding="2" cellspacing="2" class="greyborder">		  
              <tr >			<td><b>Article Title</b></td>			<td><b>Detail</b></td>			<td><b>
                    <div align="center">Edit
                    </div></b></td>			<td><b>
                    <div align="center">Delete
                    </div></b></td>		  
              </tr>		   		   
<?php
// DELETE operation of article
if(isset($_REQUEST['a']) && trim($_REQUEST['a'])==3)
{
	if(isset($_REQUEST['artid']) && trim($_REQUEST['artid'] != ""))
	{
  
  
  	
		$articleid =  sanitize_paranoid_string($_REQUEST['artid']);
    
    if(!is_numeric($articleid))
    {
     $_SESSION['msg'] = "Invalid Article ID..";
		header("location:thankyou.php");
		die();
    }
    
if($pdo)
{
$query = "select intAuthorId from tblarticles where intId = ?";
$bind = array($articleid);
$result = select_pdo($query,$bind);
}else{
    
    $sql = "select intAuthorId from tblarticles where intId ='".safeEscapeString($articleid)."'";
		$result = $d->fetch($sql);
}
     if(!$result)
     {
    $_SESSION['msg'] = "<font color='red'>Invalid! Author not found..</font>";
		header("location:thankyou.php");
		die();
     }else{
     $check_id = trim($result[0]['intAuthorId']);
     if($check_id != trim($_SESSION['uid']))
     {
    $_SESSION['msg'] = "<font color='red'>Invalid! Author Not Matched..</font>";
		header("location:thankyou.php");
		die();
     }
     }
    if($pdo)
{
$query = "Delete from tblarticles where intId = ? AND intAuthorId = ?";
$bind = array($articleid,$check_id);
$del = delete_pdo($query,$bind);
}else{
		$sql_del = "Delete from tblarticles where intId ='".safeEscapeString($articleid)."' AND intAuthorId = '$check_id'";
		$del = $d->exec($sql_del);
}		
		$_SESSION['msg'] = "Your article was successfully deleted.";
		header("location:thankyou.php");
		die();
	}
}
// End of DELETE operation
	

	$authorId =  $_SESSION['uid'];
		/**************************************
		PAGING CODE START
		**************************************/
		//$rowperpage=5;
		$tablename="tblarticles";
		$per_page_keywords = "intAuthorId = '$authorId'";
		$per_page_sorts = "";

		
		/**************************************
		PAGING CODE ENDING
		**************************************/
   $rows_per_page = 25;
   
if($pdo)
{
$query = "SELECT count(*) FROM tblarticles WHERE intAuthorId = ?";
$bind = array($authorId);
$result = select_pdo($query,$bind);
}else{    
$query = "SELECT count(*) FROM tblarticles WHERE intAuthorId = '$authorId'";
$result = $d->fetch($query);
}
if($result){
$numrows = $result[0]['count(*)'];

$lastpage = ceil($numrows/$rows_per_page);

if ($pageno < 1) {
$pageno = 1;
} elseif ($pageno > $lastpage) {
$pageno = $lastpage;
}

if($pdo)
{
$limit = ($pageno - 1) * $rows_per_page;
$query = "SELECT intId,varArticleTitle FROM tblarticles WHERE intAuthorId = ? LIMIT ?,?";
$bind = array($authorId,$limit,$rows_per_page);
$sql = select_pdo($query,$bind);
}else{
$sql_query = "SELECT intId,varArticleTitle FROM tblarticles WHERE intAuthorId = '$authorId' LIMIT " .($pageno - 1) * $rows_per_page ."," .$rows_per_page;
$sql =$d->fetch($sql_query);
}

	if($sql)
	{
	$i=0;
	 foreach($sql as $row)
	 { 
$title = myTruncate($row['varArticleTitle'], 35, " ")
              		  
?> 			
              <tr>				<td>
                  <?php echo stripString(convert($title)); ?></td>				<td>
                  <a class="link" href="article_detail.php?a=4&artid=<?php echo $row['intId']; ?>&pageno=<?php echo $pageno ?>">Detail</a></td>				
                <td align="center">
                  <a class="link" href="edit_author_article.php?a=2&artid=<?php echo $row['intId']; ?>&pageno=<?php echo $pageno ?>"> Edit </a></td>				
                <td align="center">
                  <a class="link" href="myarticle.php?a=3&artid=<?php echo $row['intId']; ?>&pageno=<?php echo $pageno ?>" onClick="return confirm('Are you sure you wish to delete this record ?');"> Delete </a></td>	        
              </tr>  
<?php 
	}
 }else{
echo "<p>Sorry, but no articles found for your acccount.</p>"; 
 }	

                 ?>   
            </table>
<?php
echo "<br><br><br><p align='center'>";
if ($pageno == 1) {
echo " FIRST PREV ";
} else {
echo " <a href='myarticle.php?pageno=1'>FIRST</a> ";
$prevpage = $pageno-1;
echo "<a href='myarticle.php?pageno=".$prevpage."'>PREV</a> ";
}
echo " ( Page $pageno of $lastpage ) ";
if ($pageno == $lastpage) {
echo " NEXT LAST ";
} else {
$nextpage = $pageno+1;
echo " <a href='myarticle.php?pageno=".$nextpage."'>NEXT</a> ";
echo " <a href='myarticle.php?pageno=".$lastpage.">LAST</a> ";
echo "</p>";
}

}else{
echo "<p> No articles found for your acccount.</p>";
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