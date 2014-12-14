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

function backup_tables($host, $user, $pass, $name, $tables = '*') {
    $today = date("M_j_Y_H_i_s");
    $filename = "backup_" . $today . ".sql";

    $link = mysql_connect($host, $user, $pass);
    mysql_select_db($name, $link);

    //get all of the tables
    if ($tables == '*') {
        $tables = array();
        $result = mysql_query('SHOW TABLES');
        while ($row = mysql_fetch_row($result)) {
            $tables[] = $row[0];
        }
    } else {
        $tables = is_array($tables) ? $tables : explode(',', $tables);
    }

    //cycle through
    foreach ($tables as $table) {
        $result = mysql_query('SELECT * FROM ' . $table);
        $num_fields = mysql_num_fields($result);

        $return.= 'DROP TABLE ' . $table . ';';
        $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE ' . $table));
        $return.= "\n\n" . $row2[1] . ";\n\n";

        for ($i = 0; $i < $num_fields; $i++) {
            while ($row = mysql_fetch_row($result)) {
                $return.= 'INSERT INTO ' . $table . ' VALUES(';
                for ($j = 0; $j < $num_fields; $j++) {
                    $row[$j] = str_replace("\n", "", $row[$j]);
                    $row[$j] = stripslashes($row[$j]);
                    $row[$j] = stripslashes($row[$j]);
                    $row[$j] = addslashes($row[$j]);


                    if (isset($row[$j])) {
                        $return.= '"' . $row[$j] . '"';
                    } else {
                        $return.= '""';
                    }
                    if ($j < ($num_fields - 1)) {
                        $return.= ',';
                    }
                }
                $return.= ");\n";
            }
        }
        $return.="\n\n\n";
    }

    //save file
    $handle = fopen('db_backup/' . $filename, 'w+');
    fwrite($handle, $return);
    fclose($handle);

    $zip_name = $filename . ".zip";
    $archive = new PclZip($zip_name);
    $archive->create('db_backup/' . $filename);

    unlink('db_backup/' . $filename);
    copy($filename . ".zip", 'db_backup/' . $filename . ".zip");
    unlink($zip_name);
    return "<br><br><br><p style='padding-left:25px;'>All Done! Database has been backed up and archived in the 'admin/db_backup' folder, and it's name is <b>" . $filename . ".zip</b></p>";
}

require_once('system/pclzip.lib.php');
$do_it = backup_tables(SERVER_NAME, USER_NAME, PASSWORD, DB_NAME);
echo $do_it;