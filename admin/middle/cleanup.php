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


if($pdo)
{

$query = "Delete from tblarticles where intId = ?";
$bind = array("NULL");
$results = delete_pdo($query,$bind);
$del_emptyintID = $results;
}else{   
$sql = "Delete from tblarticles where intId = ''";
$results = $d->exec($sql);
$del_emptyintID = $results;
}

if($pdo)
{
$query = "Delete from tblarticles where intCategory = ?";
$bind = array(0);
$results = delete_pdo($query,$bind);
$del_emptycatnum = $results;
}else{
$sql = "Delete from tblarticles where intCategory = '0'";
$results = $d->exec($sql);
$del_emptycatnum = $results;
}

if($pdo)
{
$query = "Delete from tblarticles where intAuthorId = ?";
$bind = array("NULL");
$results = delete_pdo($query,$bind);
$del_emptyintAuth = $results;
}else{
$sql = "Delete from tblarticles where intAuthorId = ''";
$results = $d->exec($sql);
$del_emptyintAuth = $results;
}

if($pdo)
{
$query = "Delete from tblarticles where varArticleTitle = ?";
$bind = array("NULL");
$results = delete_pdo($query,$bind);
$del_emptyart_title = $results;
}else{
$sql = "Delete from tblarticles where varArticleTitle = ''";
$results = $d->fetch($sql);
$del_emptyart_title = $results;
}

if($pdo)
{
$query = "Delete from tblarticles where textSummary = ?";
$bind = array("NULL");
$results = delete_pdo($query,$bind);
$del_emptyart_summary = $results;
}else{
$sql = "Delete from tblarticles where textSummary = ''";
$results = $d->fetch($sql);
$del_emptyart_summary = $results;
}

if($pdo)
{
$query = "Delete from tblarticles where textArticleText = ?";
$bind = array("NULL");
$results = delete_pdo($query,$bind);
$del_emptyart_body= $results;
}else{
$sql = "Delete from tblarticles where textArticleText = ''";
$results = $d->fetch($sql);
$del_emptyart_body = $results;
}

if($pdo)
{
$query = "Delete from tblauthor where intId = ?";
$bind = array("NULL");
$results = delete_pdo($query,$bind);
$del_emptyauth_id = $results;
}else{
$sql = "Delete from tblauthor where intId = ''";
$results = $d->fetch($sql);
$del_emptyauth_id = $results;
}

if($pdo)
{
$query = "Delete from tblauthor where varEmail = ?";
$bind = array("NULL");
$results = delete_pdo($query,$bind);
$del_emptyauth_email = $results;
}else{
$sql = "Delete from tblauthor where varEmail = ''";
$results = $d->fetch($sql);
$del_emptyauth_email = $results;
}

if($pdo)
{
$query = "Delete from tblauthor where varPassword = ?";
$bind = array("NULL");
$results = delete_pdo($query,$bind);
$del_emptyauth_password = $results;
}else{
$sql = "Delete from tblauthor where varPassword = ''";
$results = $d->fetch($sql);
$del_emptyauth_password = $results;
}

 if($pdo)
{
$query = "update tblauthor set intCountry = ? where intCountry = ?";
$bind = array(4,0);
$results = update_pdo($query,$bind);
$del_emptyauth_country = $results;
}else{
$sql = "update tblauthor set intCountry = '4' where intCountry = '0'";
$results = $d->fetch($sql);
$del_emptyauth_country = $results;
}


// Remove articles with no author
if($pdo)
{
$query = "select intAuthorId from tblarticles";
$result = select_pdo($query);
}else{
$sql = "select intAuthorId from tblarticles";
		$result = $d->fetch($sql);
}
		
			foreach($result as $row)
			{
			
$author = $row['intAuthorId'];			
			
      if($pdo)
{
$query = "SELECT varPassword FROM tblauthor WHERE ? = intId";
$bind = array($author);
$results = select_pdo($query,$bind);
}else{
			  $new_sql = "SELECT varPassword FROM tblauthor WHERE '$author' = intId";
			  $results = $d->fetch($new_sql);
}			  
			    if(count($results)<=0)
			    
		       {
           if($pdo)
{
$query = "Delete from tblarticles where intAuthorId = ?";
$bind = array($author);
$result = delete_pdo($query,$bind);
$strays = $result;
}else{
           $sql_del = "Delete from tblarticles where intAuthorId = '$author'";
		       $del = $d->exec($sql_del);
           $strays = $del;
           
}
		       
		       }
           
		      }

if($pdo)
{
$query = "OPTIMIZE TABLE keywords, tbladminuser, tblarticles, tblauthor, tblcategories, tblcountry, tbllinks, tblsettings, tbl_emails";
$result = select_pdo($query);
}else{
$sql_repair = "OPTIMIZE TABLE keywords, tbladminuser, tblarticles, tblauthor, tblcategories, tblcountry, tbllinks, tblsettings, tbl_emails";
$d->exec($sql_repair);		      
}

?>
	<br><br>
	<div align="center"><font color="blue"><strong>Database Cleanup</strong></font></div><br><br>
	<p align="center">All Bad Article/Author Records Have Been Repaired or Removed!</p>
  <p>&nbsp;</p>
  <?php
  $k = 0;
   if($del_emptyintID !== false && $del_emptyintID != ""){echo "<p class='pad'>Number of Articles with no ID number: ".$del_emptyintID."</p>";
   }else{echo "<p  class='pad'>Found ".$del_emptyintID." Articles with a bad ID number.</p>";}
   if($del_emptycatnum !== false && $del_emptycatnum != ""){echo "<p class='pad'>Number of Articles with no Category ID number: ".$del_emptycatnum."</p>";
   }else{echo "<p  class='pad'>Found ".$del_emptycatnum." Articles with a bad Category ID number.</p>";}
   if($del_emptyintAuth !== false && $del_emptyintAuth != ""){echo "<p class='pad'>Number of Articles with no Author ID number: ".$del_emptyintAuth."</p>";
   }else{echo "<p  class='pad'>Found ".$del_emptyintAuth." Articles with a bad Author ID number.</p>";}
   if($del_emptyart_title !== false && $del_emptyart_title != ""){echo "<p class='pad'>Number of Articles with no Title: ".$del_emptyart_title."</p>";
   }else{echo "<p  class='pad'>Found ".$del_emptyart_title." Articles with a missing title.</p>";}
   if($del_emptyart_summary !== false && $del_emptyart_summary != ""){echo "<p class='pad'>Number of Articles with no Summary: ".$del_emptyart_summary."</p>";
   }else{echo "<p  class='pad'>Found ".$del_emptyart_summary." Articles with a missing Summary.</p>";}
   if($del_emptyart_body !== false && $del_emptyart_body != ""){echo "<p class='pad'>Number of Articles with no Text Body: ".$del_emptyart_body."</p>";
   }else{echo "<p  class='pad'>Found ".$del_emptyart_body." Articles with a missing Text Body.</p>";}
   if($del_emptyauth_id !== false && $del_emptyauth_id != ""){echo "<p class='pad'>Number of Articles with no Author ID: ".$del_emptyauth_id."</p>";
   }else{echo "<p  class='pad'>Found ".$del_emptyauth_id." Articles with a missing Author ID.</p>";}
   if($del_emptyauth_email !== false && $del_emptyauth_email != ""){echo "<p class='pad'>Number of Authors with no Author Email: ".$del_emptyauth_email."</p>";
   }else{echo "<p  class='pad'>Found ".$del_emptyauth_email." Authors with a missing Author Email.</p>";}
   if($del_emptyauth_password !== false && $del_emptyauth_password != ""){echo "<p class='pad'>Number of Authors with no Author Password: ".$del_emptyauth_password."</p>";
   }else{echo "<p  class='pad'>Found ".$del_emptyauth_password." Authors with a missing Author Password.</p>";}
   if($del_emptyauth_country !== false && $del_emptyauth_country != ""){echo "<p class='pad'>Number of Authors with no Author Country (Fixed): ".$del_emptyauth_country."</p>";
   }else{echo "<p  class='pad'>Found ".$del_emptyauth_country." Authors with a missing Country ID.</p>";}
   if($strays !== false && $strays != ""){echo "<p class='pad'>Number of Articles with no Author (Removed): ".$strays."</p>";
   }else{echo "<p  class='pad'>Found 0 Articles with a missing Author.</p>";}
    echo "<p  class='pad'>Repaired All Tables.</p>";
   
   
   
   
     
  ?>
<br>

