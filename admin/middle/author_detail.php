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
$error = 0;
if (isset($_REQUEST['delete']))
   {
   $authorID = sanitize_paranoid_string($_REQUEST['id']);
   
   if(strlen($authorID) > 5)
   {
    $error = 1;
   }
   
If($error = 0)
{
if($pdo)
{
$query = "UPDATE tblauthor SET authPhoto = ? WHERE intId = ?";

$bind = array("NULL",$authorID);
$result = update_pdo($query,$bind);
}else{
  $d->exec("UPDATE tblauthor SET authPhoto = '' WHERE intId = '$authorID'");
}
}		
  if($error = 0)
{
    $_SESSION['msg']=  "<br><br>Author's picture has been deleted.<br><br> Thank you";
	  header("location:index.php?filename=thankyou"); 
}else{
$_SESSION['msg']=  "<br><br>Author's picture has NOT been deleted due to a bad author ID";
	  header("location:index.php?filename=thankyou"); 

}   
   }
                  
if(isset($_REQUEST['a']) && trim($_REQUEST['a'])==4)
{
	if(isset($_REQUEST['authorid']) && trim($_REQUEST['authorid'] != ""))
	{	
		$authorid =  sanitize_paranoid_string($_REQUEST['authorid']);

    
if($pdo)
{
$query = "select tblcountry.*, tblauthor.* from tblcountry, tblauthor 
						WHERE tblauthor.intCountry = tblcountry.intId AND tblauthor.intId= ?";
$bind = array($authorid);
$sql = select_pdo($query,$bind);
}else{
		$sql_select = "select tblcountry.*, tblauthor.* from tblcountry, tblauthor 
						WHERE tblauthor.intCountry = tblcountry.intId AND tblauthor.intId='$authorid'";
		$sql = $d->fetch($sql_select);
}
		if(count($sql)>0)
		{
			foreach($sql as $row)
			{ 

	?>
	<table border="0" cellspacing="3" cellpadding="2" align="center">
      <tr class="line_top">
        <td width="95%"><div align="center">Author Detail</div></td>
        <td width="5%"><div align="right"><a class="line_top" href="javascript:history.back()">Back</a></div></td>
      </tr>
    </table>
<table  border="0" align="center" cellpadding="2" cellspacing="3" class="greyborder">
  <tr>
   <td>Name :</td>
  <td><?php echo stripString($row['varFirstName']). " " .stripString($row['varlastName']); ?></td>
  </tr>
  <tr>
   <td>Email :</td>
  	<td><?php echo stripString($row['varEmail']); ?></td>
  </tr>
  <tr>
   <td>Password :</td>
  	<td><?php echo stripString($row['varPassword']); ?></td>
  </tr>
  <tr>
   <td>Address1 :</td>
  <td><?php echo stripString($row['varAddress1']); ?></td>
  </tr>
  <tr>
    <td>Address2 :</td>
 <td><?php echo stripString($row['varAddress2']); ?></td>
  </tr>
  <tr>
    <td>Zip :</td>
   <td><?php echo stripString($row['varZip']); ?></td>
  </tr>
  <tr>
    <td>City :</td>
 <td><?php echo stripString($row['varCity']); ?></td>
  </tr>
  <tr>
   <td>State :</td> 
 <td><?php echo stripString($row['varState']); ?></td>
  </tr>  
    <tr>
    <td>Country :</td>
  <td><?php echo stripString($row['varCountry']); ?></td>
  </tr>
  <tr>
    <td>Phone :</td>
  <td><?php echo stripString($row['varPhone']); ?></td>
  </tr>
  <tr>
   <td>Fax :</td> 
 <td><?php echo stripString($row['varFax']); ?></td>
  </tr>  
  <tr>
    <td>Photo</td>
    <td><?php 
    $picPath = "author/".$row['authPhoto'];
    if($row['authPhoto'] > ""){
    echo $picPath; 
    }else{
    echo "None";
    }
    ?>
    </td>
  </tr>
  <tr>
  <td>Bio</td>
   <td><?php echo stripString($row['varBio']) ?></td>
  </tr>
  <tr>
  <td colspan="2"><a href="index.php?filename=photos&script=editauthor&a=2&authorid=<?php echo $authorid ?>"><font color='blue'>Edit Bio</font></a></td>
  
  </tr>
  <tr>
  <td><?php
  if($row['authPhoto'] > ""){
  echo "<a href='index.php?filename=author_detail&delete=Y&id=".$authorid."'><font color='blue'>Delete Photo</font></a>";
  }
     ?>  
  
  </td>
  <td><?php
  if($row['authPhoto'] > ""){
        echo "<center><img src=../author/".$row['authPhoto']." border='1'></center>"; 
        }
      ?>
        </td>
  </tr>
</table>
	  	<?php 
	  	 }
	 	 	}
		}
	}
	
?>
