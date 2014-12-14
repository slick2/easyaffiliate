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

if (isset($_REQUEST['remove'])) {
    $the_file = $_REQUEST['remove'];
// Check if valid request
    $test = explode(".", $the_file);
    if ($test[1] != "sql" && $test[2] != "zip") {
        die("Filename is not a valid file for deletion. Exiting...");
    } else {
        $path = "db_backup/" . $the_file;
        unlink($path);
    }
}

function listDirs($where) {
    echo "<table border=\"0\" cellpadding='5' cellspacing='5' width='725'><tr class='table_header_menu'><td><b>Name</b></td><td><b>Type</b></td>";
    echo "<td><b>Invisible (Hidden)?</b></td><td><b>Delete?</td></tr>";
    $itemHandler = opendir($where);
    $i = 0;
    while (($item = readdir($itemHandler)) !== false) {
        if ($item != "." && $item != ".." && $item != ".htaccess") {
            if (substr($item, 0, 1) != ".") {
                if (is_dir($item)) {
                    echo "<tr><td>$item</td><td>Directory</td><td>No</td><td align='center'>
                     <a href='index.php?filename=backup_list&remove=$item'><img src='images/del.png' alt='Edit' border='0'></a></td></tr>";
                } else {
                    echo "<tr><td>$item</td><td>File</td><td>No</td><td align='center'>
                     <a href='index.php?filename=backup_list&remove=$item'><img src='images/del.png' alt='Edit' border='0'></a></td></tr>";
                }
                $i++;
            } else {
                if (is_dir($item)) {
                    echo "<tr><td>$item</td><td>Directory</td><td>Yes</td><td align='center'>
                <a href='index.php?filename=backup_list&remove=$item'><img src='del/edit.png' alt='Edit' border='0'></a></td></tr>";
                } else {
                    echo "<tr><td>$item</td><td>File</td><td>Yes</td><td align='center'>
                <a href='index.php?filename=backup_list&remove=$item'><img src='images/del.png' alt='Edit' border='0'></a></td></tr>";
                }
                $i++;
            }
        }
    }
    echo "</table>";
}
?>
<br /><br />
<p align='center'>BackUp Management</p>
<br /><br />

<table  border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center" valign="middle" >
            <?php
            $list = listDirs("db_backup");
            ?>

        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
</table>
