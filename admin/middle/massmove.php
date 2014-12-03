<?php
if (!$ss->Check() || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1)
  {
   header("location:index.php?filename=adminlogin");
   die();
  }
// If member is not login or session is not set
if(!isset($_SESSION['userid']) || $_SESSION['userid'] == '')
{
	header("location:index.php?filename=adminlogin");
	die();
}
if(isset($_REQUEST['Submit']) && trim($_REQUEST['Submit']) == "Submit")
{

$from = safeEscapeString($_POST['users2']);
$to =  safeEscapeString($_POST['users3']);


if($pdo)
{
$query = "SELECT intCategory FROM tblarticles WHERE intCategory = ?";
$bind = array($from);
$cat1 = select_pdo($query,$bind);
}else{
$cat1 = $d->fetch("SELECT intCategory FROM tblarticles WHERE intCategory = '$from'");
}
$num_rows = count($cat1);

if($num_rows > 0){

if($pdo)
{
$query = "UPDATE tblarticles SET intCategory = ? WHERE intCategory = ?";
$bind = array($to[0],$from[0]);
$result = uupdate_pdo($query,$bind);
$moved = $result;
}else{
$result = $d->exec("UPDATE tblarticles SET intCategory = '$to' WHERE intCategory = '$from'");
$moved = $result;
}
echo "<br><br><<p style='padding-left:25px'><b>".$moved." Articles Were Moved To ".$to." From ".$from.".</b></div>";
  
 }else{

echo "<br><br><div align='center'><b><font color='red'>No Records Found To Move!</font></b></div>";
 }
}
?>
