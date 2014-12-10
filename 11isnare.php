<?php
/**
 * @package Article Friendly
 */
if (!$_POST) {
    echo "You do not have authority to view this page!";
    die();
}

$ip = $_SERVER["REMOTE_ADDR"];

// check for isnare ip address. die if not right
if ($ip != '96.44.178.162') {
    die("bad IP");
}

if (isset($_POST["article_summary"]) && isset($_POST["article_body_text"]) && isset($_POST["article_bio_text"])) {
    $_POST["article_summary"] = n2br($_POST["article_summary"]);
    $_POST["article_body_text"] = n2br($_POST["article_body_text"]);
    $_POST["article_bio_text"] = n2br($_POST["article_bio_text"]);
    $_POST["article_body_html"] = n2br($_POST["article_body_html"]);
    $_POST["article_bio_html"] = n2br($_POST["article_bio_html"]);
}

function EscapeString($string) {
    if (is_array($string)) {
        return array_map(__METHOD__, $string);
    }
    if (!empty($string) && is_string($string)) {
        return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $string);
    }
}

if (!get_magic_quotes_gpc()) {

    $_GET = array_map('EscapeString', $_GET);
    $_POST = array_map('EscapeString', $_POST);
    $_COOKIE = array_map('EscapeString', $_COOKIE);
    $_REQUEST = array_map('EscapeString', $_REQUEST);
}

define('AFFREE', 1);
require_once("system/config.inc.php");



$_GET = array_map('stripslashes', $_GET);
$_POST = array_map('stripslashes', $_POST);
$_COOKIE = array_map('stripslashes', $_COOKIE);
$_REQUEST = array_map('stripslashes', $_REQUEST);


$bad = array("-", "&", "'", '"', "%");
$good = array(" ", "&amp ", " ", " ", " ");



$titles = $_POST["article_title"];
$title = str_replace($bad, $good, $titles);
$title = htmlentities($title);

$author = explode(" ", $_POST["article_author"]);
$author = $author;


$summarys = $_POST["article_summary"];
$summary = str_replace($bad, $good, $summarys);
$summary = htmlentities($summary);


$category = $_POST["article_category"];
$category = htmlentities($category);

$body_texts = $_POST["article_body_text"];
$body_text = str_replace($bad, $good, $body_texts);
$body_text = htmlentities($body_text);

$body_html = $_POST["article_body_html"];
$body_html = $body_html;

$bio_text = $_POST["article_bio_text"];
$bio_text = htmlentities($bio_text);

$bio_html = $_POST["article_bio_html"];
$bio_html = stripslashes($bio_html);

$keywords = $_POST["article_keywords"];
$keywords = htmlentities($keywords);

//$email = $_POST["article_email"];

$fname = $author[0];

$lname = $author[1];

$email = $fname . "." . $lname . "@isnare.com";



$iSnare_cat_id = "";

$iSnare_user_id = "";



if (preg_match("/[\x80-\xff]/", $title)) {
    die;
}

if (preg_match("/[\x80-\xff]/", $summary)) {

    die;
}

if (preg_match("/[\x80-\xff]/", $body_text)) {

    die;
}




if ($pdo) {
    $query = "SELECT intId FROM tblauthor WHERE varEmail = ?";
    $bind = array($email);
    $verify_user = select_pdo($query, $bind);
} else {

    $check_user = "SELECT intId FROM tblauthor WHERE varEmail = '" . safeEscapeString($email) . "'";

    $verify_user = $d->fetch($check_user);
}
$num_rows = count($verify_user);

if (!$num_rows) {

    if ($pdo) {
        $date = date("Y-m-d");
        $query = "INSERT INTO tblauthor(varEmail, varPassword, varFirstName, varlastName, varAddress1, varAddress2, varZip, varCity, varState, intCountry,
 varPhone, varFax, intIsTerms, dtRegisteredDate, intStatus)
 VALUES(?, ?, ?, ?, ?, ?, ?, ?,
 ?, ?, ?, ?, ?, ?, ?)";
        $bind = array($email, 'pass', $fname, $lname, 'isnare submission', 'address', 'zip', 'city', 'state', '4', 'Phone', 'Fax', 1, $date, 1);
        $result = insert_pdo($query, $bind);
    } else {

        $d->exec("INSERT INTO tblauthor(varEmail, varPassword, varFirstName, varlastName, varAddress1, varAddress2, varZip, varCity, varState, intCountry,
 varPhone, varFax, intIsTerms, dtRegisteredDate, intStatus)
 VALUES('" . safeEscapeString($email) . "', 'pass', '" . safeEscapeString($fname) . "', '" . safeEscapeString($lname) . "', 'isnare submission', 'address', 'zip', 'city',
 'state', '4', 'Phone', 'Fax', '1', '" . date("Y-m-d") . "', '1')");
    }
} else {
    $iSnare_user_id = $verify_user[0]['intId'];
}

if ($pdo) {
    $query = "SELECT intID FROM tblcategories WHERE varCategory = ?";
    $bind = array($category);
    $verify_user = select_pdo($query, $bind);
} else {

    $check_user = "SELECT intID FROM tblcategories WHERE varCategory = '" . safeEscapeString($category) . "'";

    $verify_user = $d->fetch($check_user);
}

$num_rows = count($verify_user);



If (!$num_rows) {



    die("category does not exist.");
} else {

    if ($pdo) {
        $query = "INSERT INTO tblarticles(intAuthorId, intCategory, varArticleTitle, textArticleText, intStatus, textSummary, varKeywords, textResource, ttSubmitDate)
  VALUES(?, ?, ?, ?, ?, ?,?, ?, ?)";
        $bind = array($iSnare_user_id, $iSnare_cat_id, $title, $body_text, 0, $summary, $keywords, $bio_text, $date);
        $result = insert_pdo($query, $bind);
    } else {

        $date = date("Y-m-d G:i:s");
        $d->exec("INSERT INTO tblarticles(intAuthorId, intCategory, varArticleTitle, textArticleText, intStatus, textSummary, varKeywords, textResource, ttSubmitDate)
  VALUES('" . safeEscapeString($iSnare_user_id) . "', '" . safeEscapeString($iSnare_cat_id) . "', '" . safeEscapeString($title) . "',
  '" . safeEscapeString($body_text) . "', '0', '" . safeEscapeString($summary) . "','" . safeEscapeString($keywords) . "', '" . safeEscapeString($bio_text) . "', '$date')");
    }
    echo "Submission successful, <a href='http://www.articlefriendly.com'>Article Friendly</a><br>";
}
?>
