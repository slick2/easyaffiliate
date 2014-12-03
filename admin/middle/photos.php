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
<script language="javascript" type='text/javascript' src="js/author.js"></script>
<script Language="javascript" type='text/javascript'>
function textCounter(field, countfield, maxlimit) {
if (field.value.length > maxlimit) // if too long...trim it!
field.value = field.value.substring(0, maxlimit);
// otherwise, update 'characters left' counter
else 
countfield.value = maxlimit - field.value.length;
}
// End -->
</script>
<form action="" method="post" enctype="multipart/form-data" name="adminform">

<?php
// INSERT operation of author
if(isset($_REQUEST['Submit']) && trim($_REQUEST['Submit']) == "Submit")
{
	$varEmail = safeEscapeString($_REQUEST['mail']);
	$varPassword = safeEscapeString($_REQUEST['password']);
	$varFirstName = safeEscapeString($_REQUEST['fname']);
	$varlastName = safeEscapeString($_REQUEST['lname']);
	$varAddress1 = safeEscapeString($_REQUEST['add1']);
	$varAddress2 = safeEscapeString($_REQUEST['add2']);
	$varZip = $_REQUEST['zip'];
	$varCity = safeEscapeString($_REQUEST['city']);
	$varState = safeEscapeString($_REQUEST['state']);
	$intCountry = $_REQUEST['country'];
	$varPhone = $_REQUEST['phone'];
	$varFax = $_REQUEST['fax'];
	if(isset($_REQUEST['terms']) && trim($_REQUEST['terms'])==1)
	{
		$intIsTerms = 1; 
	}else{
		$intIsTerms = 0;
	}

	$sql = "INSERT INTO `tblauthor` ( `varEmail` , `varPassword` , `varFirstName` , `varlastName` , 
										`varAddress1` , `varAddress2` , `varZip` , `varCity` , 
										`varState` , `intCountry` , `varPhone` , `varFax` , 
										`intIsTerms` , `intStatus` , `dtRegisteredDate` ) 
							VALUES ('$varEmail', '$varPassword', '$varFirstName', '$varlastName', 
									'$varAddress1', '$varAddress2', '$varZip', '$varCity', 
									'$varState', '$intCountry', '$varPhone', '$varFax', 
									'$intIsTerms', '0', NOW())";
	$result = $obj_db->sql_query($sql);
		
	header("location:index.php?filename=author&pageno=".$pageno);
	die();
}
// End of INSERT operation


// BAN operation of author
if(isset($_REQUEST['a']) && trim($_REQUEST['a'])==Yes)
{
	if(isset($_REQUEST['authorid']) && trim($_REQUEST['authorid'] != ""))
	{	
		$authorId =  sanitize_paranoid_string($_REQUEST['authorid']);
		$sql_ban = "UPDATE tblauthor SET txtBAN = 'Yes' where intId ='$authorId'";
		
		$del = $obj_db->sql_query($sql_ban);
		header("location:index.php?filename=photots&pageno=".$pageno);
		die();
	}
}

if(isset($_REQUEST['a']) && trim($_REQUEST['a'])==No)
{
	if(isset($_REQUEST['authorid']) && trim($_REQUEST['authorid'] != ""))
	{	
		$authorId =  sanitize_paranoid_string($_REQUEST['authorid']);
		$sql_ban = "UPDATE tblauthor SET txtBAN = 'No' where intId ='$authorId'";
		
		$del = $obj_db->sql_query($sql_ban);
		header("location:index.php?filename=photos&pageno=".$pageno);
		die();
	}
}
// End of BAN operation

// DELETE operation of author
if(isset($_REQUEST['a']) && trim($_REQUEST['a'])==3)
{
	if(isset($_REQUEST['authorid']) && trim($_REQUEST['authorid'] != ""))
	{	
		$authorId =  sanitize_paranoid_string($_REQUEST['authorid']);
		$sql_del = "Delete from tblauthor where intId ='$authorId'";
		$del = $obj_db->sql_query($sql_del);
		header("location:index.php?filename=photos&pageno=".$pageno);
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
		$sql = "select * from tblauthor where intId ='$authorId'";
		$result = $obj_db->select($sql);
		
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
				$Email = stripString($row['varEmail']);
				$Password = stripString($row['varPassword']);
				$FirstName = stripString($row['varFirstName']);
				$lastName = stripString($row['varlastName']);
				$Address1 = stripString($row['varAddress1']);
				$Address2 = stripString($row['varAddress2']);
				$Zip = stripString($row['varZip']);
				$City = stripString($row['varCity']);
				$State = stripString($row['varState']);
				$Country = stripString($row['intCountry']);
				$Phone = stripString($row['varPhone']);
				$Fax = stripString($row['varFax']);
				$bio = stripString($row['varBio']);
				$web = stripString($row['website']);
				$action=2;
			}
		}
		
		// Update operation
		if(isset($_REQUEST['Submit']) && trim($_REQUEST['Submit']) == "Update")
		{
			$varEmail = safeEscapeString($_REQUEST['mail']);
			$varPassword = $_REQUEST['password'];
      $gen_salt = generate_salt();
	    $varPassword = shadow($gen_salt.$varPassword);
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
			$bio = safeEscapeString($_REQUEST['message']);
			$web = safeEscapeString($_REQUEST['web']);
			if(isset($_REQUEST['terms']) && trim($_REQUEST['terms'])==1)
			{
				$intIsTerms = 1; 
			}else{
				$intIsTerms = 0;
			}
			$sql_upd ="UPDATE tblauthor SET  varEmail = '$varEmail', varPassword = '$varPassword', varFirstName = '$varFirstName',
						varlastName = '$varlastName', varAddress1 = '$varAddress1', varAddress2 = '$varAddress2', varZip = '$varZip',
						varCity = '$varCity', varState = '$varState', intCountry = '$intCountry', varPhone = '$varPhone',
						varFax = '$varFax', varBio = '$bio', website = '$web', salt = '$gen_salt' WHERE intId ='$authorId'";
			$result = $obj_db->sql_query($sql_upd);
			$action=1;
			$_SESSION['msg'] = "<center>Author Bio Has Been Updated!</center>";
			header("location:index.php?filename=thankyou");
			die();
		}
		//  End of updation
	}
}
// End Of UODATE operation
?>

<?php
// change status Approve or Not Approve
if(isset($_REQUEST['s']) && trim($_REQUEST['s'])==0)
{
	if(isset($_REQUEST['authorid']) && trim($_REQUEST['authorid']!=""))
	{
		$id=sanitize_paranoid_string($_REQUEST['authorid']);
		$update = $obj_db->sql_query("update tblauthor set intStatus = 1 where intId=$id");
	}
}
if(isset($_REQUEST['s']) && trim($_REQUEST['s'])==1)
{
	if(isset($_REQUEST['authorid']) && trim($_REQUEST['authorid']!=""))
	{
		$id=sanitize_paranoid_string($_REQUEST['authorid']);
		$update = $obj_db->sql_query("update tblauthor set intStatus = 0 where intId=$id");
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
<table  border="0" align="center" cellpadding="1" cellspacing="1" width="625">
  <tr>
    <td class="line_top" colspan="1"><div align="center">Author</div></td>
    <td class="line_top" colspan="1"><div align="right"><a class="line_top" href="javascript:history.back()">Back</a></div></td>
  </tr>
  <tr>
    <td><table width="625"  border="0" cellspacing="1" cellpadding="1" class="greyborder">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
	  
	  
      <tr>
        <td width="20%"> Email  :</td>
        <td width="80%"><input name="mail" type="text" id="mail" value="<?=$Email;?>" size="30"></td>
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
		$result = $obj_db->select("SELECT * FROM `tblcountry`");
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
      <tr align="left">
                          <td>Bio :</td>
                          <td><textarea name=message wrap=physical cols=28 rows=4 onKeyDown="textCounter(this.form.message,this.form.remLen,200);" onKeyUp="textCounter(this.form.message,this.form.remLen,200);"><?php echo $bio ?></textarea>
<br>
<input readonly type=text name=remLen size=3 maxlength=3 value="200"> characters left</td>
                        </tr>
                         <tr align="left">
                          <td>Website :</td>
                          <td><input name="web" type="text" id="web" size="30" value="<?php echo $web ?>"></td>
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
<br>
<table border="0" align="center" cellpadding="1" cellspacing="0" width="98%">
	  <tr>
		<td height="20">
		<table border="0" align="center" cellpadding="1" cellspacing="0" width="98%">
			<tr align="center" class="line_top">
				<td width="75%">Author's Photos</td>
				<td width="100%" align="right">&nbsp;</td>
			</tr>
		</table>
		</td>
	  </tr>

		<td >
		<table  border="0" align="center" cellpadding="2" cellspacing="2" class="greyborder" width="98%">
		  <tr class='table_header_menu'>
			<td>Email</td>
			<td>Name</td>
			<td>Status</td>
			<td>Photo</td>
			<td>Detail</td>
			<td><div align="center">Edit</div></td>
			<td><div align="center">Delete</div></td>
			<td><div align="center">Banned?</div></td>
		  </tr>
		  
		  
	<?
		/**************************************
		PAGING CODE START
		**************************************/
		//$rowperpage=5;
		$tablename="tblauthor,tblcountry";
		$per_page_keywords = "tblauthor.intCountry = tblcountry.intId AND intStatus = 1 AND authPhoto > ''";
		$per_page_sorts="";
		include("system/paging.inc.php");
		

		/**************************************
		PAGING CODE ENDING
		**************************************/


	$sql_select = "select tblcountry.*, tblauthor.* from tblcountry, tblauthor 
					WHERE ".$per_page_keywords." ORDER BY dtRegisteredDate DESC
					Limit ".($page_no*$row_per_page).",".$row_per_page;
	$sql = $obj_db->select($sql_select);
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
				
				
				<?php 
				if($row['intStatus']==0)
				{
					$intId = $row['intId'];
					echo "<td><a class='link' href='index.php?filename=photos&pageno=".$pageno."&s=0&authorid=$intId'>Unapproved</a></td>";		
				}
				if($row['intStatus']==1)
				{
					$intId = $row['intId'];
					echo "<td><a class='link' href='index.php?filename=photos&pageno=".$pageno."&s=1&authorid=$intId'>APPROVED</a></td>";
				}
				?>
        <td align="left"><?php
        $photo = $row['authPhoto'];
        
         echo "<img src='../author/".$photo."' width='40' height'40'>";
          
          
        ?>
        </td>
        
				<td><a class="link" href="index.php?filename=author_detail&a=4&authorid=<?php echo $row['intId']; ?>">Detail</a></td>
				<td align="center"><a class="link" href="index.php?filename=<?php  echo $_REQUEST['filename']; ?>&script=editauthor&a=2&authorid=<?php echo $row['intId']; ?>"> <img src="images/edit.png" alt="Edit" border="0"> </a></td>
				<td align="center"><a class="link" href="index.php?filename=<?php  echo $_REQUEST['filename']; ?>&pageno=<?php echo $pageno ?>&a=3&authorid=<?php echo $row['intId']; ?>" onClick="return confirm('Are you sure you wish to delete this record ?');"> <img src="images/del.png" alt="Delete" border="0"> </a></td>
	      
        <?php 
				if($row['txtBAN']=='No')
				{
					$intId = $row['intId'];
					echo "<td align='center'><a class='link' href='index.php?filename=photos&pageno=".$pageno."&a=Yes&authorid=$intId' onClick='return confirm('Are you sure you wish to ban this author?');'>No</a></td>";		
				}
				if($row['txtBAN']=='Yes')
				{
					$intId = $row['intId'];
					echo "<td align='center'><a class='link' href='index.php?filename=photos&pageno=".$pageno."&a=No&authorid=$intId' onClick='return confirm('Are you sure you wish to unban this author?');'>Yes</a></td>";
				}
				?>
				
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
   
   
  </table><br></td>
  </tr>
  <tr>
   <td algn="center" style="font-size: 12px;">To Remove Photos, Please Click On The "Detail" Link Next To Author's Photo.</td>
   </tr>
</table>	

</form>
<?
}
?>
