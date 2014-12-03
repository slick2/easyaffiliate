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

<form method="post" action="index.php?filename=artsearch" name="search">

<table height="50"  border="0" align="center" cellpadding="0" cellspacing="0" width="100%">
<tr>
    <td align="center" valign="middle" width="85%"><br><br>
<?php
if(isset($_SESSION['msg']) && $_SESSION['msg'] > '')
{
echo "<p><b>".$_SESSION['msg']."</b></p>";
unset($_SESSION['msg']);
}
?>
<center><font size="2">Please enter the article title or a word/phrase in the title.<br><br>
<input type="text" name="author" MAXLENGTH="50" SIZE="40">

</SELECT></font></center><br><br><table border="0" width="75%">
                     
  <tr>
                       <td align="center" colspan="2"><input type="submit" value="Find Article"></td>
                      
  </tr>
</table>
</td></td>
  </tr>
</table>
</form>
<?php
unset($_SESSION['msg']);
?>
