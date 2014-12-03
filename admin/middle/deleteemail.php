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
<script language="JavaScript">
 function disp_text()
   {
   var w = document.form1.users.selectedIndex;
   var selected_text = document.form1.users.options[w].text;
   document.form1.author.value = selected_text;
   }

 </script>

<form method="POST" action="index.php?filename=deleteemail" name="form1">

<table width="625"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="middle" ><br><br>
<?php
	
	$u_subject2 = stripslashes($_POST['user_subject2']);
	$toemail = stripslashes($_REQUEST['to_email']);
	$art_title = stripslashes($_REQUEST['art_title']);
	$fname = stripslashes($_REQUEST['first_name']);
	
	$aname = stripslashes($_POST['author']);
		if(isset($aname)){
				
$mail_subject = $_POST['user_subject2'];
$message = "Dear ".$fname.",\r
We regret to inform you that your article entitled ".stripString($art_title)." has been declined due to the following reason:\r ".stripString($aname)."\r
".$_POST['user_message2']."\r
We appreciate your time in submitting articles and working with us to create a top notch article publishing directory!.\r
Thank You,\r
Articles Staff\r
".$title;
	
	
	$mail_from = $_POST['user_from2'];
  $headers = "From:".$mail_from;
	
	mail($_REQUEST['to_email'], $mail_subject, $message, $headers);
	
	  echo "<div align='center'><font color='green'><b>Your Email Has been sent to:</b></font></div><br><br>";
		echo $toemail."<br>";
		echo "Subject: ".$mail_subject."<br><br>";
	  echo "Message:<br>".$message."<br>";
	  
?>
</td></tr></table>
     
<?php

 }else{


?>

<center><font size="2">Please choose the reason for denying the article<br><br>
<SELECT name="users" value="" onchange="javascript:disp_text()">
<OPTION SELECTED value="">--Choose a Decline Reason--
<?php

  $toemail = stripslashes($_REQUEST['email']);
	$art_title = stripslashes($_REQUEST['title']);
	$firstname = stripslashes($_REQUEST['fname']);
      
$fp = fopen('deny.txt','r'); 
if (!$fp) {echo 'ERROR: Unable to open file.</table></body></html>'; exit;} 
  
while (!feof($fp)) { 
$line = fgets($fp, 2048); //use 2048 if very long lines 
//list ($field1) = split ('#', $line); 
echo "<option value=$line>$line</option>"; 
$fp++; 
}  
?> 

</SELECT></font></center>

 <input type="hidden" value="<?php echo $toemail ?>" name="to_email">
 <input type="hidden" value="<?php echo $art_title ?>" name="art_title">
 <input type="hidden" value="<?php echo $firstname ?>" name="first_name">
<br><br>

<table width="625"  border="0" align="center" cellpadding="0" cellspacing="0">
 
                     
  <tr>
                       <td align="left" width="2%">
                       From:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="user_from2" MAXLENGTH="50" SIZE="40" value="<?php echo $fromemail ?>"></td>
                       
                      </tr>
                      <tr>
                       <td>Subject:&nbsp;&nbsp;<input type="text" name="user_subject2" MAXLENGTH="50" SIZE="40" value="Article Declined"></td>
                       
                      </tr><tr><td>&nbsp;</td></tr><tr>
                       <td align="center"><b>Message</b></td></tr><tr>
                       <td align="left"><font size="2">Dear <?php echo $firstname ?>,<br><br>
                       We regret to inform you that your article entitled <b><?php echo $art_title ?></b> has been declined 
                       due to the following reason: (ADMIN NOTE: The decline reason you chose above will appear here in the email body **).</font><br><br></td></tr><tr>
                       <td align="left"><textarea name="user_message2" cols="75" rows="10">ADMIN NOTE: Overwrite this text is you'd like to add additional info to this decline email.  If you'd like to edit the email text sent, the file is admin/middle/deleteemail.php.  The reasons for declining articls is in your admin/deny.txt If you donot wish to include any additional text in your decline email, simply select and delete all this text.</textarea></td></tr>
                       <tr><td>&nbsp;</td></tr><tr><td align="left" colspan="2"><font size="2">
                       We appreciate your time in submitting articles and working with us to create a top notch article publishing directory!.
                        <br><br>
                       Thank You,<br><br>
                       Article Editing Staff<br>
                       <?php echo $title ?></font></td></tr>
                      <tr><td align="center" colspan="2"><input type="submit" value="Send PlainText Email" name="Submit2"></td>
                      
  </tr>
</table>
</td>
  </tr>
</table>
<input type="hidden" value="" name="author"></form>

<?php
}
?>
