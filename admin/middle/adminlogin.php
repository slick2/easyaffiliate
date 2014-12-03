<?php
if (!defined('AFFREE'))
{
die('You cannot access this page directly!');
}

// Login operation of the admin
if(isset($_REQUEST['actioneded']))
{
	if($_REQUEST['actioneded']=="checkloginadmin")
	{
			if(strlen($_POST['username']) > 25 || strlen($_POST['username']) < 2)
      {
      $_SESSION['msg'] = "Username too long or short. Please try again.";
				header("location:index.php?filename=thankyou");
      }
      	if(strlen($_POST['password']) > 25 || strlen($_POST['password']) < 6)
      {
      $_SESSION['msg'] = "Password too long or short. Please try again.";
				header("location:index.php?filename=thankyou");
      }
      $varUsername = stripslashes(trim($_POST['username']));
      $varPassword = stripslashes(trim($_POST['password']));
			
			
			
if($pdo)
{
      
$query = "SELECT * FROM tbladminuser WHERE varUsername = ?";
$bind = array($varUsername);
$result1 = select_pdo($query,$bind);
}else{
      $varUsername = safeEscapeString($varUsername);
      $login = "SELECT * FROM tbladminuser WHERE varUsername = '$varUsername'";
			$result1 = $d->fetch($login);
}
			if($result1)
			{
        $admin_salt = $result1[0]['admin_salt'];
        $varPassword = shadow($admin_salt.$varPassword);
        $test_pass = $result1[0]['varPassword'];
      if($varPassword != $test_pass)
      {
      $_SESSION['msg'] = "<p style='color:red;font:12px;padding-left:20px;'>Invalid user/pass! ".$varPassword."</p>";
			header("location:index.php?filename=adminlogin");
      die();
      }
				$userid = $result1[0]['intId'];
				$_SESSION['userid'] = $userid;
				
				$display = $result1[0]['intStatus'];
				$_SESSION['acctype'] = $result1[0]['intStatus'];
				
        if($pdo)
{
$query = "SELECT varFullname FROM tbladminuser WHERE intId = ?";
$bind = array($userid);
$result2 = select_pdo($query,$bind);
}else{
				$sql = "SELECT varFullname FROM tbladminuser WHERE intId = '$userid'";
				$result2 = $d->fetch($sql);
}				
				
				$ss = new SecureSession();
        $ss->Open();
        $_SESSION['logged_in'] = 1;
				$_SESSION['msg'] = "Welcome ".$result2[0]['varFullname'].".";
				header("location:index.php?filename=stats");
        die();
			}
			else
			{
				$_SESSION['msg'] = "<p style='color:red;font:12px;padding-left:20px;'>Account Not Found!!</p>";
				header("location:index.php?filename=adminlogin");
        die();
			}
	}
}
else
{
?>
<form action="" method="post" enctype="multipart/form-data" name="adminform">
<script language="javascript">
function checkloginform(frm)
{
	if(adminform.username.value.length == 0)
	{
		alert("Please enter Username!");
		frm.username.focus();
		return false;
	}
	if(adminform.password.value.length == 0)
	{
		alert("Please enter Password!");
		frm.password.focus();
		return false;
	}
}
</script>
<br><br><br>
<?php
       
	if(isset($_SESSION['msg']))
	{
		echo $_SESSION['msg']."<br>";
	}
	unset($_SESSION['msg']);
  
	?>	
<div  style='padding-left:200px'>
<table align="left" cellpadding="2" cellspacing="2" class="snippet" >
  <tr>
    <td class="line_top">
	 <p>Please fill in both fields</p>
	 </td>
  </tr>
  <tr>
    <td>
      <table  border="0" align="center" cellpadding="3" cellspacing="3" >
      
        <tr>
          <td width='10%'>Username </td>
          <td align='left' width='25%'><input name="username" type="text" id="username" value="" size="25">
          </td>
        </tr>
        <tr>
          <td>Password </td>
          <td align='left'><input name="password" type="password" id="password" value="" size="25">
		  </td>
        </tr>
        
        <tr>
          <td colspan="2" align="center">
            <input type="submit" value="Administrator Login" name="Submit" onClick="return checkloginform(this.form);">
          </td>
        </tr>
        <tr>
         <td align="center" colspan='2'>Forgot your password? <a href="index.php?filename=forgetpswd">Click Here</a>
         </td>
         </tr>
    </table></td>
  </tr>
</table>
</div>
<input name="actioneded" type="hidden" id="actioneded" value="checkloginadmin"></form>

<? 
} 
?>
