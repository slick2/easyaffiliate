<?php
if (!$ss->Check() || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1)
  {
   header("location:index.php?filename=adminlogin");
   die();
  }
// If member is not login or session is not set
if(!isset($_SESSION['userid'])|| $_SESSION['userid'] == '')
{
	header("location:index.php?filename=adminlogin");
	die();
}	
$minwords= '';
$message= '';
$cnt= 0;

if (isset($_POST['wordcount']) && trim($_POST['wordcount'])!='') {
    $minwords= sanitize_paranoid_string($_POST['wordcount']);
    
if($pdo)
{
$query = "SELECT intId,textArticleText FROM tblarticles WHERE intStatus=?";
$bind = array(0);
$result = select_pdo($query,$bind);
}else{
    $query= "SELECT intId,textArticleText FROM tblarticles WHERE intStatus='0'";
    $result=$d->fetch($query);
 }     
    foreach($result as $row) {
    
      $wordcount= count(array_filter(explode(' ',$row['textArticleText'])));
      
      if ($wordcount< $minwords) {
        $artid= $row[intId];
       if($pdo)
{
$query = "DELETE FROM tblarticles WHERE intId= ? LIMIT ?";
$bind = array($artid,1);
$result2 = select_pdo($query,$bind);
}else{ 
        $query2= "DELETE FROM tblarticles WHERE intId='$artid' LIMIT 1";
        $result2= $d->fetch($query2);
  }
        $cnt++;
      }
      
              }   
      }
     $message= '<br><br><center><b>Deleted: '.$cnt.' article(s) from the database.</b></center><br><br>';
    
      
$page=
'<br><br><div align="center"><h2>Short Article Deleter</h2><br><br>
<form method="post" action="index.php?filename=wordcount">
<b>Only checks unapproved articles!</b><br/>
Enter the <b>minimum</b> (e.g. 200) amount of words allowed per article?<br /><br>
<input name="wordcount" type="text" size="5" value="'.$minwords.'" /><br /><br>
*Be sure to backup your database before using this mod,  and be aware it could take a long time to complete or timeout on huge databases!<br><br>
<input type="submit" /></div>
</form>';
echo $message.$page;

?>
<br><br>
<div align="center"><a href="javascript:history.back()">Back</a></div>
