<?php
if (!defined('AFFREE'))
{
die('You cannot access this page directly!');
}
################################################################
#		Common Function
##################################################################



// FUNCTION FOR CHECKING ACTIONS OF FORM
function actionfrmcheckrm($var="")
{
	//echo $_REQUEST[$var]."$var-->$val";
	if( isset($_REQUEST[$var])  && ($_REQUEST[$var]!="") )
	{
		return true;
	}
	else
	{
		return false;
	}
}

// Strip slashes
function stripString($string) 
{ 
 return stripslashes($string); 
}
 
// escape strings without db connection 
function safeEscapeString($string) 
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

// change newlines and returns to <br/>
function n2br($string) {
    
$stuff = array('\r\n','\n','\r');
$string = str_replace($stuff,"<br>",$string);

return trim($string);
}

// check submitted email is valid
function check_email($email) {
 
if(filter_var($email, FILTER_VALIDATE_EMAIL))
{
return true;
}
return false;  
}


function cache_cleanup($path ='')
{
if(!isset($path))
{
$path = "../cache/scache/";
}

if (is_dir("$path") )
        {
           $handle=opendir($path);
            while (false !== ($file = readdir($handle))) {
               if ($file != "." && $file != ".." ) { 
               
                   @unlink($path.$file);

               }
           }


          
           closedir($handle);
        }
      }


function convert($text) {

  
   // if(!mb_check_encoding($content, 'UTF-8')) {

       // $content = mb_convert_encoding($content, 'UTF-8');
       
$text = str_replace(
 array("\xe2\x80\x98", "\xe2\x80\x99", "\xe2\x80\x9c", "\xe2\x80\x9d", "\xe2\x80\x93", "\xe2\x80\x94", "\xe2\x80\xa6"),
 array("'", "'", '"', '"', '-', '--', '...'),
 $text);
// Next, replace their Windows-1252 equivalents.
 $text = str_replace(
 array(chr(145), chr(146), chr(147), chr(148), chr(150), chr(151), chr(133)),
 array("'", "'", '"', '"', '-', '--', '...'),
 $text);

    return $text;
}


function sanitize_paranoid_string($string, $min='', $max='')
{
  $string = preg_replace("/[^0-9]/", "", $string);
  $len = strlen($string);
  if((($min != '') && ($len < $min)) || (($max != '') && ($len > $max)))
    return FALSE;
  return $string;
}

function generate_salt()
{
  $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.
            '0123456789``-=~!@#$%^&*()_+,./<>?;:[]{}\|';
  $length = 10;
  $str = '';
  $max = strlen($chars) - 1;

  for ($i=0; $i < $length; $i++)
    $str .= $chars[rand(0, $max)];

  return $str;
}

function shadow($pass)
{
return sha1($pass);
}

function rand_pass( $length ) {
if($length == '')
{
$length = 6;
}
$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-=~!@#$%^&*()";
return substr(str_shuffle($chars),0,$length);

}
  
?>
