<br><br><br><br>
<table height="50"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="middle" ><strong>
	<?php 
	if(isset($_SESSION['msg']))
	{
		echo $_SESSION['msg'];
    unset($_SESSION['msg']);
	}else{
		echo "No Message.";
	}
	
	?>	
	</strong><br><br></td>
  </tr>
  <tr><td>&nbsp;</td></tr>
</table>
