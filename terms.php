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
$page = 'terms';
include("system/config.inc.php");
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
          <div class="article"><h2>Terms &amp; Guidelines</h2>
            <p>&nbsp;
            </p>
            <p>The rules for submitting your articles to 
              <?php echo $title; ?> are simple, but they must be followed!        		  
              <ul>                 
                <li><strong>The articles that you submit must be your own work - </strong> You may not submit articles written by other authors and the content must be unique.  No rebranded articles allowed, you must be the sole copyright holder for each article you submit. Articles written by a ghost writer are perfectly acceptable  as long as they are  being used only by yourself.                  
                <li><strong>By submitting your articles to our site, you grant us the right to publish your articles - </strong>  We may publish your article anywhere on our website, blog, or syndicate through RSS. We may also change where it appears, including the category, at any time. 
                <li><strong>By submitting  your articles to 
                  <?php echo $title; ?> , you grant others the right to publish your articles - </strong> Other 
                <?php echo $title; ?> users have the right to  publish your articles on their websites, in their newsletters or ezines, and more as long as the article is left in the original state.  This includes the resource box which provides you credit as the author of the content.                  
                <li><strong>No affiliate links! - </strong> The articles that you submit to 
                <?php echo $title; ?> should not contain affiliate links. It is acceptable,  however, to mention the URL's of helpful sites or your own website which redirects to the recommended affiliate product.                  
                <li><strong>Submitting your articles to 
                  <?php echo $title; ?> does not entitle you to financial compensation of any kind - </strong> You will not  receive compensation from 
                <?php echo $title; ?>  or the users of our directory for articles you submit.                  
                <li><strong>Submitting an article in no ways guarantees inclusion in our directory - </strong> We reserve the right to reject an article submission for  any reason.                  
                <li><strong>Articles must be spellchecked and proof read for grammatical errors prior to submitting - </strong> Do not  submit articles filled with spelling errors and bad grammar. We do monitor article submissions and we will reject content that doesn't meet  this requirement. 
                </li>
                <li><strong>Articles that pertain to gambling, guns, promote hate or sex sites or content</strong> will be deleted and your account may be also deleted.
                </li>
                <li><strong>HTML is not allowed</strong> in any part of an article except the author's resource box. Javascript is not permitted at all.
                </li>
                <li><strong>We Do Not delete author's accounts or articles by request (unless they violate our terms)</strong>... whether by email or phone. This is for ours, and the author's security. If  you or your agent creates an account and/or submits articles, then it's your responsibility to edit/remove articles or your account.
                </li>             
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