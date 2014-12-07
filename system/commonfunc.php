<?php

if (!defined('AFFREE')) {
    die('You cannot access this page directly!');
}


################################################################
#		Common Function
##################################################################
// strip string

function stripString($string) {
    return stripslashes($string);
}

// escape string without DB connection
function safeEscapeString($string) {
    if (is_array($string)) {
        return array_map(__METHOD__, $string);
    }
    if (!empty($string) && is_string($string)) {
        return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $string);
    }
}

//convert newlines and returns to <br/>
function n2br($string) {

    $stuff = array('\r\n', '\n', '\r');
    $string = str_replace($stuff, "<br/>", $string);

    return trim($string);
}

// check submitted email is valid
function check_email($email) {

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    return false;
}

//// Function for where

function Where($ID, $db, $pdo) {

    if ($pdo) {
        $retVal = "";
        $query = "SELECT * FROM tblcategories  WHERE intId = ?";
        $bind = array($ID);
        $result = select_pdo($query, $bind, "breadcrumb" . $ID . ".af", 3600);
    } else {
        $d = new db(0);
        $retVal = "";
        $sql = "SELECT * FROM tblcategories  WHERE intId =" . $ID;
        $result = $d->fetch($sql, 3600, "breadcrumb" . $ID . ".af");
    }
    $totalRows_RsWhere = count($result);
    if ($totalRows_RsWhere > 0) {
        if ($result[0]['intParentID'] > 0) {
            echo $retVal = " &nbsp; | &nbsp; <a href='categorydetail.php?Cat=" . $ID . "'> " . $result[0]['varCategory'] . "</a> " . Where($result[0]['intParentID'], $db, $pdo);
        } else {
            echo "You are at : <a href='index.php' style='font-size:12px background-color:#000000'> Home </a> | <a href='categorydetail.php?Cat=" . $ID . "'> " . $result[0]['varCategory'] . "</a> ";
        }
    } else {
        echo"";
    }
}

// Get Categories
function Menu($d, $ParentID, $num) {
    $i = 0;
    $value = "";
    $c = "";
    if ($_SESSION['pdo'] == true) {
        $pdo = true;
    } else {
        $pdo = false;
    }
    $d = new db(0);
    for ($i = 0; $i < $num; $i++)
        $c.="&nbsp;&nbsp;";
    if ($pdo) {
        $query = "SELECT * FROM tblcategories  WHERE intParentID = ? ORDER BY varCategory ASC";
        $bind = array($ParentID);
        $result = select_pdo($query, $bind, "cat_parID" . $ParentID . ".af", 3600);
    } else {
        $sql = "SELECT * FROM tblcategories  WHERE intParentID = " . $ParentID . " ORDER BY varCategory ASC";
        $result = $d->fetch($sql, "daily", md5("catparent") . md5($ParentID) . ".af"); // expires in one day
    }

    $totalRows_Rs = count($result);
    $location = "categorydetail.php";
    $cat = 0;
    if (isset($_GET['Cat']) && trim($_GET['Cat']) != "") {
        $tmpp_cat = sanitize_paranoid_string($_GET['Cat']);
        $cat = safeEscapeString($tmpp_cat);

        if ($pdo) {
            $sql = "SELECT * FROM tblcategories  WHERE intParentID = ? ORDER BY varCategory ASC";
            $bind = array($cat);
            $resultchecksub = select_pdo($sql, $bind, "cat_parID" . $cat . ".af", 3600);
        } else {
            $sql = "SELECT * FROM tblcategories  WHERE intParentID = " . $cat . " ORDER BY varCategory ASC";
            $resultchecksub = $d->fetch($sql, "daily", md5("catID") . md5($cat) . ".af"); // expires in one day
        }
    }

    if ($totalRows_Rs > 0) {
        // get parent id of current category to check if it is a main cat or sub cat

        if ($pdo) {
            $query_sql = "SELECT intParentID FROM tblcategories  WHERE intID = ?";
            $bind = array($cat);
            $qryResult = select_pdo($query_sql, $bind, "cat_intID" . $cat . ".af", 3600);
        } else {

            $query = "SELECT intParentID FROM tblcategories  WHERE intID = " . $cat . "";
            $qryResult = $d->fetch($query, "daily", md5("intparent") . md5($cat) . ".af"); // expires in one day
        }


        for ($i = 0; $i < $totalRows_Rs; $i++) {
            //$imgfile = "images/spacer.gif";
            if (isset($cat) && trim($cat) != "" && $cat == $result[$i]['intID']) {
                $imgfile = "images/spacer.gif";
            }

            // get number of articles in category
            if ($result[$i]['intParentID'] == 0) {
                $sum = 0;

                if ($pdo) {
                    $query2 = "SELECT intID FROM tblcategories WHERE intParentID = ?";
                    $bind = array($result[$i]['intID']);
                    $qry2Result = select_pdo($query2, $bind, "cat_tblcat" . $result[$i]['intID'] . ".af", 3600);
                } else {
                    $query2 = "SELECT intID FROM tblcategories WHERE intParentID = " . $result[$i]['intID'];
                    $qry2Result = $d->fetch($query2, "daily", md5("tblcat2") . md5($result[$i]['intID']) . ".af"); // expires in one day
                }


                for ($j = 0; $j < $rows; $j++) {

                    if ($pdo) {
                        $query3 = "SELECT count(intID) FROM tblarticles WHERE intCategory= ? AND intStatus = 1";
                        $bind = array($qry2Result[$j]['intID']);
                        $qry3Result = select_pdo($query3, $bind);
                    } else {
                        $query3 = "SELECT count(intID) FROM tblarticles WHERE intCategory=" . $qry2Result[$j]['intID'] . " AND intStatus = 1";
                        $qry3Result = $d->fetch($query3); // expires in one day
                    }

                    $sum = $sum + $qry3Result[0]['count(intID)'];
                }


                // add articles that belong to main category

                if ($pdo) {
                    $query5 = "SELECT count(intID) as num_rows FROM tblarticles WHERE intCategory= ? AND intStatus = 1";
                    $bind = array($result[$i]['intID']);
                    $qry5Result = select_pdo($query5, $bind);
                } else {
                    $query5 = "SELECT count(intID) as num_rows FROM tblarticles WHERE intCategory=" . $result[$i]['intID'] . " AND intStatus = 1";
                    $qry5Result = $d->fetch($query5); // expires in one day
                }

                $numArticles = $sum + $qry5Result[0]['num_rows'];
            } else {
                if ($pdo) {
                    $query4 = "SELECT count(intID) as num_articles FROM tblarticles WHERE intCategory= ? AND intStatus = 1";
                    $bind = array($result[$i]['intID']);
                    $qry4Result = select_pdo($query4, $bind);
                } else {
                    $query4 = "SELECT count(intID) as num_articles FROM tblarticles WHERE intCategory=" . $result[$i]['intID'] . " AND intStatus = 1";
                    $qry4Result = $d->fetch($query4); // expires in one day
                }

                $numArticles = $qry4Result[0]['num_articles'];
            }
            if ($result[$i]['intParentID'] == 0) {
                $value = $value . "<p><b><a href='" . $location . "?Cat=" . $result[$i]['intID'] . "&amp;level=$num&amp;title="
                    . str_replace($bad, $good, $result[$i]['varCategory']) . "'>" . substr($result[$i]['varCategory'], 0, 24) . " (" . $numArticles . ")</a></b></p>";
            } else {
                $value = $value . "<p>- <a href='" . $location . "?Cat=" . $result[$i]['intID'] . "&amp;level=$num&amp;title="
                    . str_replace($bad, $good, $result[$i]['varCategory']) . "'>" . substr($result[$i]['varCategory'], 0, 24) . " (" . $numArticles . ")</a></p>";
            }

            // check if cat is a main category or a sub category
            if ($qryResult[0]['intParentID'] == 0) { // main
                if ($cat > 0 && $result[$i]['intID'] == $cat) {
                    $value = $value . Menu($obj_db, $result[$i]['intID'], $num + 1);
                }
            } else { // sub
                if ($cat > 0 && $result[$i]['intID'] == trim($qryResult[0]['intParentID'])) {
                    $value = $value . Menu($obj_db, $result[$i]['intID'], $num + 1);
                }
            }
        }
    }

    return stripString($value) . "";
}

// FUNCTION FOR CHECKING ACTIONS OF FORM
function actionfrmcheck($var = "", $val = "") {
    //echo $_REQUEST[$var]."$var-->$val";
    if (isset($_REQUEST[$var]) && ($_REQUEST[$var] != "") && ($_REQUEST[$var] == $val)) {
        return true;
    } else {
        return false;
    }
}

// FUNCTION FOR CHECKING ACTIONS OF FORM
function actionfrmcheckrm($var = "") {
    //echo $_REQUEST[$var]."$var-->$val";
    if (isset($_REQUEST[$var]) && ($_REQUEST[$var] != "")) {
        return true;
    } else {
        return false;
    }
}

//FUNCTION FOR THE PER PAGE ROW DISPLAY DROP DOWN CREATE
// function drpdown_display($maxval)
// {
// 	//echo " <select name='sltpage' onChange='this.form.submit();'>";
// 	//echo "<option value=0>Select</option>";
// 	if(actionfrmcheckrm('sltpage') )
// 	{
// 			$tmppage=$_REQUEST['sltpage'];
// 	}
// 	for($i=0;$i<$maxval;$i=$i+ DRPPG )
// 	{
// 		$slted="";
// 		if($tmppage==$i)
// 		{
// 			$slted=" Selected";
// 		}
//
// 		if($i==0)
// 		{
// 			echo "<option value=".$i." $slted >".All."</option>";
// 		}
// 		else
// 		{
// 			echo "<option value=".$i." $slted >".$i."</option>";
// 		}
// 	}
// 	echo "</select>";
// }
// function used for remmoving anything but numbers from a string (such as Author ID's Article Id's ect..)
function sanitize_paranoid_string($string, $min = '', $max = '') {
    $string = preg_replace("/[^0-9]/", "", $string);
    $len = strlen($string);
    if ((($min != '') && ($len < $min)) || (($max != '') && ($len > $max)))
        return FALSE;
    return $string;
}

////////////////////

function cache_cleanup() {
    $path = "cache/scache/";

    if (is_dir("$path")) {
        $handle = opendir($path);
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {

                @unlink($path . $file);
            }
        }



        closedir($handle);
    }
}

// This is a virtual cron that will empty the cache/scache folder if the time has expired
//==============================================================================================
class virtualcron {

    var $controlFile = "cache/cron.txt"; // the default url of the control file
    var $minDelay = CACHE_TIME; // the default delay period in minutes.

//==============================================================================================
// PUBLIC [Constructor]
// param $minDelay: sets the delay period in minutes (optional)
// param $conrolFile: sets the control file url (optional). The generation or last modification time
// of this file is used to estimate the time required to be passed, in order to allow an action.
// If there is no control file the function will try to generate one.
//==============================================================================================

    function virtualcron($minDelay = false, $controlFile = false) {
        if ($minDelay)
            $this->minDelay = $minDelay;
        if ($controlFile)
            $this->controlFile = $controlFile;
        $this->lastExec = 0; // it will contain the UNIXTIME of the last action
        $this->nextExec = 0; // it will contain the UNIXTIME of the next action
        $this->secToExec = 0; // it will contain the time in seconds until of the next action
        if (file_exists($this->controlFile))
            $this->check = true;
        else {
            $handle = fopen($this->controlFile, "w");
            if (!$handle)
                $this->check = false;
            else {
                if (!fwrite($handle, time()))
                    $this->check = false;
                else {
                    fclose($handle);
                    $this->check = true;
                }
            }
        }
    }

//==============================================================================================
// PUBLIC allowAction() [boolean]
// checks if the current execution time is within the delay period. Example:
// $vcron=new virtualcron();
// if ($vcron->allowAction()) ...do something...
// That's all
//==============================================================================================
    function allowAction() {
        $now = time();
        if ($this->check)
            $FT = $this->getFileCreationTime($this->controlFile);
        if ($FT) {
            $nextExec = $FT + ($this->minDelay * 60) - $now;
            if ($nextExec < 0) {
                $handle = fopen($this->controlFile, "w");
                if (!$handle)
                    return false;
                else {
                    if (!fwrite($handle, $now))
                        return false;
                    else {
                        fclose($handle);
                        $this->lastExec = $now;
                        $this->nextExec = $now + ($this->minDelay * 60);
                        $this->secToExec = $this->minDelay * 60;
                        return true;
                    }
                }
            } else {
                $this->lastExec = $FT;
                $this->nextExec = $FT + $nextExec;
                $this->secToExec = $nextExec;
                return false;
            }
        } else
            return false;
    }

//==============================================================================================
// PRIVATE getFileCreationTime()
// estimates the generation or last modification time of the control file (UNIXTIME)
//==============================================================================================
    function getFileCreationTime($filename) {
        if (function_exists("filemtime")) {
            $FT = filemtime($filename);
        } else {
            $FT = false;
        }
        return $FT;
    }

}

////////////////////

function convert($text) {

    $text = str_replace(
        array("\xe2\x80\x98", "\xe2\x80\x99", "\xe2\x80\x9c", "\xe2\x80\x9d", "\xe2\x80\x93", "\xe2\x80\x94", "\xe2\x80\xa6"), array("'", "'", '"', '"', '-', '--', '...'), $text);
// Next, replace their Windows-1252 equivalents.
    $text = str_replace(
        array(chr(145), chr(146), chr(147), chr(148), chr(150), chr(151), chr(133)), array("'", "'", '"', '"', '-', '--', '...'), $text);

    return $text;
}

function generate_salt() {
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' .
        '0123456789``-=~!@#$%^&*()_+,./<>?;:[]{}\|';
    $length = 10;
    $str = '';
    $max = strlen($chars) - 1;

    for ($i = 0; $i < $length; $i++)
        $str .= $chars[rand(0, $max)];

    return $str;
}

function shadow($pass) {
    return sha1($pass);
}

function rand_pass($length) {
    if ($length == '') {
        $length = 6;
    }
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-=~!@#$%^&*()";
    return substr(str_shuffle($chars), 0, $length);
}

// Limits string of words and used in the featured authors printed bio text
function myTruncate($string, $limit, $break = ".", $pad = "...") {
// return with no change if string is shorter than $limit
    if (strlen($string) <= $limit)
        return $string; // is $break present between $limit and the end of the string?
    if (false !== ($breakpoint = strpos($string, $break, $limit))) {
        if ($breakpoint < strlen($string) - 1) {
            $string = substr($string, 0, $breakpoint) . $pad;
        }
    }
    return $string;
}

?>
