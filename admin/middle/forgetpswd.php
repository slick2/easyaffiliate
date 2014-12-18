<script language="javascript" src="js/forgetpswd.js"></script>
<?php
if (isset($_REQUEST['Submit']) && trim($_REQUEST['Submit']) == "Submit") {

////////////////////////////////////////////////////////////////////////


    $varEmailAddress = safeEscapeString($_REQUEST['email']);
    $username = "";

    $echeck = check_email($varEmailAddress);
    if (!$echeck) {
        $_SESSION['msg'] = "Your email address is not valid. Please try again.<br><br>";
        header("location:index.php?filename=thankyou");
        die();
    }
    if ($pdo) {
        $query = "SELECT * FROM tbladminuser WHERE varEmail = ?";
        $bind = array($varEmailAddress);
        $result1 = select_pdo($query, $bind);
    } else {
        $sql = "SELECT * FROM tbladminuser WHERE varEmail = '$varEmailAddress'";
        $result1 = $d->fetch($sql);
    }

    if ($result1) {
        $varEmailAddress = stripString($result1[0]['varEmail']);
        $custName = stripString($result1[0]['varFullname']);
        $usernames = $result1[0]['varUsername'];

        $gen_pass = generate_password();
        $salt = generate_salt();
        $updatefield = shadow($salt . $gen_pass);
        if ($pdo) {
            $query = "UPDATE tbladminuser  SET  varPassword = ?, admin_salt = '$salt' WHERE varEmail = ?";
            $bind = array($updatefield, $varEmailAddress);
            $result_upd = select_pdo($query, $bind);
        } else {
            $sql_upd = "UPDATE tbladminuser  SET  varPassword = '$updatefield', admin_salt = '$salt' WHERE varEmail = '$varEmailAddress'";
            $result_upd = $obj_db->sql_query($sql_upd);
        }
////////////////////////    Mail Function  //////////////////////////
        /* recipients */
        $to = $varEmailAddress;

        /* subject */
        $sub = "Your password has been reset.";

        /* message */
        $message = '
						<html>
						<head>
						<title>Password Reminder</title>
						</head>
						<body>

						<table>
						<tr>
							<td>Dear  ' . $custName . '</td>
						</tr>

						<tr>
							<td>Your password has been reset.</td>
						</tr>
						<tr>
							<td>Kindly, Use the following username and password to access your account.</td>
						</tr>
						<tr>
						  <td>User Name : ' . $usernames . '</td>
						</tr>

						<tr>
						  <td>Password : ' . $gen_pass . '</td>
						</tr>
						<tr>
							<td>Please login into the site and Change your password for security purpose.</td>
						</tr>
						<tr>
							<td>Thank you</td>
						</tr>
						<tr>
							<td>Regards</td>
						</tr>
						<tr>
							<td>Admin</td>
						</tr>

						</table>
						</body>
						</html>
						';

        /* To send HTML mail, you can set the Content-type header. */
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";

        /* additional headers */

        $headers .= "From: Password Reminder<" . $fromemail . ">\r\n";
        $headers .= "Repy-To: $fromemail\r\n";
        /* and now mail it */
        mail($to, $sub, $message, $headers);

        $_SESSION['msg'] = "<p>Your new password has been sent to the email address on record.<br><br>Thank you.<br><br></p>";
        header("location:index.php?filename=thankyou");
        die();
    } else {
        $_SESSION['msg'] = "<p>Invalid password request data entered! Please try again.</p>";
        header("location:thankyou.php");
        die();
    }

//////////////////////////////////////////////////////////////////////////////////
}
?>
<form action="" method="post" name="frmfgtpswd" id="frmfgtpswd">
    <table width="725"  border="0" align="center" cellpadding="0" cellspacing="0" class="greyborder">
        <tr>
            <td>
                <table width="725"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td height="80">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>
                            <table width="725"  border="0" cellpadding="0" cellspacing="0">
                                <tr class="fonttitle">
                                    <td valign="top" class="fonttitle">&nbsp;</td>
                                    <td valign="top" bgcolor="#FFFFFF" class="fonttitle" ><div align="center">Forgot your password?</div></td>
                                    <td valign="top" class="fonttitle">&nbsp;</td>
                                </tr>

                                <tr>
                                    <td id="leftColumn" valign="top" class="box">&nbsp;</td>
                                    <td id="content" align="center" valign="top" bgcolor="#FFFFFF">
                                        <table width="100%" border="0" cellpadding="5" cellspacing="2">
                                            <tr>
                                                <td colspan="2">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">Enter the email address below that was used to setup your admin user account. </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td width="49%"><div align="right">Email:</div></td>
                                                <td width="51%">
                                                    <div align="left">
                                                        <input name="email" type="text" id="email3" />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <div align="center">
                                                        <input type="submit" name="Submit" value="Submit" onClick="return confirmsubmit();" />
                                                        &nbsp;
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">&nbsp;</td>
                                            </tr>
                                        </table>
                                        <p align="left">&nbsp; </p>
                                        <p align="left">&nbsp;</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</form>