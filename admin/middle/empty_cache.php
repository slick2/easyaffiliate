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
$path = "../cache/scache/";
cache_cleanup($path);
$path = "cache/acache/";
cache_cleanup($path);
?>
<br /><br /><br />
<p align='center'>Done! Both the frontend and admin caches have been emptied.</p>

