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
?>

<form method="POST" action="index.php?filename=authorsearch" name="search">
<script language="JavaScript">
 function disp_text()
   {
   var w = document.search.users.selectedIndex;
   var selected_text = document.search.users.options[w].text;
   document.search.author.value = selected_text;
   }

 </script>
 

<table height="50"  border="0" align="center" cellpadding="0" cellspacing="0" width="100%">
<tr>
    <td align="center" valign="middle" width="85%"><br><br>
<?php    
$sql = "SELECT intId, varFirstName, varlastName FROM tblauthor ORDER BY varlastName";
$result = $obj_db->select($sql);
foreach($result as $row) { 
$os = $row['intId']." ".$row["varFirstName"]." ".$row["varlastName"]; 
$option_block .= "<OPTION value=$os>$os</OPTION>";
 } 
?>
<?php
 if (isset($_SESSION['msg'])){
 $msg =  ($_SESSION['msg']);
 echo  $msg;
 }           
?>
<center><font size="2">Please choose the author you wish to find<br><br>
<SELECT name="users" value="" onchange="javascript:disp_text()"> 
<option>-- Select An Author
<? 
echo "$option_block"; 

?> 

</SELECT></font></center><br><br><table border="0" width="75%">
                     
  <tr>
                       <td align="center" colspan="2"><input type="submit" value="Get Author Record"></td>
                      
  </tr>
</table>
</td></td>
  </tr>
</table>
<input type="hidden" value="" name="author"></form>

<?php
unset($_SESSION['msg']);
?>
