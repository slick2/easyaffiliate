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
$tmp_art = "";
$tmp_art = stripslashes(strip_tags($_REQUEST['artname']));
$artname = convert($tmp_art);
if($pdo)
{
$query = "SELECT * FROM tblarticles where varArticleTitle = ?";
$bind = array($artname);
$result = select_pdo($query,$bind);
}else{
$artname = safeEscapeString($artname);
$result = $d->fetch("SELECT * FROM tblarticles where varArticleTitle = '$artname'");
}
if($result)
					{
					foreach ($result as $row)
					{
						
						$artname = stripString(convert($row['varArticleTitle']));
						$arttext = stripString(convert($row['textArticleText']));
						$authorId = stripString(convert($row['intAuthorId']));
						$artresorce = stripString(convert($row['textResource']));
if($pdo)
{
$query = "SELECT * FROM tblauthor where intId = '$authorId'";
$bind = array();
$result1 = select_pdo($query,$bind);
}else{
$authorId = safeEscapeString($authorId);
$result1 = $d->fetch("SELECT * FROM tblauthor where intId = '$authorId'");
}
						$authorname = stripString($result1[0]['varFirstName'])." ".stripString($result1[0]['varlastName']);
			
					}
					}
          
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
      <?php echo $title; ?> | Terms
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
          <div class="article"><h2>Print Article</h2>
            <p>&nbsp;
            </p>
            <table cellspacing="2" cellpadding="5" width="100%">
              <tr><td>
                  <p class="articletitle">
                    <?=$artname;?>- 
                    <span style="font-weight: 400">
                      <font size="1" color="#000080">By: 
                        <?=$authorname?>
                      </font>
                    </span>
                  </p>
                  <p class="articletext"><b>Description : </b>
                    <?=str_replace("\n","<BR>",$arttext)?>
                  </p> 
                  <p class="articletext">              <b>Article Source : </b>              
                    <?php echo "<a href='".$site_URL."'>".$site_URL."</a>" ?>            
                  </p>
                  <p class="articletext"><b>Author Resource : </b>
                    <?=str_replace("\n","<BR>",$artresorce)?>
                  </p>  
<script language="JavaScript">  window.print();  </script>     
                  <br>	  
                  <br>	   
                  <br> 
                  <br> 
                  <br> 
                  <br>  
                  <table width="100%"  border="0" cellspacing="1" cellpadding="1">    
                    <tr>      <td>
                        <div align="right">        
                          <input name="Close" type="submit" id="Close" value="Close" onClick="window.close();">      
                        </div></td>      <td>&nbsp;</td>      <td>
                        <input name="Print" type="submit" id="Print" value="Print" onClick="window.print();"></td>    
                    </tr>  
                  </table>  </td>
              </tr>
            </table>  
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