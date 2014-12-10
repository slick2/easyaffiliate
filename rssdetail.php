<?php

///////////////////// TERMS OF USE //////////////////////////
//
//  1. You must keep the link at the bottom of at least the index.php page on the frontend.
//  2. You cannot give AF Free to your friends family or anyone else. Anyone that wants AF Free
//     must signup for the download at articlefriendly.com.
//  3. You may use AF Free on as many of your own sites as you wish, but not for clients or others.
//     They must signup for their own copy of AF Free also.
//
/////////////////////////////////////////////////////////////
if (@ini_get('zlib.output_compression') != '1' || @ini_get('output_handler') != 'ob_gzhandler') {
    if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') || substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate')) {
        ob_start("ob_gzhandler");
    }
} else {
    ob_start();
}
session_start();
if (!get_magic_quotes_gpc()) {

    $_GET = array_map('addslashes', $_GET);
    $_POST = array_map('addslashes', $_POST);
    $_COOKIE = array_map('addslashes', $_COOKIE);
    $_REQUEST = array_map('addslashes', $_REQUEST);
}
define('AFFREE', 1);
include("system/config.inc.php");
$strip1 = "";
$strip11 = "";
$leadin = "";
$strip2 = "";
$sting22 = "";
$string = "";
$step = "";
$categid = "";
$result = "";
$rsstitle = "";
$rssdesc = "";
$rsslink = "";

/**
 *
 * Function: CleanupSmartQuotes
 * Strip punctuation from text.
 *
 */
function CleanupSmartQuotes($text) {
    $badwordchars = array(
        chr(145),
        chr(146),
        chr(147),
        chr(148),
        chr(151)
    );
    $fixedwordchars = array(
        "'",
        "'",
        '&quot;',
        '&quot;',
        '&mdash;'
    );
    return str_replace($badwordchars, $fixedwordchars, $text);
}

/**
 * Function strip_punctuation
 * Used to strip the punctuation
 * @param type $text
 * @return type
 */
function strip_punctuation($text) {
    $urlbrackets = '\[\]\(\)';
    $urlspacebefore = ':;\'_\*%@&?!' . $urlbrackets;
    $urlspaceafter = '\.,:;\'\-_\*@&\/\\\\\?!#' . $urlbrackets;
    $urlall = '\.,:;\'\-_\*%@&\/\\\\\?!#' . $urlbrackets;

    $specialquotes = '\'"\*<>';

    $fullstop = '\x{002E}\x{FE52}\x{FF0E}';
    $comma = '\x{002C}\x{FE50}\x{FF0C}';
    $arabsep = '\x{066B}\x{066C}';
    $numseparators = $fullstop . $comma . $arabsep;

    $numbersign = '\x{0023}\x{FE5F}\x{FF03}';
    $percent = '\x{066A}\x{0025}\x{066A}\x{FE6A}\x{FF05}\x{2030}\x{2031}';
    $prime = '\x{2032}\x{2033}\x{2034}\x{2057}';
    $nummodifiers = $numbersign . $percent . $prime;

    return preg_replace(
        array(
        // Remove separator, control, formatting, surrogate,
        // open/close quotes.
        '/[\p{Z}\p{Cc}\p{Cf}\p{Cs}\p{Pi}\p{Pf}]/u',
        // Remove other punctuation except special cases
        '/\p{Po}(?<![' . $specialquotes .
        $numseparators . $urlall . $nummodifiers . '])/u',
        // Remove non-URL open/close brackets, except URL brackets.
        '/[\p{Ps}\p{Pe}](?<![' . $urlbrackets . '])/u',
        // Remove special quotes, dashes, connectors, number
        // separators, and URL characters followed by a space
        '/[' . $specialquotes . $numseparators . $urlspaceafter .
        '\p{Pd}\p{Pc}]+((?= )|$)/u',
        // Remove special quotes, connectors, and URL characters
        // preceded by a space
        '/((?<= )|^)[' . $specialquotes . $urlspacebefore . '\p{Pc}]+/u',
        // Remove dashes preceded by a space, but not followed by a number
        '/((?<= )|^)\p{Pd}+(?![\p{N}\p{Sc}])/u',
        // Remove consecutive spaces
        '/ +/',
        ), ' ', $text);
}

$articleid = sanitize_paranoid_string($_GET['rss']);

if ($articleid > 0) {
    if ($pdo) {
        $query = "SELECT * FROM tblarticles where intId = ? AND intStatus = 1 ";
        $bind = array($articleid);
        $result = select_pdo($query, $bind);
    } else {
        $result = $d->fetch("SELECT * FROM tblarticles where intId = '" . safeEscapeString($articleid) . "' AND intStatus = 1 ");
    }
} else {
    header("HTTP/1.1 404 Not Found");
    exit();
}
$article = $result[0];

$rsstitle = $title;
$rssdesc = "Fresh articles from $title";
$rsslink = $site_URL;

header("Content-type: text/xml");
#echo '<textarea cols="80" rows="40">';
echo '<rss version="2.0">' . "\n";
echo "<channel>\n";
echo '<title>' . htmlentities(strip_tags($rsstitle)) . "</title>\n";
echo '<description>' . htmlentities(strip_tags($rssdesc)) . "</description>\n";
echo '<link>' . htmlentities($rsslink) . "</link>\n";
echo "</channel>\n";


echo "<item>\n";
echo "<title>{$article['varArticleTitle']}</title>\n";
echo "<guid>" . $rsslink . "articledetail.php&#63;artid=" . htmlentities(strip_tags($article['intId'])) . "&amp;catid=" . htmlentities(strip_tags($article['intCategory'])) . "</guid>\n";
echo "<description>".$article['textSummary']."</description>\n";
echo "<content>".$article['textArticleText']."</content>\n";
echo "<link>" . $rsslink . "articledetail.php&#63;artid=" . htmlentities(strip_tags($article['intId'])) . "&amp;catid=" . htmlentities(strip_tags($article['intCategory'])) . "</link>\n";
echo "</item>\n";


echo "</rss>";
#echo '</textarea>';
ob_end_flush();
