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
$page = "signup";
include("system/config.inc.php");
if(isset($_POST['message']))
{
if(stristr($_POST['message'], '\n') === FALSE)
{
$_POST['message'] = nl2br($_POST['message']);
}else{
$_POST['message'] = n2br($_POST['message']);
}
}
    $_GET = array_map('stripslashes',$_GET);
    $_POST = array_map('stripslashes',$_POST);
    $_COOKIE = array_map('stripslashes',$_COOKIE);
    $_REQUEST = array_map('stripslashes',$_REQUEST);
if(isset($_REQUEST['Submit']) && trim($_REQUEST['Submit']) == "Submit")
{
require_once("htmlpurifier/library/HTMLPurifier.auto.php");
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
  //Captcha check system here
$session_key = md5($_POST['data']);
$session_key2 = $_SESSION['key'];
if($session_key == $session_key2)
{
 $cool = "cool";
 }else{

$_SESSION['msg'] = "<p>Your entry for the captcha code was incorrect! Please <a href='javascript:history.back()'>click here</a> to try again.</p> <p>thank you.</p>";
							unset($_SESSION['key']);
		header("location:thankyou.php");
  die();
}
//End Captcha Check here
 foreach ($_POST as $key=>$value) {
  $_POST[$key] = convert($value);
  }

	//$varEmail = $_POST['email'];
	  $tmp_email = "";
		$tmp_email = safeEscapeString($_POST['email']);

    $e_check = check_email($tmp_email);

  if(!$e_check)
  {
   $_SESSION['msg'] = "<p>The email host is not valid.  Please go back and enter a valid email address.</p>";
		   header("location:thankyou.php");
		   die();
  }else{
  $varEmail = $tmp_email;
  }


  // Stop certain domains

  if (getenv('HTTP_CLIENT_IP')) {
$IP = getenv('HTTP_CLIENT_IP');
}
elseif (getenv('HTTP_X_FORWARDED_FOR')) {
$IP = getenv('HTTP_X_FORWARDED_FOR');
}
elseif (getenv('HTTP_X_FORWARDED')) {
$IP = getenv('HTTP_X_FORWARDED');
}
elseif (getenv('HTTP_FORWARDED_FOR')) {
$IP = getenv('HTTP_FORWARDED_FOR');
}
elseif (getenv('HTTP_FORWARDED')) {
$IP = getenv('HTTP_FORWARDED');
}
else {
$IP = $_SERVER['REMOTE_ADDR'];
}
  $tmp_pass = "";
  $tmp_pass = stripslashes(strip_tags($_POST['pswd']));
	$varPassword = $tmp_pass;
  // generate a salted pass
  $salt = generate_salt();
  $varPassword =  shadow($salt.$varPassword);
  // end generation
	$tmp_fname = "";
	$tmp_fname = stripslashes(strip_tags($_POST['fname']));
	$varFirstName = safeEscapeString($tmp_fname);
  $varFirstName = $purifier->purify($varFirstName);
	$tmp_lname = "";
	$tmp_lname = stripslashes(strip_tags($_POST['lname']));
	$varlastName = safeEscapeString($tmp_lname);
  $varlastName = $purifier->purify($varlastName);
	$tmp_addy = "";
	$tmp_addy = stripslashes(strip_tags($_POST['add1']));
  $varAddress1 = safeEscapeString($tmp_addy);
  $varAddress1 = $purifier->purify($varAddress1);
  $tmp_addy2 = "";
  $tmp_addy2 = stripslashes(strip_tags($_POST['add2']));
	$varAddress2 = safeEscapeString($tmp_addy2);
  $varAddress2 = $purifier->purify($varAddress2);
	$tmp_zip = "";
	$tmp_zip = stripslashes(strip_tags($_POST['zip']));
	$varZip = $tmp_zip;
  $varZip = $purifier->purify($varZip);
	$tmp_city = "";
	$tmp_city = stripslashes(strip_tags($_POST['city']));
	$varCity = safeEscapeString($tmp_city);
  $varCity = $purifier->purify($varCity);
	$tmp_city = "";
	$tmp_city = stripslashes(strip_tags($_POST['state']));
	$varState = safeEscapeString($tmp_city);
  $varState = $purifier->purify($varState);
	$tmp_coun = "";
	$tmp_coun = stripslashes(strip_tags($_POST['country']));
	$intCountry = $tmp_coun;
	$tmp_phone = "";
	$tmp_phone = stripslashes(strip_tags($_POST['phone']));
	$varPhone = $tmp_phone;
  $varPhone = $purifier->purify($varPhone);
	$varLogginIP = safeEscapeString($_SERVER['REMOTE_ADDR']);
	$name = $varFirstName." ".$varlastName;
	$tmp_bio = "";
	$tmp_bio = stripslashes(strip_tags($_POST['message']));
	$bio = safeEscapeString($tmp_bio);
  $bio = $purifier->purify($bio);
	$tmp_web = "";
	$tmp_web = stripslashes(strip_tags($_POST['web']));

	$web = safeEscapeString($tmp_web);
  $web = $purifier->purify($web);

	//Test for dupe author names
	 if($pdo)
{
$query = "select varFirstName, varlastName from tblauthor WHERE varFirstName = ? AND varlastName = ?";
$bind = array($varFirstName,$varlastName);
$sql_setting = select_pdo($query,$bind);
}else{
	$sql_setting=$d->fetch("select varFirstName, varlastName from tblauthor WHERE varFirstName = '$varFirstName' AND varlastName = '$varlastName'");
}
	 if($sql_setting)
	 {
   $_SESSION['msg'] = "Your name is already being used.  Please choose another.<br><br>
              Thank you,<br><br>
              Admin Staff";
		header("location:thankyou.php");
		die();
   }
	///Test for bad submission charactors


	$tmp1_varfirstName = str_replace(".", " ", $varFirstName);
	$tmp2_varfirstName = str_replace( ' +', '', $tmp1_varfirstName);

		if (!ctype_alpha($tmp2_varfirstName)) {
        $_SESSION['msg'] = "Your first name contains a numeric character.  This is not allowed.  Please return and correct.<br><br>
              Thank you,<br><br>
              Admin Staff";
		header("location:thankyou.php");
		die();
	}

	$tmp3_varlastName = str_replace(".", " ", $varlastName);
	$tmp4_varlastName = str_replace( ' +', '', $tmp3_varlastName);

	if (!ctype_alpha($tmp4_varlastName)) {
        $_SESSION['msg'] = "Your last name contains a numeric character.  This is not allowed.  Please return and correct.<br><br>
              Thank you,<br><br>
              Admin Staff";
		header("location:thankyou.php");
		die();
	}

  if (strcasecmp($varFirstName, $varlastName) == 0) {

	$_SESSION['msg'] = "Your first and last name are the same.  This is not allowed.<br><br>
              Thank you,<br><br>
              Admin Staff";
		header("location:thankyou.php");
		die();
	}

	if(isset($_POST['terms']) && trim($_POST['terms'])==1)
	{
		$intIsTerms = 1;
	}else{
		$intIsTerms = 0;
	}
// varification of same email
if($pdo)
{
$query = "select varEmail from tblauthor WHERE varEmail = ?";
$bind = array($varEmail);
$sql_author = select_pdo($query,$bind);
}else{
	$sql_author=$d->fetch("select varEmail from tblauthor WHERE varEmail = '$varEmail'");
}
	if($sql_author){

		$_SESSION['msg'] = "Email address already exists please enter another email address! <br>
							Please <a href='signup.php'>click here</a> to sign in, thank you.";
		header("location:thankyou.php");
		die();
	}
   if($pdo)
{
$query = "SELECT varIPNUM, txtBAN FROM tblauthor WHERE varIPNUM = '$varLogginIP'";
$bind = array($varLogginIP);
$sql_IP = select_pdo($query,$bind);
}else{
	$sql_IP =$obj_db->select("SELECT varIPNUM, txtBAN FROM tblauthor WHERE varIPNUM = '$varLogginIP'");
}
	if($sql_IP){
  foreach($sql_IP as $row){
  if($row['txtBAN'] == "Yes"){
  $_SESSION['msg'] = "Your IP Number is not being accepted for membership at this time. <br>
							Please <a href='contact.php'>click here</a> to contact us if you feel this is an error.<br><br>
              Thank you,<br><br>
              Admin Staff";
		header("location:thankyou.php");
		die();
		}
	}
}
//end of varification

  if($pdo)
{
$query = "INSERT INTO tblauthor ( varEmail, varPassword, varFirstName, varlastName,
					varAddress1, varAddress2, varZip, varCity,
					varState, intCountry, varPhone,
					intIsTerms, intStatus, dtRegisteredDate, varIPNUM, varBio, website, salt )
					VALUES (?, ?, ?, ?,
									?, ?, ?, ?,
									?, ?, ?,
									?, ?, NOW(), ?, ?, ?, ?)";
$bind = array($varEmail,$varPassword,$varFirstName,$varlastName,$varAddress1,$varAddress2,$varZip,$varCity,$varState,$intCountry,$varPhone,$intIsTerms,0,$varLogginIP,$bio,$web,$salt);
$result = insert_pdo($query,$bind);
$insertid = $result;
}else{
	$sql = "INSERT INTO tblauthor ( varEmail, varPassword, varFirstName, varlastName,
					varAddress1, varAddress2, varZip, varCity,
					varState, intCountry, varPhone,
					intIsTerms, intStatus, dtRegisteredDate, varIPNUM, varBio, website, salt )
					VALUES ('".safeEscapeString($varEmail)."', '".safeEscapeString($varPassword)."', '".safeEscapeString($varFirstName)."', '".safeEscapeString($varlastName)."',
									'".safeEscapeString($varAddress1)."', '".safeEscapeString($varAddress2)."', '".safeEscapeString($varZip)."', '".safeEscapeString($varCity)."',
									'".safeEscapeString($varState)."', '".safeEscapeString($intCountry)."', '".safeEscapeString($varPhone)."',
									'".safeEscapeString($intIsTerms)."', '0', NOW(), '".safeEscapeString($varLogginIP)."', '".safeEscapeString($bio)."',
                  '".safeEscapeString($web)."', '$salt')";
	$result = $d->exec($sql);
  $insertid = $d->last_id;
}
	if($pdo)
{
$query = "SELECT * FROM tblsettings";
$sql_setting = select_pdo($query,"","sitesettings.af",3600);
}else{
	$sql_setting=$d->fetch("select * from tblsettings","daily","sitesettings.af");

}

	$url_site=$sql_setting[0]['varSiteURL'];


	$encid=sha1($insertid);
////////////////////////// MAIL Function //////////////////////////////////////

	$to = $varEmail;
				$subject = "User Activation Link From $title";
				$message = "
						<html>
						<head>
						<title>User Activation Link From $title</title>
						</head>
						<body>
							<table>
						<tr>
						  <td>Dear $name,</td>
						</tr>
						<tr>
						  <td>Thank you for becoming a member of $title <br> We
							encourage you to get into the habit of submitting multiple articles <br>
							each and every week.  This is a proven way to provide tremendous benefits <br>
							for your website including link popularity, search engine rankings, and <br>
							credibility...just to name a few.<br>
							</td>
						</tr>
						<tr>
						  <td>Email Address : $varEmail </td>
						</tr>
						<tr>
						  <td>Password : $tmp_pass </td>
						</tr>
						<tr>
				<td>-> Please open given link to activate your profile :
				<a  href=".$url_site."confirm.php?id=".$encid." target=_blank> Click Here </a> </td>
      </tr>
      <tr>
        <td>
        Or, copy/paste the following into your browser - ".$url_site."confirm.php?id=".$encid."</td>
			</tr>
					<tr>
						  <td><p>Thank You</p><p>Regards</p><p>$admin</p></td>
						</tr>
            <tr>
            <td><b>Get the same software that powers this site at
                http://www.articlefriendly.com
                You can get a Free version, standard with unencoded frontend pages,
                or a Pro version with SEF urls starting at just 24.99.</b>
        </td>
        <tr>
						</table>
						</body>
						</html>
						";
				$headers  = "MIME-Version: 1.0\r\n";
				$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

				/* additional headers */
				$headers .= "Content-Transfer-Encoding: 8bit\r\n";
				$headers .= "From: $fromemail\r\n";
				$headers .= "X-Mailer: PHP" . phpversion();
				$headers .= "";

				/* and now mail it */
				//mail($to, $subject, $message, $headers);

				if($email_sent == ""){
				mail($to, $subject, $message, $headers);
         $email_sent = "true";
         $_SESSION['msg'] = "E-mail confirmation link has been sent to the email address provided. <BR> Please check your email address at ".$to;
	       header("location:thankyou.php");
	die();
         }
     }
// End of INSERT operation
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
      <?php echo $title; ?> | SignUp
    </title>
    <style type="text/css">   .capcha    {     background:url("images/captcha.png");     border:1px solid #DFE8F7;     -moz-border-radius: 12px 12px / 12px 12px;     border-radius: 12px 12px / 12px 12px;         }
    </style>
<script language="javascript" type="text/javascript">
function textCounter(field, countfield, maxlimit) {
if (field.value.length > maxlimit) // if too long...trim it!
field.value = field.value.substring(0, maxlimit);
// otherwise, update 'characters left' counter
else
countfield.value = maxlimit - field.value.length;
}
// End -->
</script>
<script language="javascript" type="text/javascript" src="js/customres.js"></script>
  </head>
  <body>
<?php
//Captcha beginning
//lets use md5 to generate a totally random string
$md5 = md5(microtime() * mktime());
/*
We dont need a 32 character long string so we trim it down to 5
*/
$string = substr($md5,0,5);
$_SESSION['key'] = md5($string);
//Captcha ending
    ?>
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
          <div class="article"><h2>Author Sign Up</h2>
            <p>&nbsp;
            </p>
            <form name="frmsignup" method="post" action="">
              <table  border="0" cellpadding="0" cellspacing="0" width=99%>
                <tr>
                  <td bgcolor="#FFFFFF">
                    <font size="1">
                      <br>
                      <br>Please make sure your email                   address is correct before submitting this form, as this is where your confimation email from us will be delivered to!
                    </font>
                    <table align="left" cellpadding="2" cellspacing="2">
                      <tr>
                        <td colspan="2"></td>
                        <td width="3%" colspan="2"></td>
                      </tr>
                      <tr>
                        <td colspan="2"></td>
                      </tr>
                      <tr>
                        <td colspan="2"><strong>Account Details </strong></td>
                      </tr>
                      <tr>
                        <td width="30%"></td>
                        <td width="70%"></td>
                      </tr>
                      <tr align="left">
                        <td width="30%">Email Address: </td>
                        <td width="70%">
                          <input name="email" type="text" id="email" size="30">&nbsp;&nbsp;
                          <font color="red" size="-4">*Req
                          </font></td>
                      </tr>
                      <tr align="left">
                        <td width="30%">Verify Email: </td>
                        <td width="70%">
                          <input name="email_v" type="text" id="email_v" size="30">&nbsp;&nbsp;
                          <font color="red" size="-4">*Req
                          </font></td>
                      </tr>
                      <tr align="left">
                        <td width="30%">Password: </td>
                        <td width="70%">
                          <input name="pswd" type="password" id="pswd" size="25">&nbsp;&nbsp;
                          <font color="red" size="-4">*Req
                          </font></td>
                      </tr>
                      <tr align="left">
                        <td width="30%">Verify Password: </td>
                        <td width="70%">
                          <input name="cpswd" type="password" id="cpswd" size="25">&nbsp;&nbsp;
                          <font color="red" size="-4">*Req
                          </font></td>
                      </tr>
                      <tr align="left">
                        <td width="30%"></td>
                        <td width="70%"></td>
                      </tr>
                      <tr align="center">
                        <td colspan="2"><strong>Member Details </strong></td>
                      </tr>
                      <tr align="left">
                        <td width="30%"></td>
                        <td width="70%"></td>
                      </tr>
                      <tr align="left">
                        <td width="30%">First Name: </td>
                        <td width="70%">
                          <input name="fname" type="text" id="fname" size="30">&nbsp;&nbsp;
                          <font color="red" size="1">*Req
                          </font></td>
                      </tr>
                      <tr align="left">
                        <td width="30%">Last Name: </td>
                        <td width="70%">
                          <input name="lname" type="text" id="lname" size="30">&nbsp;&nbsp;
                          <font color="red" size="1">*Req
                          </font></td>
                      </tr>
                      <tr align="left">
                        <td width="30%">Address 1: </td>
                        <td width="70%">
                          <input name="add1" type="text" id="add1" size="30" value="Optional"></td>
                      </tr>
                      <tr align="left">
                        <td width="30%">Address 2: </td>
                        <td width="70%">
                          <input name="add2" type="text" id="add2" size="30" value="Optional"></td>
                      </tr>
                      <tr align="left">
                        <td width="30%">Zip/Postal code: </td>
                        <td width="70%">
                          <input name="zip" type="text" id="zip" size="30" value="Optional"></td>
                      </tr>
                      <tr align="left">
                        <td width="30%">City: </td>
                        <td width="70%">
                          <input name="city" type="text" id="city" size="30">&nbsp;&nbsp;
                          <font color="red" size="-4">*Req
                          </font></td>
                      </tr>
                      <tr align="left">
                        <td width="30%">State: </td>
                        <td width="70%">
                          <input name="state" type="text" id="state" size="30">&nbsp;&nbsp;
                          <font color="red" size="-4">*Req
                          </font></td>
                      </tr>
                      <tr align="left">
                        <td width="30%">Country:</td>
                        <td width="70%">
                          <select name="country" id="country">
                            <option>Select Country
                            </option>
<?php
		$Country="";
if($pdo)
{
$query = "SELECT * FROM tblcountry";
$result = select_pdo($query,"","country_list.af",3600);
}else{
		$result = $d->fetch("SELECT * FROM tblcountry","daily","country_list.af");
}
		foreach($result as $row)
		{
                            		?>
                            <option value="<? echo $row['intId'];?>" <? if($row['intId']==$Country){echo "selected";}else{echo "";} ?>>
                            <?php echo $row['varCountry'];?>
                            </option>
<?php }
                            		  ?>
                          </select> &nbsp;&nbsp;
                          <font color="red" size="-4">*Req
                          </font></td>
                      </tr>
                      <tr align="left">
                        <td width="30%">Phone: </td>
                        <td width="70%">
                          <input name="phone" type="text" id="phone" size="30" value="Optional">&nbsp;&nbsp;
                          <font color="red" size="-4">*Req
                          </font></td>
                        <tr align="left">
                          <td width="30%">A little about you:&nbsp;&nbsp;
                            <font color="red" size="-4">*Req
                            </font></td>
                          <td width="70%">
<textarea name=message wrap=physical cols=28 rows=4 onKeyDown="textCounter(this.form.message,this.form.remLen,200);" onKeyUp="textCounter(this.form.message,this.form.remLen,200);"></textarea>
                            <br>
                            <input readonly type=text name=remLen size=3 maxlength=3 value="200"> characters left</td>
                        </tr>
                        <tr align="left">
                          <td width="30%">Your Website:</td>
                          <td width="70%">
                            <input name="web" type="text" id="web" size="30" value="">&nbsp;&nbsp;
                            <font color="red" size="-4">*Req
                            </font></td>
                        </tr>
                        <tr align="left">
                          <td colspan="2">Please submit your site in this manner: http://www.yourdomain.com, and NO Affiliate                            links. Thank you.</td>
                        </tr>
                        <tr><td>&nbsp;</td>
                        </tr>
                        <tr align="left">
                          <td width="30%" align="right">
                            <input name="terms" type="checkbox" id="terms" value="1"></td>
                          <td width="70%">Yes, I agree to the
                            <a href="terms.php">Terms &amp; Conditions</a>&nbsp;&nbsp;
                            <font color="red" size="-4">*Req
                            </font></td>
                        </tr>
                        <tr><td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td align="center" class="capcha">
                            <font color="white" size="3"><b>
                                <?php echo $string ?></b>
                            </font></td>
                          <td align="left">&nbsp;&nbsp;&nbsp;Enter the white text&nbsp;&nbsp;
                            <input name="data" type="text" size="5" value=""></td>
                        </tr>
                        <tr><td>&nbsp;</td>
                        </tr>
                        <tr align="left">
                          <td width="30%">&nbsp;</td>
                          <td width="70%">
                            <input type="submit" name="Submit" value="Submit" onClick="return confirmsubmit();"></td>
                        </tr>
                        <tr>
                          <td colspan="2">&nbsp;</td>
                        </tr>
                    </table>
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