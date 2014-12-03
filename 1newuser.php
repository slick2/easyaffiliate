  <?php
  if (!$_POST)
  {
  echo "Empty Submission<br>";
  echo "You are NOT allowed access to this page! <a href='http://www.articlefriendly.com'>article friendly</a>";
  exit;
  }

    $ip = $_SERVER["REMOTE_ADDR"] ;

  // check for contentcrooner.com's ip address. Die if not right
 if($ip != '174.129.237.211')
 {
 die("bad IP");
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
  
  $_POST = array_map('EscapeString',$_POST);
  $_REQUEST = array_map('EscapeString',$_REQUEST);
  }
  define('AFFREE', 1);
  require_once("system/config.inc.php");
  

  $_POST = array_map('stripslashes',$_POST);
  $_REQUEST = array_map('stripslashes',$_REQUEST);
  
  if (!isset($_POST['first_name'])){
  echo "Are you kidding?";
  die();
  }else{
if(isset($_POST['country']))
  {
$country_sent = $_POST['country'];
}else{
$country_id=4;
}
  if($pdo)
{
$query = "SELECT intId FROM tblcountry WHERE varCountry =?";
$bind = array($country_sent);
$result = select_pdo($query,$bind);
$country_id=$result[0]['intId'];
}else{
  if($result=$d->fetch("SELECT intId FROM tblcountry WHERE varCountry ='".safeEscapeString($country_sent)."'"))
  {
  $country_id=$result[0]['intId'];
  }
  else{ 
  $country_id=4;
  }
 }
  $email = $_POST['email'];
  $pass = $_POST['password'];
  $fname=$_POST['first_name'];
  $lname=$_POST['last_name'];
  $address1=$_POST['address1'];
  $address2=$_POST['address2'];
  $zip = $_POST['zip_code'];
  $city = $_POST['city'];
  $state = $_POST['state'];
  
  if($pdo)
{
$query = "INSERT INTO tblauthor(varEmail, varPassword, varFirstName, varlastName, varAddress1, varAddress2, varZip, varCity, varState, 
  intCountry, varPhone, varFax, intIsTerms, dtRegisteredDate, intStatus) VALUES(?, ?, ?, 
  ?, ?, ?, ?, ?, ?, '4', 'Phone', 
  'Fax', '1', '".date("Y-m-d")."', '1')";
$bind = array($email,$pass,$fname,$lname,$address1,$addres2,$zip,$city,$state,$country_id);
$result = insert_pdo($query,$bind);
}else{

  $email = safeEscapeString($email);
  $pass = safeEscapeString($pass);
  $fname= safeEscapeString($fname);
  $lname= safeEscapeString($lname);
  $address1= safeEscapeString($address1);
  $address2= safeEscapeString($address2);
  $zip = safeEscapeString($zip);
  $city = safeEscapeString($city);
  $state = safeEscapeString($state);
  
  $result = $d->exec("INSERT INTO tblauthor(varEmail, varPassword, varFirstName, varlastName, varAddress1, varAddress2, varZip, varCity, varState, 
  intCountry, varPhone, varFax, intIsTerms, dtRegisteredDate, intStatus) VALUES('$email', '$pass', '$fname', 
  '$lname', '$address1', '$address2', '$zip', '$city', '$state', '$country_id', 'Phone', 
  'Fax', '1', '".date("Y-m-d")."', '1')");
}  
  
}
  
if($result)
{
echo "Submission Success";
die();
}else{
error_log('Could not insert into database');
echo "Submission Failure";
die();
}  
  ?>
