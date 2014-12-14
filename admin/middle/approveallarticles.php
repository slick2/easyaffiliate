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
?>
<br><br><br><br>
<table height="50"  border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center" valign="middle" >
            <strong>
                <?php
                if ($pdo) {
                    $query = "SELECT intStatus FROM tblarticles WHERE intStatus = ?";
                    $bind = array(0);
                    $cat1 = select_pdo($query, $bind);
                } else {
                    $cat1 = $d->fetch("SELECT intStatus FROM tblarticles WHERE intStatus = 0");
                }
                $num_rows = count($cat1);

                if ($num_rows > 0) {
                    if ($pdo) {
                        $query = "UPDATE tblarticles SET intStatus = ? WHERE intStatus = ?";
                        $bind = array(1, 0);
                        $result = update_pdo($query, $bind);
                    } else {
                        $d->exec("UPDATE tblarticles SET intStatus = 1 WHERE intStatus = 0");
                    }
                    echo $num_rows . " Unapproved Articles Have Been Approved in The Database!";
                } else {

                    echo "No Records Were Found To Approve!";
                }
                ?>
            </strong>
        </td>
    </tr>
</table>
