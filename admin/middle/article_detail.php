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
    if (isset($_REQUEST['articleid']) && trim($_REQUEST['articleid'] != "")) {
        $articleid = sanitize_paranoid_string($_REQUEST['articleid']);

        $sql_select = "select * from tblarticles WHERE intId='$articleid'";
        $sql = $obj_db->select($sql_select);
        if (count($sql) > 0) {
            foreach ($sql as $row) {
                $sum = n2br($row["textSummary"]);
                $sum = stripslashes($sum);
                $sum = convert($sum);
                $body = n2br($row["textArticleText"]);
                $body = stripslashes($body);
                $body = convert($body);
                $resource = n2br($row["textResource"]);
                $resource = stripslashes($resource);
                $resource = convert($resource);
                $kewords = stripslashes($row["varKeywords"]);
                $kewords = convert($kewords);
                ?>
                <table border="0" cellspacing="3" cellpadding="2" align="center" width="75%">
                    <tr class="line_top">
                        <td width="95%"><div align="center">Article Detail</div></td>
                        <td width="5%">&nbsp;</td>
                    </tr>
                </table>
                <table  border="0" align="center" cellpadding="2" cellspacing="3" width="75%">
                    <tr>
                        <td><b>Summary:</b></td>
                        <td><?php echo $sum; ?></td>
                    </tr>
                    <tr>
                        <td><b>Keywords:</b></td>
                        <td><?php echo $kewords; ?></td>
                    </tr>
                    <tr>
                        <td valign="top"><b>Description:</b></td>
                        <td><textarea name="textarea" cols="85" rows="20"><?php echo $body; ?></textarea></td>
                    </tr>
                    <tr>
                        <td valign="top"><b>Resources:</b></td>
                        <td><textarea name="textarea2" cols="85" rows="10"><?php echo $resource; ?></textarea></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
                <?php
            }
        }
    }
}

