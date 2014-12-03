<?php
if (!defined('AFFREE'))
{
die('You cannot access this page directly!');
}
error_reporting(E_ERROR);
// check for PDO and the pdo_mysql drivers being installed.
$pdo = '';
if(isset($_SESSION['pdo']) && $_SESSION['pdo'] == true)
{
//set to true if pdo and mysql_pdo is loaded.
$pdo = true;
}
if(isset($_SESSION['pdo']) && $_SESSION['pdo'] == false)
{
//set to false if pdo and mysql_pdo is not loaded.
$pdo = false;
}
if(!isset($_SESSION['pdo']) || $_SESSION['pdo'] == '')
{
if(extension_loaded('PDO') && extension_loaded('pdo_mysql'))
{
$_SESSION['pdo'] = true;
$pdo = true;
}else{
$_SESSION['pdo'] = false;
$pdo = false;
}
}


############################################
#	Database Server
############################################

  define("DB_NAME","your database name here");
	define("SERVER_NAME","localhost");
	define("USER_NAME","your database username here");
	define("PASSWORD","your database password here");
  $dbhost=SERVER_NAME;
  $dbuser=USER_NAME;
  $dbpasswd=PASSWORD;
  $dbname=DB_NAME;



################################################################
#		File paths
##################################################################
//For the inc folders
define("INC","inc/");

// Common functions
require_once("commonfunc.php");

// For  the Database file path

require_once("class_db.php");

//caching object
$d = new db(0);

// require_once("classmysql.inc.php");
// 
// ################################################################
// #		Database Class
// ##################################################################
// $obj_db= new dbclass();
// 
// ################################################################

//for common function
require_once("commonfunc.php");

define('CACHE','cache/scache/');
define('CACHE_TIME',60);

################################################################
#		Global Varibles
##################################################################
define('ROW_PER_PAGE',20);



$db='';

function db_con()
{
try{
$db = new PDO('mysql:host='.SERVER_NAME.';dbname='.DB_NAME.';charset=UTF8', USER_NAME, PASSWORD, array( PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch(PDOException $ex) {
    $ex->getMessage();
   error_log("Could not connect to database!  PDO Message: ".$ex);
   return FALSE;
  }
  $GLOBALS['db_conn'] = $db;
  return $db;
}


// SELECT FUNCTION
function select_pdo($query='',$bind='',$name='',$expires='')
{

if($query=='')
{
error_log("Your SELECT query is empty!");
return false;
}

// END TEST FOR EMPTY VALUES

$exist = 0;
if (is_numeric($expires) && $name > '')
{
$file = "cache/scache/".$name;
$expired = time() - $expires;
}

if ($name > '' && is_numeric($expires) && file_exists($file) && filemtime($file) >= $expired) 
{
  $exist = 1;
  
  $rows = unserialize(file_get_contents($file));
  return $rows;

}    

if(!isset($GLOBALS['db_conn']) || $GLOBALS['db_conn'] == '')
{    
$db = db_con();
}
else
{
$db = $GLOBALS['db_conn'];
}
try {
if($bind == '')
{
$query = str_replace("\n","",$query);
$query = substr($db->quote($query), 1, -1);
$stmt = $db->query($query);
}else{
foreach($bind as $clean)
{
stripslashes($clean);
}
$stmt = $db->prepare($query);
$stmt->execute($bind);
}
$rows = $stmt->fetchALL(PDO::FETCH_ASSOC);
} catch(PDOException $ex) {
    $ex->getMessage();
   error_log("There was a PDO query error! Query: ".$query." || PDO Message: ".$ex);
  return false;
}
 if ($name > '' && is_numeric($expires) && $exist == 0) 
{
 $OUTPUT = serialize($rows);

    if($fp = fopen($file,"w"))
     {
    fputs($fp, $OUTPUT);

    fclose($fp);
  }
}

return $rows;  

}

// UPDATE FUNCTION
function update_pdo($query='',$bind='')
{
if($query=='')
{
error_log("The UPDATE function query is empty!");
return false;
}
// END TEST FOR EMPTY VALUES

if(!isset($GLOBALS['db_conn']) || $GLOBALS['db_conn'] == '')
{    
$db = db_con();
}
else
{
$db = $GLOBALS['db_conn'];
}
try {
if($bind == '')
{
$query = substr($db->quote($query), 1, -1);
$stmt = $db->query($query);
$affected_rows = $stmt->rowCount();
}else{
$stmt = $db->prepare($query);
$affected_rows = $stmt->execute($bind);

}
} catch(PDOException $ex) {
    $ex->getMessage();
   error_log("There was a PDO query error! Query: ".$query." || PDO Message: ".$ex);
  return false;
}
   
return $affected_rows;
}

// INSERT FUNCTION
function insert_pdo($query='',$bind='')
{
if($query=='')
{
error_log("The INSERT function query is empty!");
return false;
}

if(!isset($GLOBALS['db_conn']) || $GLOBALS['db_conn'] == '')
{    
$db = db_con();
}
else
{
$db = $GLOBALS['db_conn'];
}
try {
if($bind == '')
{
$query = substr($db->quote($query), 1, -1);
$stmt = $db->query($query);
$insertId = $db->lastInsertId();
}else{
$stmt = $db->prepare($query);
$stmt->execute($bind);
$insertId = $db->lastInsertId();
}
} catch(PDOException $ex) {
    $ex->getMessage();
   error_log("There was a PDO query error! Query: ".$query." || PDO Message: ".$ex);
  return false;
}

return $insertId;

}

// DELETE FUNCTION
function delete_pdo($query='',$bind='')
{
if($query=='')
{
error_log("The DELETE function query is empty!");
return false;
}

// END TEST FOR EMPTY VALUES

if(!isset($GLOBALS['db_conn']) || $GLOBALS['db_conn'] == '')
{    
$db = db_con();
}
else
{
$db = $GLOBALS['db_conn'];
}

try {
if($bind == '')
{
$query = substr($db->quote($query), 1, -1);
$stmt = $db->query($query);
$affected_rows = $stmt->rowCount();
}else{
$stmt = $db->prepare($query);
$stmt->execute($bind);
$affected_rows = $stmt->rowCount();
}
} catch(PDOException $ex) {
    $ex->getMessage();
   error_log("There was a PDO query error! Query: ".$query." || PDO Message: ".$ex);
 return false; 
}
   
return $affected_rows;
}

//Example Count : query('SELECT COUNT(id) FROM pics');  selecting only one column to count
// Get record count from database
function count_pdo($query='',$bind='')
{
if($query=='')
{
error_log("The Count function query is empty!");
return false;
}

// END TEST FOR EMPTY VALUES

if(!isset($GLOBALS['db_conn']) || $GLOBALS['db_conn'] == '')
{    
$db = db_con();
}
else
{
$db = $GLOBALS['db_conn'];
}

try {
if($bind == '')
{
$query = substr($db->quote($query), 1, -1);
$stmt = $db->query($query);
$row_count = $stmt->fetchColumn(); 
}else{
$stmt = $db->prepare($query);
$stmt->execute($bind);
$row_count = $stmt->fetchColumn();
}
} catch(PDOException $ex) {
    $ex->getMessage();
   error_log("There was a PDO query error! Query: ".$query." || PDO Message: ".$ex);
  return false;
}
   
return $row_count;
}



 if($pdo)
{
$query = "SELECT * FROM tblsettings";
$result_settings = select_pdo($query,"","site_settings.af",3600); // 1 Hour in Seconds
}else{
$connection = "SELECT * FROM tblsettings";
$result_settings = $d->fetch($connection, 3600, "settings.af");
}
foreach($result_settings as $row)
{
$title = $row['varSiteName'];
$fromemail = $row['varContactEmail'];
$site_URL = $row['varSiteURL'];
$admin = $row['ownerName'];
$uplimit = $row['maxWords'];
$downlimit = $row['minWords'];
$homearticle = $row['intTotalArticleinHome'];                                                                                             
}
  $_SESSION['titles'] = $title;
  $_SESSION['fromemails'] = $fromemail;
  $_SESSION['site_urls'] = $site_URL;
  $_SESSION['admins'] = $admin;
  $_SESSION['uplimits'] = $uplimit;
  $_SESSION['downlimits'] = $downlimit;
  $_SESSION['home_art'] = $homearticle;  




?>
