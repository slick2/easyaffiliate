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

$id = "intId";

$field1 = "varArticleTitle";

$delay = "2"; // Delay to help prevent prevent excessive loads
/////////////// end config
// build query
if ($pdo) {
    $query = "SELECT * from tblarticles group by ? having count(*) > ?";
    $bind = array($field1, 1);
    $result = select_pdo($query, $bind);
    $dupes = count($result);
} else {
    $query = "SELECT * from tblarticles group by $field1";
    $query .= " having count(*) > 1";
// locate the dupes
    $result = $d->fetch($query);
    $dupes = count($result);
}
// remove the dupes
foreach ($result as $myrow) {
    if ($pdo) {
        $query = "DELETE from tblarticles WHERE ? = ?";
        $bind = array($id, $myrow[0]);
        $delete = delete_pdo($query, $bind);
    } else {
        $delete = $d->exec("DELETE from tblarticles WHERE " . $id . " = '$myrow[0]'");
    }
// if ($delete === true){
// echo "entry ". $myrow[0] ." removed <br>";
// sleep ($delay);
// }else{
// echo "No dupe found/removed <br>";
// }
}
// re-optimize table if more than 1 dupe was removed
if ($dupes > 2) {
    echo "<br>optimizing tblarticles";
    if ($pdo) {
        $query = "OPTIMIZE TABLE tblarticles";
        $optimize = select_pdo($query);
    } else {
        $optimize = $d->fetch("OPTIMIZE TABLE tblarticles");
    }
}
if ($myrow[0] === true) {
    echo "<html>
      <head>
      <title>Delete Duplicates</title>
      <meta http-equiv='Content-Type; content='text/html; charset=utf-8'>

      </head>
      <body><br><br>
      <div align='center'><h2>Results</h2><br><br>
      <font size='2'>Found " . $dupes . " duplicate entries<br><br>
       Check Again Later.</b><br></div></font>
      </body>
      </html>";
} else {
    ?>
    <html>
        <head>
            <title>Delete Duplicates</title>
            <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        </head>

        <body><br /><br />
            <div align="center"><h2>Results</h2><br /><br /><font size='2'>
                <b>No Dupes Found! Nothing Removed...</font><br />
            </div>
        </body>
    </html>
    <?php
}
