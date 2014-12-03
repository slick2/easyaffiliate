<?
//Article Friendly Email Export Utility  copyright 2007 Jan Michaels
// Please donot remove this notice, take off my name or give this script away!
// Created by Smart Websites,
// http://www.articlefriendly.com/
define('AFFREE', 1);
include("system/config.inc.php");
//connect 

   $csv_output = "First_Name,Last_Name,Email"; 
   $csv_output .= "\n";
   
if($pdo)
{
$query = "select varFirstName, varlastName, varEmail FROM tblauthor";

$result = select_pdo($query);
}else{
 
   $result = $d->fetch("select varFirstName, varlastName, varEmail FROM tblauthor"); 
}
   foreach($result as $row) { 
       $csv_output .= "$row[varFirstName],$row[varlastName],$row[varEmail]\n";
       } 

   header("Content-type: application/vnd.ms-excel");
$size_in_bytes = strlen($csv_output);
header("Content-disposition:  attachment; filename=email_export" .
date("Y-m-d").".csv; size=$size_in_bytes");
 
   print $csv_output;
   exit;  
?>
