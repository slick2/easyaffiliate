<?php
if (!$ss->Check() || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1)
  {
   header("location:index.php?filename=adminlogin");
   die();
  }
if(!isset($_SESSION['userid'])|| $_SESSION['userid'] == '')
{
	header("location:index.php?filename=adminlogin");
	die();
}	

?>

<br>
<table height="50"  border="0" align="center" cellpadding="30" cellspacing="0">
  <tr>
    <td align="left" valign="middle" class="black_note">
<center><h2>Results</h2><br>
<?php
  $var = safeEscapeString($_POST['art_title']) ;
  $trimmed = trim($var);
  $limit=10; 

if ($trimmed == "")
  {
  echo "<p>Please enter a search...</p>";
  echo "<div align='center'><p><a href='index.php?filename=dupe_form'>Back To Search Form</a></p></div>";
  exit;
  }

if (!isset($var))
  {
  echo "<p>We dont seem to have a search parameter!</p>";
  echo "<div align='center'><p><a href='index.php?filename=dupe_form'>Back To Search Form</a></p></div>";
  
  exit;
  }
  
  if($pdo)
{
$var = $_POST['art_title'];
$query = "select intAuthorId,varArticleTitle,textSummary from tblarticles WHERE varArticleTitle like ?";
$bind = array("%".$var."%");
$connection = select_pdo($query,$bind);
}else{
$connection = $d->fetch("select intAuthorId,varArticleTitle,varFirstName,varlastName,textSummary from tblarticles WHERE varArticleTitle like '%$var%'");
}

  $numrows=count($connection);
  // If we have no results, offer a google search as an alternative

if( $numrows == 0)
  {
  
  echo "<p>Sorry, your search: &quot;" .htmlentities($trimmed, ENT_QUOTES, "UTF-8"). "&quot; returned zero results</p>";

// google
 echo "<p><a href='https://www.google.com/search?q=". urlencode($trimmed) ."' target='_blank'><font color='blue'>Click here</font></a> to try the 
  search on Google search engine</p></center>";
  echo "<div align='center'><a href='index.php?filename=dupe_form'>Back To Search Form</a>";
  
// Yahoo
 echo "<p><a href='http://search.yahoo.com/search?p=
 ".urlencode($trimmed)."' target='_blank'><font color='blue'>Click here</font></a> to try the 
  search on Yahoo search engine</p></center>";
  echo "<div align='center'><a href='index.php?filename=dupe_form'>Back To Search Form</a>";
  
  die();
  }else{
  

// next determine if s has been passed to script, if not use 0
  if (empty($s)) {
  $s=0;
  }

// display what the person searched for
echo "<p>You searched for: &quot;" .htmlentities($trimmed, ENT_QUOTES, "UTF-8"). "&quot;</p>";


// begin to show results set
echo "<br><br><h3>A match was found!</h3></center>";
$count = 1 + $s ;

// now you can display the results returned

$auth_id = $connection[0]['intAuthorId'];

  
  
if($pdo)
{
$query = "select * from tblauthor WHERE intId = ? LIMIT ?"; 
$bind = array($auth_id,1);
$result = select_pdo($query,$bind);
}else{
$result = $d->fetch("select varFirstName,varlastName from tblauthor WHERE intId = '$auth_id' LIMIT 1");
}
$authorf = convert(stripString($result[0]['varFirstName']));
$authorl = convert(stripString($result[0]['varlastName']));

 foreach($connection as $row) {
  $title = convert(stripString($row["varArticleTitle"]));
  $summary = n2br(convert(stripString($row['textSummary'])));

  echo $count.")&nbsp;<b>".$title."</b> By: ".$authorf." ".$authorl."<br><br>".$summary."<br><br>" ;
  $count++ ;
  }

$currPage = (($s/$limit) + 1);

//break before paging
  echo "<br />";

  // next we need to do the links to other results
  if ($s>=1) { // bypass PREV link if s is 0
  $prevs=($s-$limit);
  print "&nbsp;<a href=\"$PHP_SELF?s=$prevs&q=$var\">&lt;&lt; 
  Prev 10</a>&nbsp&nbsp;";
  }

// calculate number of pages needing links
  $pages=intval($numrows/$limit);

// $pages now contains int of pages needed unless there is a remainder from division

  if ($numrows%$limit) {
  // has remainder so add one page
  $pages++;
  }

// check to see if last page
  if (!((($s+$limit)/$limit)==$pages) && $pages!=1) {

  // not last page so give NEXT link
  $news=$s+$limit;

  echo "&nbsp;<a href=\"$PHP_SELF?s=$news&q=$var\">Next 10 &gt;&gt;</a>";
  }

$a = $s + ($limit) ;
  if ($a > $numrows) { $a = $numrows ; }
  $b = $s + 1 ;
  echo "<p>Showing results $b to $a of $numrows</p>";
  echo "<br><br><div align='center'><a href='index.php?filename=dupe_form'>Back To Search Form</a><br>";
 echo $titles;
 }
?>
</td>
  </tr>
</table>

