<?php
if (!$ss->Check() || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1)
  {
   header("location:index.php?filename=adminlogin");
   die();
  }
// If member is not login or session is not set
if(!isset($_SESSION['userid']) || $_SESSION['userid'] == '')
{
	header("location:index.php?filename=adminlogin");
	die();
}
?>
<table border="0" align="center" cellpadding="1" cellspacing="2">
  
<?php

// Set this variabile for the number of keywords to be found in an article. 
//If an keyword is found more then one times in an article it will be considered as a different keyword.
//So if you have an article in which one keyword is found 8 times and another kw is found 2 times then the total keywords found are 10. 

$minkwtofind=5;


//*********************************//
//** Do not edit below this line **//
//*********************************//

//MySQL connect

if($pdo)
{
$query = "SELECT intId,intCategory,varArticleTitle,textSummary,textArticleText,textResource,varKeywords FROM tblarticles WHERE intStatus=?";
$bind = array(0);
$result = select_pdo($query,$bind);
}else{
$findauth_id="SELECT intId,intCategory,varArticleTitle,textSummary,textArticleText,textResource,varKeywords FROM tblarticles WHERE intStatus='0'";
$result = $d->fetch($findauth_id);
}
$x=0;
$num_rows=count($result);
if ($num_rows>0)
	   {
		foreach ($result as $row)
		 { 
		  $article[$x][id]=$row[intId];
		  $article[$x][categid]=$row[intCategory];
		  $article[$x][text]=strtolower($row[varArticleTitle])." ".strtolower($row[textSummary])." ".strtolower($row[textArticleText])." ".strtolower($row[textResource])." ".strtolower($row[varKeywords]);
		  $x++;
		 }
	   }
else {print "<br><br><center><font color='red'><b>No articles to process</b></font><br><br><a href='index.php?filename=app'>Back</a>"; exit;}

if($pdo)
{
$query = "SELECT * FROM keywords";

$result1 = select_pdo($query);
}else{
$findkwds="SELECT * FROM keywords";
$result1 = $d->fetch($findkwds);
}
//$num_rows1=count($result1);
if ($result1)
	   {
	foreach($result1 as $row1)
		 { 
		  $kwds[$row1[category_id]]=strtolower($row1[keywords]);

		 }
	   }
else {print "<br><br><center><font color='red'><b>No keywords in DB</b></font><br><br><a href='index.php?filename=app'>Back</a>"; exit;}

if($pdo)
{
$query = "SELECT * FROM tblcategories";

$result2 = select_pdo($query);
}else{
$findcat="SELECT * FROM tblcategories";
$result2 = mysql_query($findcat);
}
$num_rows2=count($result2);
if ($num_rows2>0)
	   {
		foreach($result2 as $row2)
		 { 
		  $cat[$row2[intID]]=$row2[varCategory];

		 }
	   }
else {print "<br><br><center><font color='red'><b>No keywords in DB</b></font><br><br><a href='index.php?filename=app'>Back</a><br></center>"; exit;}

$badkw=explode(",",$kwds[-1]);

$i=0;$j=0;$badart=0;
foreach ($article as $key=>$tmp)
{
//print "<b> ".($key+1)." .</b> ";

//check for bad words

      $countkwinart=0;
      foreach ($badkw as $tmpkw) { $countkwinart+=substr_count($tmp[text], $tmpkw);}
      if  ($countkwinart>0) 
         {
          print "Found $countkwinart bad keywords in the $tmp[id] ( \"".$cat[$tmp[categid]]."\" category ). ";
          
if($pdo)
{
$query = "DELETE FROM tblarticles WHERE intId=?";
$bind = array($tmp[id]);
$updresult = delete_pdo($query,$bind);
}else{
          $updresult=$d->exec("DELETE FROM tblarticles WHERE intId='".$tmp[id]."'");
}

	  
	  if ($updresult == 0) {print "Bad article not deleted!!!<br>";} else {print "Article deleted.<br>";$badart++;}
         }
      else {


		$tryothercateg=0;
		if ($tmp[categid]!=0) 
		  {
		   $i++;
		//print "keywords ".$kwds[$tmp[categid]]."<br>";
		if (!isset($kwds[$tmp[categid]])) { 
    
    
    $tryothercateg=1;
    
    }
		else{
		      $kw=explode(",",$kwds[$tmp[categid]]);
		//      print_r($kw);
		      $countkwinart=0;
		      foreach ($kw as $tmpkw)	{ $countkwinart+=substr_count($tmp[text], $tmpkw);}
		      if  ($countkwinart>=$minkwtofind) 
		         {
		          print "Found $countkwinart keywords in the $tmp[id] ( \"".$cat[$tmp[categid]]."\" category ). ";
              
if($pdo)
{
$query = "UPDATE tblarticles SET intStatus = ? WHERE intId= ?";
$bind = array(1,$tmp[id]);
$updresult = update_pdo($query,$bind);
}else{              
		    $updatestr="UPDATE tblarticles SET intStatus = '1' WHERE intId='".$tmp[id]."'";
			  $updresult = $d->exec($updatestr);
}
			  if (!$updresult) {print "DB NOT updated!!!<br>";} else {print "DB Updated<br>";$j++;}
		         }
		      else {$tryothercateg=1;}
		    }
		  }
		if (($tmp[categid]==0) or ($tryothercateg))
		  {
		   
		   foreach ($kwds as $categid=>$kwstr)
		    {
		      $kw=explode(", ",$kwstr);
		//      print_r($kw);
		      $countkwinart=0;
		      foreach ($kw as $tmpkw)	{ $countkwinart+=substr_count($tmp[text], $tmpkw);}
		      if  ($countkwinart>=$minkwtofind) 
		         {
		          print "Found $countkwinart keywords in the \"".$cat[$categid]."\" category . ";
          if($pdo)
{
$query = "UPDATE tblarticles SET intStatus = ?,intCategory= ? WHERE intId= ?";
$bind = array(1,$categid,$tmp[id]);
$result = update_pdo($query,$bind);
}else{
		    $updatestr="UPDATE tblarticles SET intStatus = '1',intCategory='".$categid."' WHERE intId='".$tmp[id]."'";
			  $updresult = $d->exec($updatestr);
}
			  if ($updresult == 0) {print "DB NOT updated!!!<br>";} else {print "DB Updated<br>";$j++;}
		          break;
		         }
		    }
		  
		  }
	   }
    }
print "<br><br>***********************************************************************<br>Total articles processed: ".$num_rows."<br>";
print $badart." bad articles deleted.<br>";
print $i." articles with suggested category<br>";
print ($num_rows-$i)." articles with no suggested category<br>";
print $j." articles accepted<br>";
print ($num_rows-$j-$badart)." articles not accepted yet. Please <a href=\"index.php?filename=app\">add more keywords</a> to the categories if this number is not ok for manual processing.<br>";

?> 

