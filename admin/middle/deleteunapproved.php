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
$query = "DELETE FROM tblauthor WHERE intStatus = ? AND txtBAN = ?";
$bind = array(0,"No");
$result = delete_pdo($query,$bind);
}else{
$result = $d->exec("DELETE FROM tblauthor WHERE intStatus = 0 AND txtBAN = 'No'");
}
if($result !== false && $result != '')
{
echo "All ".$result." Unapproved Authors Have Been Removed From The Database!";
}else{
echo "Found 0 Unapproved Authors in the database! No changes made.";
}
	?>	
	</strong></td>
  </tr>
</table>
