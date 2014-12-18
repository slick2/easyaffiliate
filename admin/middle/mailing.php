<?php
if (!$ss->Check() || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1) {
    header("location:index.php?filename=adminlogin");
    die();
}
// If member is not login or session is not set
if (!isset($_SESSION['userid']) || $_SESSION['userid'] == '') {
    header("location:index.php?filename=adminlogin");
    die();
}

if ($_SESSION['acctype'] == '1') {
    $_SESSION['msg'] = "<p>Sorry, but you don't have permission to view that page.";
    header("location:index.php?filename=thankyou");
    die();
}
?>
<table height="50"  border="0" align="center" cellpadding="0" cellspacing="0" width='75%'>
    <tr>
        <td align="center" valign="middle" ><br /><br />
            <?php
            if (isset($_POST['Submit'])) {
                if (isset($_POST['user_subject'])) {
                    $u_subject = $_POST['user_subject'];
                    $delay = $_POST['delay'];

                    if ($pdo) {
                        $query = "SELECT varEmail, varFirstName FROM tblauthor";
                        $result = select_pdo($query);
                    } else {
                        $sql = "SELECT varEmail, varFirstName FROM tblauthor";
                        $result = $d->fetch($sql);
                    }

                    if (count($result) <= 0) {
                        echo " No Email Addresses Found!<br>";
                        die();
                    }

                    if ($result) {
                        $count = count($result);
                        foreach ($result as $row) {

                            $to = $row['varEmail'];
                            $name = $row['varFirstName'];

                            /* subject */
                            $subject = stripString($_POST['user_subject']);

                            /* message */
                            $message = "<html>
<head>
<title>articlefriendly.com</title>
</head>
<body>
<table>
<tr>
<td>Hello " . $name . ",</td>" . stripString($_POST['user_message']);

                            /* To send HTML mail, you can set the Content-type header. */
                            $headers = "MIME-Version: 1.0\r\n";
                            $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

                            /* additional headers */

                            $headers .= "From: " . $_POST['user_from'] . "\r\n";
                            $headers .= "Repy-To: $fromemail\r\n";
                            //$headers .= "";
                            //echo $message;
                            //die();

                            /* and now mail it */
                            mail($to, $subject, $message, $headers);

                            sleep($delay);
                        }
                        echo "<p style='padding-left:25px;'>Sent " . $count . " Html Emails!</p>";
                    }
                }
            }
            if (isset($_POST['Submit2'])) {
                if (isset($_POST['user_subject2'])) {
                    $u_subject2 = $_POST['user_subject2'];
                    $delay = $_POST['delay2'];

                    $sql = "SELECT varEmail, varFirstName FROM tblauthor";
                    $result = $obj_db->select($sql);

                    if (count($result) <= 0) {
                        echo " No Record Found!<br>";
                        die();
                    }

                    if ($result) {
                        $count = count($result);
                        foreach ($result as $row) {

                            $mail_subject = stripString($_POST['user_subject2']);
                            $message = stripString($_POST['user_message2']);
                            $mail_to = stripString($row['varEmail']);
                            $mail_from = stripString($_POST['user_from2']);
                            $headers = "From:" . $mail_from;
                            $headers .= "Repy-To: $mail_from\r\n";
                            mail($mail_to, $mail_subject, $message, $headers);
                            sleep($delay);
                        }
                    }
                }
            }

            if (isset($u_subject)) {
                echo "<p style='padding-left:25px'><font color='green'><b>" . $count . " HTML Mail(s) sent!</b></font></p><br><br>";
            }
            if (isset($u_subject2)) {
                echo "<p style='padding-left:25px'><font color='green'><b>" . $count . " Plaintext Mail(s) sent!</b></font></p><br><br>";
            }
            ?>
            <script language="javascript" type="text/javascript" src="js/htmlmassmail.js"></script>
            <script language="javascript" type="text/javascript" src="js/plainmassmail.js"></script>

            <form method="POST" action="index.php?filename=mailing" name='form1'>
                <input type="hidden" value="user_subject" name="html">
                <font size="3"><b>SEND HTML EMAIL SECTION</b></font><br /><br />
                <table border="0" width="75%">
                    <tr>
                        <td>Send Delay Seconds</td>
                        <td>
                            <select name="delay" value="">
                                <option SELECTED value="choose">--Choose Delay--</option>
                                <option value="1">1 Seconds</option>
                                <option value="2">2 Seconds</option>
                                <option value="3">3 Seconds</option>
                                <option value="4">4 Seconds</option>
                                <option value="5">5 Seconds</option>
                                <option value="10">10 Seconds</option>
                                <option value="20">20 Seconds</option>
                                <option value="25">25 Seconds</option>
                                <option value="30">30 Seconds</option>
                                <option value="35">35 Seconds</option>
                                <option value="40">40 Seconds</option>
                                <option value="45">45 Seconds</option>
                                <option value="50">50 Seconds</option>
                                <option value="55">55 Seconds</option>
                                <option value="60">60 Seconds</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="left">From</td>
                        <td align="left"><input type="text" name="user_from" MAXLENGTH="50" SIZE="40" value="<?php echo $fromemail ?>"></td>
                    </tr>
                    <tr>
                        <td align="left">Subject</td>
                        <td align="left"><input type="text" name="user_subject" MAXLENGTH="50" SIZE="40" value=""></td>
                    </tr>
                    <tr>
                        <td align="left">Message</td>
                        <td align="left"><textarea name="user_message" cols="75" rows="10"><?php include("mail_body.txt") ?></textarea>
                    <tr>
                        <td align="center" colspan="2">
                            <input type="submit" value="Send HTML To All Authors" name="Submit" onClick="return confirmsubmit();"></form><br /><br />
                        </td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td colspan='2' width='75%' align='center'>
                            <font size="3"><b>SEND PLAINTEXT EMAIL SECTION</b></font><br /><br />
                        </td>
                    </tr>
                    <tr>

                    <form method="POST" action="index.php?filename=mailing" name='form2'>
                        <td>Send Delay Seconds</td>
                        <td><select name="delay2" value="">
                                <option SELECTED value="choose">--Choose Delay--</option>
                                <option value="1">1 Seconds</option>
                                <option value="2">2 Seconds</option>
                                <option value="3">3 Seconds</option>
                                <option value="4">4 Seconds</option>
                                <option value="5">5 Seconds</option>
                                <option value="10">10 Seconds</option>
                                <option value="20">20 Seconds</option>
                                <option value="25">25 Seconds</option>
                                <option value="30">30 Seconds</option>
                                <option value="35">35 Seconds</option>
                                <option value="40">40 Seconds</option>
                                <option value="45">45 Seconds</option>
                                <option value="50">50 Seconds</option>
                                <option value="55">55 Seconds</option>
                                <option value="60">60 Seconds</option>
                            </select>
                        </td>
                        </tr>
                        <tr>
                            <td align="left"><input type="hidden" value="user_subject2" name="plaintext2">
                                From</td>
                            <td align="left"><input type="text" name="user_from2" MAXLENGTH="50" SIZE="40" value="<?php echo $fromemail ?>"></td>
                        </tr>
                        <tr>
                            <td align="left">Subject</td>
                            <td align="left"><input type="text" name="user_subject2" MAXLENGTH="50" SIZE="40" value=""></td>
                        </tr>
                        <tr>
                            <td align="left">Message</td>
                            <td align="left"><textarea name="user_message2" cols="75" rows="10">Type your message here</textarea>
                        </tr>
                        <tr>
                            <td align="center" colspan="2"><input type="submit" value="Send PlainText To All Authors" name="Submit2" onClick="return confirmsubmits();"></form>
                    </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                </table>
        </td>
    </tr>
</table>
