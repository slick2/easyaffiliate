<?php
if (!$ss->Check() || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1)
  {
   header("location:index.php?filename=adminlogin");
   die();
  }
if(!isset($_SESSION['userid'])|| $_SESSION['userid'] == '')
{
	header("location:index.php?filename=adminlogin");
	die();
}	
if($pdo)
{
$query = "SHOW TABLES";
$alltables = select_pdo($query);
}else{
$alltables = $d->fetch("SHOW TABLES");
}
foreach ($alltables as $table){   
foreach ($table as $db => $tablename){
if($pdo)
{
$query = "OPTIMIZE TABLE ".$tablename;
//$bind = array($tablename);
$result = select_pdo($query,$bind);
}else{
$result = $d->fetch("OPTIMIZE TABLE ".$tablename);
  }       
 }   
}
echo "<center><br><br><br>All the database tables have been Optimized!</center><br>";

?>
