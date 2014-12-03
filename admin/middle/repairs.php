<br><br><br>
<?php
if (!$ss->Check() || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1)
  {
   header("location:index.php?filename=adminlogin");
   die();
  }
  
// If member is not login or session is not set
if(!isset($_SESSION['userid'])|| $_SESSION['userid'] == '')
{
	header("location:index.php?filename=adminlogin");
	die();
}	


$dbname = DB_NAME;

$tables = array("keywords","tbladminuser",",tblarticles","tblauthor",
                 "tblcategories","tblcountry","tbllinks","tblsettings");    
		// cycle through the list of tables and check them
	        
    foreach ($tables as $tar) {
       $tablename = $tar;

			$res = "check table ".$tablename;
      $ar = $d->fetch($res);

			
			
			if ($ar[Msg_text] != "OK" || $ar[Msg_text] != "Table is already up to date")
			{
      echo var_dump($ar);
      die();
  
				$res = "repair table ".$tablename ;
				$ar = $d->fetch($res);


			// if we got an error trying to fix it, send an alert
			if ($ar[Msg_text] != "OK" || $ar[Msg_text] != "Table is already up to date")
				{
					echo ("<p style=padding-left:25px;'>UNABLE TO REPAIR ($tablename) !!!!</p>");
				
				}
				else
				{	
					echo("<p style=padding-left:25px;'>$tablename: Repaired</p>");
				}	
			}
		 }
    

echo "<p><br><br><center>Your Database Has Been Checked and Repaired if needed.</center></p>";
exit();

?>

