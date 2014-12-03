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
?>
<br><br><br><br>
<table height="50"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="middle" ><strong>
	<?php 
if($pdo)
{
$query = "UPDATE tblauthor SET intStatus = ? WHERE intStatus = ?";
$bind = array(1,0);
$result = select_pdo($query,$bind);
}else{
$d->exec("UPDATE tblauthor SET intStatus = 1 WHERE intStatus = 0");
}
echo "All Unapproved Authors Have Been Approved!"

	?>	
	</strong></td>
  </tr>
</table>
