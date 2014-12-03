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
  $_SESSION['msg'] = '<font color="red">Sorry, but you must have an account and be logged in to add your link!</font>';
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
$page = "links";
include("system/config.inc.php");
//lets use md5 to generate a totally random string
  $md5 = md5(microtime() * mktime());
  
  /*
  We dont need a 32 character long string so we trim it down to 5
  */
  $string = substr($md5,0,5);
  
  $_SESSION['key'] = md5($string);
  
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
      <?php echo $title; ?> | Add Link
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
          <div class="article"><h2>Add Link</h2>
            <p>&nbsp;
            </p>
            <table height="50"  border="0" align="center" cellpadding="0" cellspacing="0">                
              <tr>                  
                <td align="center" valign="top" class="inputlabel">
                  <form name="links" action="links_submit.php" method="POST"><strong>
                      <br>
                      <br>                    Please fill out all fields, then click "Submit" and we will review your link asap!</strong>
                    <br>
                    <br>
                    <br>
                    <table border="0" width="75%">                     
                      <tr>                       
                        <td align="left">First Name</td>                       
                        <td align="left">
                          <input type="text" name="user_fname" MAXLENGTH="50" SIZE="40"></td>                      
                      </tr>                      
                      <tr>                       
                        <td align="left">Last Name</td>                       
                        <td align="left">
                          <input type="text" name="user_lname" MAXLENGTH="50" SIZE="40"></td>                      
                      </tr>
                      <tr>                       
                        <td align="left">Your Email:</td>                       
                        <td align="left">
                          <input type="text" name="user_email" MAXLENGTH="50" SIZE="30">                      </td>
                      </tr>
                      <tr>                       
                        <td align="left">Your Sites Name</td>                       
                        <td align="left">
                          <input type="text" name="site_name" MAXLENGTH="75" SIZE="40"></td>                      
                      </tr>
                      <tr>                       
                        <td align="left">Site Address:</td>                       
                        <td align="left">
                          <input type="text" name="site_addy" MAXLENGTH="75" SIZE="40" value="http://"></td>                      
                      </tr>
                      <tr>                       
                        <td align="left">Site Desc:</td>                       
                        <td align="left">
                          <input type="text" name="site_desc" MAXLENGTH="300" SIZE="40"></td>                      
                      </tr>
                      <tr>                       
                        <td colspan="2">
                          <br>A recipical link is not required, but certainly appreciated. Please use the link                         code below to add us to your site!
                          <br></td>
                      </tr>                       
                      <tr>
                        <td colspan="2">
<textarea readonly="yes" cols="50" rows="8"><?php require_once('linktxt.php') ?></textarea>                       </td>
                      </tr>
                      <tr>
                        <td colspan="2">                       
                          <table width="100%">     
                            <tr>       
                              <td align="center" background="images/captcha.png" style="padding-left:5px;" colspan="1">
                                <font color="white" size="3"><b>
                                    <?php echo $string ?></b>
                                </font></td>       
                              <td align="left" style="padding-left:5px;" colspan="1">&nbsp;&nbsp;&nbsp;Enter the white text&nbsp;&nbsp;
                                <input name="data" type="text" size="5" value="">
          </div></td>      
          </tr>     
          </table> 
          <br>  </td>
          </tr>                       
          <tr><td>
              <input type="submit" value="Submit"></td>
          </tr>                      
          </table>                    </td>                
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