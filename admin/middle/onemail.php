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
 
?>

<form method="POST" action="index.php?filename=onemail" name="form1">

<table height="50"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="middle" ><br><br>
<?php

	$u_subject2 = $_POST['user_subject2'];
	$aname = $_POST['users'];
		if(isset($u_subject2)){
		
    $pieces = $aname;
    
if($pdo)
{
$query = "SELECT * FROM tblauthor WHERE intID = ?";
$bind = array($pieces);
$result2 = select_pdo($query,$bind);
}else{
      $findcat="SELECT * FROM tblauthor WHERE intID = '$pieces'";
      $result2 = $d->fetch($findcat);
}
    $fname = $result2[0]["varFirstName"];
    $lname = $result2[0]["varlastName"];
    
    echo "<div align='center'><font color='green'><b>Your Email Has been sent to:</b></font></div><br><br>";
		echo $fname." ".$lname."<br>";
	
    
  foreach ($result2 as $row2)
      { 	
	$mail_to = stripString($row2['varEmail']);			
	$mail_subject = stripString($_POST['user_subject2']);
	$message = "Dear ".$row2["varFirstName"].",\r\n".stripString($_POST['user_message2']);
	
	
	$mail_from = stripString($_POST['user_from2']);
  $headers = "From:".$mail_from;
	
	//mail($mail_to, $mail_subject, $message, $headers);
     
  }
  }else{
if($pdo)
{
$query = "SELECT intID, varFirstName, varlastName FROM tblauthor ORDER BY varlastName";
$result = select_pdo($query);
}else{    
$sql = "SELECT intID, varFirstName, varlastName FROM tblauthor ORDER BY varlastName";
$result = $d->fetch($sql);
}
foreach($result as $row) { 
$os = $row['intID'];
$os2 = $row["varFirstName"]." ".$row["varlastName"]; 
$option_block .= "<OPTION value=$os>$os2</OPTION>";
 } 
  ?>
            
<center><font size="2">Please choose the author you wish to email<br><br>
<SELECT name="users" value="">
<OPTION SELECTED value="">-------Choose an author to email-------

<? 
echo "$option_block"; 

?> 

</SELECT></font></center><br><br><table border="0" width="75%">
                     
  <tr>
                       <td align="left">
                       From</td>
                       <td align="left"><input type="text" name="user_from2" MAXLENGTH="50" SIZE="40" value="<?php echo $fromemail ?>"></td>
                      </tr>
                      <tr>
                       <td align="left">Subject</td>
                       <td align="left"><input type="text" name="user_subject2" MAXLENGTH="50" SIZE="40" value=""></td>
                      </tr><tr>
                       <td align="left">Message</td>
                       <td align="left"><textarea name="user_message2" cols="75" rows="10">Replace this text, and type your message here. "Dear authors name," will be auto added to the email!</textarea>
                      <tr><td align="center" colspan="2"><input type="submit" value="Send PlainText Email" name="Submit2"></td>
                      
  </tr>
</table>
</td>
  </tr>
</table>
</form>

<?php
}
?>
