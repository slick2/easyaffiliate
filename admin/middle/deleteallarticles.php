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
<br /><br /><br /><br />
<table height="50"  border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center" valign="middle" >
            <strong>
                <?php
                if ($pdo) {
                    $query = "delete FROM tblarticles WHERE intStatus = ?";
                    $bind = array(0);
                    $result = delete_pdo($query, $bind);
                    $deleted = $result;
                } else {
                    $result = $d->exec("delete FROM tblarticles WHERE intStatus = 0");
                    $deleted = $result;
                }
                if ($deleted !== false && $deleted != '') {
                    echo "<p>All " . $deleted . " Unapproved Articles Have Been Removed From The Database!</p>";
                } else {
                    echo "<p>No Unapproved Articles found and " . $deleted . " Have Been Removed From The Database!</p>";
                }
                ?>
            </strong>
        </td>
    </tr>
</table>
