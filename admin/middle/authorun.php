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
<script language="javascript" src="js/author.js"></script>

<?php
// INSERT operation of author
if(isset($_REQUEST['Submit']) && trim($_REQUEST['Submit']) == "Submit")
{
		$varEmail = convert($_REQUEST['mail']);
	$varPassword = convert($_REQUEST['password']);
	$varFirstName = convert($_REQUEST['fname']);
	$varlastName = convert($_REQUEST['lname']);
	$varAddress1 = convert($_REQUEST['add1']);
	$varAddress2 = convert($_REQUEST['add2']);
	$varZip = convert($_REQUEST['zip']);
	$varCity = convert($_REQUEST['city']);
	$varState = convert($_REQUEST['state']);
	$intCountry = convert($_REQUEST['country']);
	$varPhone = convert($_REQUEST['phone']);
	$varFax = convert($_REQUEST['fax']);
	if(isset($_REQUEST['terms']) && trim($_REQUEST['terms'])==1)
	{
		$intIsTerms = 1; 
	}else{
		$intIsTerms = 0;
	}

  if($pdo)
{
$query = "INSERT INTO tblauthor ( varEmail , varPassword , varFirstName , varlastName , 
					varAddress1 , varAddress2 , varZip , varCity , 
					varState , intCountry , varPhone , varFax , 
					intIsTerms , intStatus , dtRegisteredDate ) 
					 VALUES (?, ?, ?, ?, 
					 ?, ?, ?, ?, 
					 ?, ?, ?, ?, 
					 ?, ?, NOW())";
$bind = array($varEmail,$varPassword,$varFirstName,$varlastName,$varAddress1,$varAddress2,$varZip,$varCity,$varState,$intCountry,$varPhone,$varFax,$intIsTerms,0);
$result = insert_pdo($query,$bind);
}else{

	$varEmail = safeEscapeString($_REQUEST['mail']);
	$varPassword = safeEscapeString($_REQUEST['password']);
	$varFirstName = safeEscapeString($_REQUEST['fname']);
	$varlastName = safeEscapeString($_REQUEST['lname']);
	$varAddress1 = safeEscapeString($_REQUEST['add1']);
	$varAddress2 = safeEscapeString($_REQUEST['add2']);
	$varZip = safeEscapeString($_REQUEST['zip']);
	$varCity = safeEscapeString($_REQUEST['city']);
	$varState = safeEscapeString($_REQUEST['state']);
	$intCountry = safeEscapeString($_REQUEST['country']);
	$varPhone = safeEscapeString($_REQUEST['phone']);
	$varFax = safeEscapeString($_REQUEST['fax']);
  
	$sql = "INSERT INTO tblauthor ( varEmail , varPassword , varFirstName , varlastName , 
										varAddress1 , varAddress2 , varZip , varCity , 
										varState , intCountry , varPhone , varFax , 
										intIsTerms , intStatus , dtRegisteredDate ) 
							VALUES ('$varEmail', '$varPassword', '$varFirstName', '$varlastName', 
									'$varAddress1', '$varAddress2', '$varZip', '$varCity', 
									'$varState', '$intCountry', '$varPhone', '$varFax', 
									'$intIsTerms', '0', NOW())";
	$result = $d->exec($sql);
}		

		
	header("location:index.php?filename=authorun");
	die();
}
// End of INSERT operation



// DELETE operation of author
if(isset($_REQUEST['a']) && trim($_REQUEST['a'])==3)
{
	if(isset($_REQUEST['authorid']) && trim($_REQUEST['authorid'] != ""))
	{	
		$authorId =  sanitize_paranoid_string($_REQUEST['authorid']);
    
if($pdo)
{
$query = "Delete from tblauthor where intId =?";
$bind = array($authorId);
$result = delete_pdo($query,$bind);
}else{
		$sql_del = "Delete from tblauthor where intId ='$authorId'";
		$del = $d->exec($sql_del);
    }
    
if($pdo)
{
$query = "Delete from tblarticles where intAuthorId =?";
$bind = array($authorId);
$result = delete_pdo($query,$bind);
}else{
		$sql_delart = "Delete from tblarticles where intAuthorId ='$authorId'";
		$delart = $d->exec($sql_delart);
}
		header("location:index.php?filename=authorun");
		die();
	}
}
// End of DELETE operation




// UPDATE operation of author
$Email = "";
$Password = "";
$FirstName = "";
$lastName = "";
$Address1 = "";
$Address2 = "";
$Zip = "";
$City = "";
$State = "";
$Country = "";
$Phone = "";
$Fax = "";
$action=1;

if((isset($_REQUEST['a']) && trim($_REQUEST['a'])==2) && (!(isset($_REQUEST['Submit']) && trim($_REQUEST['Submit']) == "Submit")))
{
 	if(isset($_REQUEST['authorid']) && trim($_REQUEST['authorid'] != ""))
 	{
		$authorId =  sanitize_paranoid_string($_REQUEST['authorid']);
    
    if($pdo)
{
$query = "select * from tblauthor where intId =?";
$bind = array($authorId);
$result = select_pdo($query,$bind);
}else{
		$sql = "select * from tblauthor where intId ='$authorId'";
		$result = $d->fetch($sql);
}		
		// IF there is not records in database
		if(count($result)<=0)
		{
			echo " No Record Found!<br>";
			die();
		}
		
		// If there is records in database it will be store in a variable 
		// to identify which record is going to update.
		if($result)
		{
			foreach($result as $row)
			{
				$Email = convert($row['varEmail']);
				$Password = convert($row['varPassword']);
				$FirstName = convert($row['varFirstName']);
				$lastName = convert($row['varlastName']);
				$Address1 = convert($row['varAddress1']);
				$Address2 = convert($row['varAddress2']);
				$Zip = convert($row['varZip']);
				$City = convert($row['varCity']);
				$State = convert($row['varState']);
				$Country = convert($row['intCountry']);
				$Phone = convert($row['varPhone']);
				$Fax = convert($row['varFax']);
				$action=2;
			}
		}
		
		// Update operation
		if(isset($_REQUEST['Submit']) && trim($_REQUEST['Submit']) == "Update")
		{
		$varEmail = convert($_REQUEST['mail']);
			$varPassword = convert($_REQUEST['password']);
			$varFirstName = convert($_REQUEST['fname']);
			$varlastName = convert($_REQUEST['lname']);
			$varAddress1 = convert($_REQUEST['add1']);
			$varAddress2 = convert($_REQUEST['add2']);
			$varZip = convert($_REQUEST['zip']);
			$varCity = convert($_REQUEST['city']);
			$varState = convert($_REQUEST['state']);
			$intCountry = convert($_REQUEST['country']);
			$varPhone = convert($_REQUEST['phone']);
			$varFax = convert($_REQUEST['fax']);
      
			if(isset($_REQUEST['terms']) && trim($_REQUEST['terms'])==1)
			{
				$intIsTerms = 1; 
			}else{
				$intIsTerms = 0;
			}
      if($pdo)
{
$query = "UPDATE tblauthor SET  varEmail = ?, varPassword = ?, varFirstName = ?,
						varlastName = ?, varAddress1 = ?, varAddress2 = ?, varZip = ?,
						varCity = ?, varState = ?, intCountry = ?, varPhone = ?,
						varFax = ? WHERE intId = ?";
$bind = array($varEmail,$varPassword,$varFirstName,$varlastName,$varAddress1,$varAddress2,$varZip,$varCity,$varState,$intCountry,$varPhone,$varFax,$authorId);
$result = update_pdo($query,$bind);
}else{

	    $varEmail = safeEscapeString($_REQUEST['mail']);
			$varPassword = safeEscapeString($_REQUEST['password']);
			$varFirstName = safeEscapeString($_REQUEST['fname']);
			$varlastName = safeEscapeString($_REQUEST['lname']);
			$varAddress1 = safeEscapeString($_REQUEST['add1']);
			$varAddress2 = safeEscapeString($_REQUEST['add2']);
			$varZip = safeEscapeString($_REQUEST['zip']);
			$varCity = safeEscapeString($_REQUEST['city']);
			$varState = safeEscapeString($_REQUEST['state']);
			$intCountry = safeEscapeString($_REQUEST['country']);
			$varPhone = safeEscapeString($_REQUEST['phone']);
			$varFax = safeEscapeString($_REQUEST['fax']);
      
			$sql_upd ="UPDATE tblauthor SET  varEmail = '$varEmail', varPassword = '$varPassword', varFirstName = '$varFirstName',
						varlastName = '$varlastName', varAddress1 = '$varAddress1', varAddress2 = '$varAddress2', varZip = '$varZip',
						varCity = '$varCity', varState = '$varState', intCountry = '$intCountry', varPhone = '$varPhone',
						varFax = '$varFax' WHERE intId ='$authorId'";
			$result = $d->exec($sql_upd);
}
			$action=1;
			header("location:index.php?filename=authorun");
			die();
		}
		//  End of updation
	}
}
// End Of UODATE operation
?>

<?php
// change status Approve or Not Approve
// change status Approve or Not Approve
if(isset($_REQUEST['s']) && trim($_REQUEST['s'])==0)
{
	if(isset($_REQUEST['authorid']) && trim($_REQUEST['authorid']!=""))
	{
		$id=sanitize_paranoid_string($_REQUEST['authorid']);
    if($pdo)
{
$query = "update tblauthor set intStatus = 1 where intId= ?";
$bind = array($id);
$result = update_pdo($query,$bind);
}else{
		$update = $d->exec("update tblauthor set intStatus = 1 where intId='$id'");
}
	}
}
if(isset($_REQUEST['s']) && trim($_REQUEST['s'])==1)
{
	if(isset($_REQUEST['authorid']) && trim($_REQUEST['authorid']!=""))
	{
		$id=sanitize_paranoid_string($_REQUEST['authorid']);
if($pdo)
{
$query = "update tblauthor set intStatus = ? where intId= ?";
$bind = array(0,$id);
$result = update_pdo($query,$bind);
}else{
		$update = $d->exec("update tblauthor set intStatus = 0 where intId=$id");
}
	}
}
?>


<?php
if(isset($_REQUEST['script']))
{
	if(trim($_REQUEST['script'])=='addauthor' || trim($_REQUEST['script'])=='editauthor')
	{
	?>
	<br><br><br>
<table  border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td class="line_top" colspan="1"><div align="center">Author</div></td>
    <td class="line_top" colspan="1"><div align="right"><a class="line_top" href="index.php?filename=authorun">Back</a></div></td>
  </tr>
  <tr>
    <td><table width="100%"  border="0" cellspacing="1" cellpadding="1" class="greyborder">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
	  
	  
      <tr>
        <td width="184"> Email  :</td>
        <td width="229"><input name="mail" type="text" id="mail" value="<?=$Email;?>" size="30"></td>
      </tr>
	     <tr>
        <td> Password : </td>
        <td><input name="password" type="password" id="country17" value="<?=$Password;?>" size="30"></td>
      </tr>
      <tr>
        <td> Verify  Password : </td>
        <td><input name="password2" type="password" id="country18" value="<?=$Password;?>" size="30"></td>
      </tr>
      <tr>
        <td> First Name : </td>
        <td><input name="fname" type="text" id="country19" value="<?=$FirstName;?>" size="30"></td>
      </tr>
      <tr>
        <td> Last Name : </td>
        <td><input name="lname" type="text" id="country20" value="<?=$lastName;?>" size="30"></td>
      </tr>
      <tr>
        <td> Address Line 1 : </td>
        <td><input name="add1" type="text" id="country21" value="<?=$Address1;?>" size="30"></td>
      </tr>
      <tr>
        <td> Address Line 2 : </td>
        <td><input name="add2" type="text" id="country22" value="<?=$Address2;?>" size="30"></td>
      </tr>
      <tr>
        <td> Zip Code : </td>
        <td><input name="zip" type="text" id="country23" value="<?=$Zip;?>" size="30"></td>
      </tr>
      <tr>
        <td> City : </td>
        <td><input name="city" type="text" id="country24" value="<?=$City;?>" size="30"></td>
      </tr>
      <tr>
        <td> State : </td>
        <td><input name="state" type="text" id="country25" value="<?=$State;?>" size="30"></td>
      </tr>
      <tr>
        <td> Country : </td>
        <td><select name="country" id="country">
          <option>Select Country</option>
		  <?php 
		if($pdo)
{
$query = "SELECT * FROM tblcountry";
$result = select_pdo($query);
}else{ 
		$result = $d->fetch("SELECT * FROM tblcountry");
}
		foreach($result as $row) 
		{
		?>
		<option value="<? echo $row['intId'];?>" <? if($row['intId']==$Country){echo "selected";}else{echo "";} ?>><?php echo $row['varCountry'];?></option>
		  <?php }
		  ?>
        </select></td>
      </tr>
      <tr>
        <td> Phone Number : </td>
        <td><input name="phone" type="text" id="country26" value="<?=$Phone;?>" size="30"></td>
      </tr>
      <tr>
        <td>Fax Number : </td>
        <td><input name="fax" type="text" id="country27" value="<?=$Fax;?>" size="30"></td>
      </tr>
      <tr>
        <td> Agree to the Terms and Conditions? : </td>
        <td><input name="terms" type="checkbox" id="terms" value="1" checked></td>
      </tr>
      <tr>
        <td>&nbsp; </td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2"><div align="center">
          <input name="Submit" type="submit" value="<?php if($action==2){echo "Update";} else {echo "Submit";}?>" onClick="return confirmsubmit();">
        </div></td>
		
		
    </table></td>
      </tr>
    </table>
	
	
	<?
	}
}


else 
{
?>
<br><br><br>
<table border="0" align="center" cellpadding="1" cellspacing="0">
	  <tr>
		<td height="20">
		<table border="0" align="center" cellpadding="1" cellspacing="0">
			<tr align="center" class="line_top">
				<td width="75%">Detail of Author</td>
				<td width="100%" align="right"><a class="link" href="index.php?filename=author&script=addauthor"><font color='#53A8BF'>New Author</font></a></td>
			</tr>
		</table>
		</td>
	  </tr>

		<td >
		<table  border="0" align="center" cellpadding="2" cellspacing="2" class="greyborder">
		  <tr >
			<td>Email</td>
			<td>Name</td>
			<td>Country</td>
			<td>Phone</td>
			<td>Status</td>
			<td>Detail</td>
			<td><div align="center">Edit</div></td>
			<td><div align="center">Delete</div></td>
		  </tr>
		  
		  
	<?
		/**************************************
		PAGING CODE START
		**************************************/
		//$rowperpage=5;
		$tablename="tblauthor,tblcountry";
		$per_page_keywords = "tblauthor.intCountry = tblcountry.intId";
		$per_page_sorts="";
		include("system/paging.inc.php");
		

		/**************************************
		PAGING CODE ENDING
		**************************************/

if($pdo)
{
$limit = $page_no*$row_per_page;
$query = "select tblcountry.*, tblauthor.* from tblcountry, tblauthor 
					WHERE tblauthor.intCountry = tblcountry.intId AND intStatus = ? ORDER BY dtRegisteredDate DESC
					Limit ?,?";
$bind = array(1,$limit,$row_per_page);
$sql = select_pdo($query,$bind);
}else{

	$sql_select = "select tblcountry.*, tblauthor.* from tblcountry, tblauthor 
					WHERE ".$per_page_keywords." ORDER BY dtRegisteredDate DESC
					Limit ".($page_no*$row_per_page).",".$row_per_page;
	$sql = $d->fetch($sql_select);
}
	if($sql)
	{
	$i=0;
	 foreach($sql as $row)
	 { 
		 $i=$i+1;
		  ?>
			<tr class="<?php echo ($i%2==0)?"Hrnormal":"Hralter"; ?>" onMouseOver="this.className='Hrhover';"  onMouseOut="this.className='<?php echo ($i%2==0)?"Hrnormal":"Hralter"; ?>';">        
				<td><?php echo stripString($row['varEmail']); ?></td>
				<td><?php echo stripString($row['varFirstName']). " " .stripString($row['varlastName']); ?></td>
				<td><?php echo $row['varCountry']; ?></td>
				<td><?php echo $row['varPhone']; ?></td>
				<?php 
				if($row['intStatus']==0)
				{
					$intId = $row['intId'];
					echo "<td><a class='link' href='index.php?filename=authorun&s=0&authorid=$intId'>Unapproved</a></td>";		
				}
				if($row['intStatus']==1)
				{
					$intId = $row['intId'];
					echo "<td><a class='link' href='index.php?filename=authorun&s=1&authorid=$intId'>APPROVED</a></td>";
				}
				?>

				<td><a class="link" href="index.php?filename=author_detail&a=4&authorid=<?php echo $row['intId']; ?>">Detail</a></td>
				<td align="center"><a class="link" href="index.php?filename=<?php  echo $_REQUEST['filename']; ?>&script=editauthor&a=2&authorid=<?php echo $row['intId']; ?>"> <img src="images/edit.png" alt="Edit" border="0"> </a></td>
				<td align="center"><a class="link" href="index.php?filename=<?php  echo $_REQUEST['filename']; ?>&a=3&authorid=<?php echo $row['intId']; ?>" onClick="return confirm('Are you sure you wish to delete this record ?');"> <img src="images/del.png" alt="Delete" border="0"> </a></td>
	        </tr>
  <?php 
	}
?>
		
		<tr >
	 <td colspan="3" ><div align="center"><?
// query line==== Limit ".($page_no*$row_per_page).",".$row_per_page;
// PAGING FUNCTION FOR PAGE NUMBER DISPLAYED
pagindet_atbotttom_page($div_page_no,$page_no,$req_querystr,$total_db_rec,$row_per_page);
?>
            </div></td>
  </tr>
  
  
		  <?
	}	
   ?>
   
   
  </table></td>
  </tr>
</table>	


<?
}
?>
