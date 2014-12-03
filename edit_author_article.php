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
  $_SESSION['msg'] = "<p>Access Denied. Please login first!</p>";
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
  
  if(isset($_POST['textarea']))
  {
  if(stristr($_POST['textarea'], '\n') == FALSE) 
  {
  $_POST['summery'] = nl2br($_POST['summery']);
  $_POST['textarea'] = nl2br($_POST['textarea']);
  $_POST['resources'] = nl2br($_POST['resources']);
  }else{
  $_POST['summery'] = n2br($_POST['summery']);
  $_POST['textarea'] = n2br($_POST['textarea']);
  $_POST['resources'] = n2br($_POST['resources']);
  }
  }
    $_POST = array_map('stripslashes',$_POST);
    $_REQUEST = array_map('stripslashes',$_REQUEST);
   // Function for displaying categories in drop down menu
  function GetChild($ParentID,$num,$selected,$pdo,$check)
  {
      if(isset($_SESSION['pdo']) && $_SESSION['pdo'] == true)
       {
        $pdo = true;
       }else{
        $pdo = false;
       }
  		$value = "";
  		$c="";
      
      $d = new db(0);
      
      for($i=0;$i<$num;$i++)
  		{
  			$c=$c."&nbsp;&nbsp;-&nbsp;&nbsp;";
  		}
      
  if($pdo)
  {
  $query = "SELECT intID,varCategory FROM tblcategories WHERE intParentID = ? ORDER BY varCategory ASC";
  $bind = array($ParentID);
  $RsC = select_pdo($query,$bind);
  
  }else{
  	$ParentID = safeEscapeString($ParentID);		
  	$query_RsC = "SELECT intID,varCategory FROM tblcategories WHERE intParentID = ".$ParentID." ORDER BY varCategory ASC";
  	$RsC = $d->fetch($query_RsC);
     }
   
  $cnt = count($RsC);
  
  if($RsC)
  		{
  			for($i=0;$i<$cnt;$i++)
  			{
  				if($check==1)
  				{
  						if($selected==$RsC[$i]['intID'])
  						{
  						$value=$value."<option value='".$RsC[$i]['intID']."' selected>".$c.stripString($RsC[$i]['varCategory'])."</option>";		
  						}
  						else
  						{
  						$value=$value."<option value='".$RsC[$i]['intID']."' >".$c.stripString($RsC[$i]['varCategory'])."</option>";		
  						}
  				}
          
  				$value=$value."".GetChild($RsC[$i]['intID'],$num+1,$selected,$pdo,$check);		
  			 }
  		 }
  	return $value;
  }
  
  if(isset($_REQUEST['a']) && trim($_REQUEST['a'])==2)
  {
  if(isset($_REQUEST['artid']) && trim($_REQUEST['artid'] != ""))
  {
  $blast = $_REQUEST['artid'];
  if (preg_match ("/^([0-9.,-]+)$/", $blast)) {
  $blast = "true";
  } else {
  $_SESSION['msg'] = "<center>Changing the URL to run remote scripts is FORBIDDEN and illegal.  You IP has been recorded, and sent to the proper authorities.</center>";
  header("location:thankyou.php");
  die();
  }
  $tmp_id = "";
  $tmp_id = sanitize_paranoid_string($_REQUEST['artid']);
  if($pdo)
  {
  $articleid =  $tmp_id;
  $query = "select * from tblarticles where intId = ?";
  $bind = array($articleid);
  $result = select_pdo($query,$bind);
  }else{
  $articleid =  safeEscapeString($tmp_id);
  $sql = "select * from tblarticles where intId ='$articleid'";
  $result = $d->fetch($sql);
  }
  
  // IF there is no records in database
  if(count($result)<=0)
  {
  
  $_SESSION['msg'] = "No Record Found! Author ID is not valid!";
  header("location:thankyou.php");
  die();
  
  }
  
  // If there is records in database it will be checked to make sure
  // the author ID's match
  
  if($result)
  {
  foreach($result as $row)
  {
  $AuthorId = stripString(trim($row['intAuthorId']));
  $Category = stripString($row['intCategory']);
  $ArticleTitle = stripString($row['varArticleTitle']);
  $Summary = stripString($row['textSummary']);
  $Keywords = stripString($row['varKeywords']);
  $ArticleText = stripString($row['textArticleText']);
  $Resource = stripString($row['textResource']);
  
  }
  
  $id_check = $_SESSION['uid'];
  if($id_check != $AuthorId)
  {
  $_SESSION['msg'] = "Wrong Author ID. Cannot Update.";
  unset($_SESSION['uid']);
  header("location:thankyou.php");
  die();
  }
  
  }
  }
  }
  // Update operation
  if(isset($_POST['Submit']) && trim($_POST['Submit']) == "Update")
  {
  
  require_once("htmlpurifier/library/HTMLPurifier.auto.php");
  $config = HTMLPurifier_Config::createDefault();
  $purifier = new HTMLPurifier($config);
  $intAuthorId = sanitize_paranoid_string($_POST['author']);
  $id_check = $_SESSION['uid'];
  if($id_check != $intAuthorId)
  {
  $_SESSION['msg'] = "Wrong Author ID. Cannot Update.";
  header("location:thankyou.php");
  die();
  }
  $intCategory = sanitize_paranoid_string($_POST['category']);
  $varArticleTitle = stripslashes(convert($_POST['title']));
  $varArticleTitle = $purifier->purify($varArticleTitle);
  $textSummary = stripslashes(convert($_POST['summery']));
  $textSummary = $purifier->purify($textSummary);
  $varKeywords = stripslashes(convert($_POST['keywords']));
  $varKeywords = $purifier->purify($varKeywords);
  $textArticleText = stripslashes(convert($_POST['textarea']));
  $textArticleText = $purifier->purify($textArticleText);
  $textResource = stripslashes(convert($_POST['resources']));
  $textResource = $purifier->purify($textResource);
  if($pdo)
  {
  $query = "UPDATE tblarticles SET intAuthorId = ?, intCategory = ?,
  varArticleTitle = ?, textResource = ?, textSummary = ?,
  varKeywords = ?, textArticleText = ?, intStatus = ?,
  ttSubmitDate = NOW() WHERE intId =?";
  $bind = array($intAuthorId,$intCategory,$varArticleTitle,$textResource,$textSummary,$varKeywords,$textArticleText,0,$articleid);
  $result = update_pdo($query,$bind);
  }else{
  $sql_upd ="UPDATE tblarticles SET intAuthorId = '".safeEscapeString($intAuthorId)."', intCategory = '".safeEscapeString($intCategory)."',
  varArticleTitle = '".safeEscapeString($varArticleTitle)."', textResource = '".safeEscapeString($textResource)."',
  textSummary = '".safeEscapeString($textSummary)."', varKeywords = '".safeEscapeString($varKeywords)."',
  textArticleText = '".safeEscapeString($textArticleText)."', intStatus = '0',
  ttSubmitDate = NOW() WHERE intId ='".safeEscapeString($articleid)."'";
  
  $result = $d->fetch($sql_upd);
  }
  cache_cleanup();
  $_SESSION['msg'] = "Your article had been successfully updated.";
  header("location:thankyou.php");
  die();
  }
  //  End of updation
  
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
      <?php echo $title; ?> | Edit Article
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
          <div class="article"><h2>Edit Article</h2>
            <p>&nbsp;
            </p>
            <form name="form11" method="post" action="">   	
              <table  width="90%" border="0" cellspacing="1" cellpadding="1" class="greyborder" align="center">      
                <tr>        <td>&nbsp;</td>        <td>&nbsp;</td>      
                </tr>	  
                <tr>        <td>Author : </td>        <td>
                    <select name="author" id="author">          
                      <option>Select Author
                      </option>		  
<?php
  if($pdo)
  {
  $query = "select intId,varFirstName,varlastName from tblauthor WHERE intId = ?";
  $bind = array($AuthorId);
  $author = select_pdo($query,$bind);
  }else{
  $sql="select intId,varFirstName,varlastName from tblauthor WHERE intId = '".safeEscapeString($AuthorId)."'";
  $author=$d->fetch($sql);
  }
  foreach($author as $row) {
  $a_name = stripslashes($row['varFirstName'])." ".stripslashes($row['varlastName']);
                        ?>  
                      <option value="<?php echo $row['intId'];?>" selected>
                      <?php echo $a_name; ?>
                      </option>  
<?php }
                        ?>  
                    </select></td>  
                </tr>  
                <tr>  <td>Category :</td>  <td>
                    <select name="category" id="category">  
                      <option value="0">ROOT
                      </option>  
<?php
  if($action == 2)
  {
  $selected = $parent_id;
  }else{
  $selected = 0;
  }
  echo GetChild(0,0,$selected,$db,1);
  if($pdo)
  {
  $query = "select * from tblcategories";
  $author = select_pdo($query);
  }else{
  $sql="select * from tblcategories";
  $author=$d->fetch($sql);
  }
		  foreach($author as $row) {
                      				?>		
                      <option value="<?php echo $row['intID'];?>" <?php if($row['intID']==$Category){echo "selected";}else{echo "";}?>>
                      <?php echo $row['varCategory'];?>
                      </option>		
                      <?php } ?>        
                    </select></td>      
                </tr>      
                <tr>        <td> Article Title : </td>        <td>
                    <input name="title" type="text" id="title" size="50" value="<?php echo convert($ArticleTitle);?>"></td>      
                </tr>      
                <tr>        
                  <td valign="top"> Summary : </td>        <td>
<textarea name="summery" cols="39" rows="4" id="summery"><?php echo convert($Summary);?></textarea></td>      
                </tr>      
                <tr>        <td> Keywords : </td>        <td>
                    <input name="keywords" type="text" id="keywords" size="50" value="<?php echo convert($Keywords);?>"></td>      
                </tr>      
                <tr>        
                  <td valign="top"> Article Text : </td>        <td>
<textarea name="textarea" cols="39" rows="6"><?php echo convert($ArticleText);?></textarea></td>      
                </tr>      
                <tr>        
                  <td valign="top"> Resources : </td>        <td>
<textarea name="resources" cols="39" rows="6" id="resources"><?php echo convert($Resource);?>
        </textarea></td>      
                </tr>      
                <tr>        <td>&nbsp;</td>        <td>&nbsp;</td>      
                </tr>      
                <tr>        
                  <td colspan="2">
                    <div align="center">          
                      <input type="submit" name="Submit" value="Update">        
                    </div></td>    
              </table>	       
            </form>  
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