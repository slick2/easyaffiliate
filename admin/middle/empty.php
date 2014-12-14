<?php

if (!$ss->Check() || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1) {
    header("location:index.php?filename=adminlogin");
    die();
}
if (!isset($_SESSION['userid']) || $_SESSION['userid'] == '') {
    header("location:index.php?filename=adminlogin");
    die();
}
if ($pdo) {
    $query = "select intId from tblauthor WHERE intStatus = ?";
    $bind = array(1);
    $result = select_pdo($query, $bind);
} else {
    $sql = "select intId from tblauthor WHERE intStatus = '1'";
    $result = $d->fetch($sql);
}

// Find the authors ID number in articles table...
// If not found, delete the author's account

foreach ($result as $row) {
    if ($pdo) {
        $query = "SELECT intCategory FROM tblarticles WHERE ? = intAuthorId";
        $bind = array($row['intId']);
        $results = select_pdo($query, $bind);
    } else {
        $new_sql = "SELECT intCategory FROM tblarticles WHERE " . $row['intId'] . " = intAuthorId";
        $results = $d->fetch($new_sql);
    }
    if (count($results) <= 0) {
        if ($pdo) {
            $query = "Delete from tblauthor where intId = ? AND txtBAN = ?";
            $bind = array($row['intId'], "No");
            $del = delete_pdo($query, $bind);
        } else {
            $sql_del = "Delete from tblauthor where intId = " . $row['intId'] . " AND txtBAN = 'No'";
            $del = $d->fetch($sql_del);
        }
    }
}

if ($del !== false && $del != '') {
    echo "<p style='padding-left:50px;'><br><br><br>All done! Removed " . $del . " Non-contributing authors from the database!</p>";
} else {
    echo "<p style='padding-left:50px;'><br><br><br>All done! No Non-contributing authors found to remove! Try again later.</p>";
}

