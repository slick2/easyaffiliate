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
<form name="massmove" method="post" action="index.php?filename=massmove">

    <?php
// Mass Move Code


    if ($pdo) {
        $query = "SELECT varCategory, intID FROM tblcategories ORDER BY varCategory";
        $names1 = select_pdo($query);
    } else {
        $names1 = $d->fetch("SELECT varCategory, intID FROM tblcategories ORDER BY varCategory");
    }
    foreach ($names1 as $row) {
        $os = $row["intID"];
        $os2 = $row["varCategory"];
        $option_block2 .= "<OPTION value=$os>$os2</OPTION>";
        $option_block3 .= "<OPTION value=$os>$os2</OPTION>";
    }
    ?>
    <br /><br /><br /><br />
    <table width="77%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" valign="middle" colspan="2">
                <div align="center"><strong><font size="2">Please Select the category to move from and then the category to move articles to</font></strong></div>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>

                From : <select name="users2" value="">

                    <?
                    echo $option_block2;

                    ?>
                </select>
            </td>
            <td>

                To : <select name="users3" value="">

                    <?
                    echo $option_block3;

                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td align="center" colspan="2"">
                <input type="submit" value="Submit" name="Submit" style="color: #333333; font-family: verdana; font-size: 11px; border: 1px solid #C0C0C0" />
            </td>
        </tr>
    </table>
</form>