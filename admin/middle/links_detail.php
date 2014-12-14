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

if (isset($_REQUEST['a']) && trim($_REQUEST['a']) == 4) {
    if (isset($_REQUEST['authorid']) && trim($_REQUEST['authorid'] != "")) {
        $authorid = sanitize_paranoid_string($_REQUEST['authorid']);
        $authorid = (int) $authorid;

        if ($pdo) {
            $query = "SELECT * FROM tbllinks WHERE intNumber = ?";
            $bind = array($authorid);
            $sql = select_pdo($query, $bind);
        } else {
            $sql_select = "SELECT * FROM tbllinks WHERE intNumber = '" . safeEscapeString($authorid) . "'";
            $sql = $d->fetch($sql_select);
        }
        if ($sql) {
            foreach ($sql as $row) {
                $fname = stripString($row['fname']);
                $lname = stripString($row['lname']);
                $email = stripString($row['email']);
                $site_name = stripString($row['site_name']);
                $site_addy = stripString($row['site_addy']);
                $site_desc = stripString($row['site_desc']);
                $status = stripString($row['status']);
            }
            ?>
            <br /><br />
            <table border="0" cellspacing="3" cellpadding="2" align="center" width='75%'>
                <tr class="line_top">
                    <td width="10%" style='padding-left:12px;'><div align="center">Links Detail</div></td>
                    <td width="5%"><div align="left"><a class="line_top" href="index.php?filename=new_links"><font color='#53A8BF'>Back</font></a></div></td>
                </tr>
            </table>
            <br /><br />
            <table border="0" align="center" cellpadding="2" cellspacing="3" class="greyborder">
                <tr>
                    <td>Name :</td>
                    <td><?php echo $fname . " " . $lname; ?></td>
                </tr>
                <tr>
                    <td>Email :</td>
                    <td><?php echo $email; ?></td>
                </tr>
                <tr>
                    <td>Site Name :</td>
                    <td><?php echo $site_name ?></td>
                </tr>
                <tr>
                    <td>Site Address :</td>
                    <td><?php echo $site_addy; ?></td>
                </tr>
                <tr>
                    <td>Site Desc :</td>
                    <td><textarea cols='40' rows='3' READONLY><?php echo $site_desc; ?></textarea></td>
                </tr>

                <tr>
                    <td>Status :</td>
                    <td><?php echo $status; ?></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
            <?php
        }
    }
} else {
    echo "<p>No ID number sent! Exiting...</p>";
}
