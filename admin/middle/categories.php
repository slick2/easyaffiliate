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
if($_SESSION['acctype'] == '1')
{
$_SESSION['msg'] = "<p>Sorry, but you don't have permission to view that page.";
header("location:index.php?filename=thankyou");
	die();
}

if(isset($_REQUEST['pageno']))
{
$pgno = sanitize_paranoid_string($_REQUEST['pageno']);
$page_no = $pgno;
}
?>
<script language="javascript" src="js/category.js"></script>
<form action="" method="post" enctype="multipart/form-data" name="adminform">
<?php
$msg= "";	
function Getcatiddel($ParentID,$num,$selected,$pdo,$check)
{
if(isset($_SESSION['pdo']) && $_SESSION['pdo'] == true)
{
$pdo = true;
}else{
$pdo = false;
}
	$value = $ParentID;
	$c="";
	 
    $d = new db(0);
    
  if($pdo)
{
$query = "SELECT intID FROM tblcategories WHERE intParentID = ? ORDER BY varCategory ASC";
$bind = array($ParentID);
$RsC = select_pdo($query,$bind);
}else{
	$ParentID = safeEscapeString($ParentID);		
	$query_RsC = "SELECT intID FROM tblcategories WHERE intParentID = ".$ParentID." ORDER BY varCategory ASC";
	$RsC = $d->fetch($query_RsC);
}

	$cnt = count($RsC);
		
	if($RsC)
	{
		for($i=0;$i<$cnt;$i++)
		{	
			$value=$value.",".Getcatiddel($RsC[$i]['intID'],$num+1,$selected,$pdo,$check);		
		}
	 }
	return stripslashes($value);
}


function GetChild($ParentID,$num,$selected,$pdo,$check)
{
    if(isset($_SESSION['pdo']) && $_SESSION['pdo'] == true)
     {
      $pdo = true;
     }else{
      $pdo = false;
     }
		$value = "";
		$c="";
    
    $d = new db(0);
    
    for($i=0;$i<$num;$i++)
		{
			$c=$c."&nbsp;&nbsp;-&nbsp;&nbsp;";
		}
    
if($pdo)
{
$query = "SELECT intID,varCategory FROM tblcategories WHERE intParentID = ? ORDER BY varCategory ASC";
$bind = array($ParentID);
$RsC = select_pdo($query,$bind);

}else{
	$ParentID = safeEscapeString($ParentID);		
	$query_RsC = "SELECT intID,varCategory FROM tblcategories WHERE intParentID = ".$ParentID." ORDER BY varCategory ASC";
	$RsC = $d->fetch($query_RsC);
   }
 
$cnt = count($RsC);


		
		if($RsC)
		{
			for($i=0;$i<$cnt;$i++)
			{
				if($check==1)
				{
						if($selected==$RsC[$i]['intID'])
						{
						$value=$value."<option value='".$RsC[$i]['intID']."' selected>".$c.stripString($RsC[$i]['varCategory'])."</option>";		
						}
						else
						{
						$value=$value."<option value='".$RsC[$i]['intID']."' >".$c.stripString($RsC[$i]['varCategory'])."</option>";		
						}
				}
        
				$value=$value."".GetChild($RsC[$i]['intID'],$num+1,$selected,$pdo,$check);		
			 }
		 }
	return $value;
}


// Insert operation of category
if(isset($_REQUEST['Submit']) && trim($_REQUEST['Submit'])=="Submit")
{
	$parent_id = sanitize_paranoid_string($_REQUEST['parentId']);
	$varCategory= $_REQUEST['name'];
	$textDescription= $_REQUEST['desc'];
	
if($parent_id != 0)
	{
if($pdo)
{
$query = "SELECT intHasChild FROM tblcategories WHERE intID = '$parent_id'";
$bind = array($parent_id);
$result = select_pdo($query,$bind);
}else{
    $varCategory= safeEscapeString($_REQUEST['name']);
	  $textDescription= safeEscapeString($_REQUEST['desc']);
		$sql = "SELECT intHasChild FROM tblcategories WHERE intID = '$parent_id'";
		$result = $d->fetch($sql);
}
		$has_child = $result[0]['intHasChild'];
    
if($pdo)
{
$query = "UPDATE tblcategories SET  intHasChild = ? + 1 WHERE intID = ?";
$bind = array($has_child,$parent_id);
$result = update_pdo($query,$bind);
}else{
$sql = "UPDATE tblcategories SET  intHasChild = '$has_child' + 1 WHERE intID = '$parent_id'";
$result = $d->exec($sql);
    }
	}	

if($pdo)
{
$query = "INSERT INTO tblcategories (varCategory , textDescription , intHasChild , intHasArticles , intParentID , intIsNew , ttDateCreated ) 
			   VALUES (?, ?, ?, ?, ?, ?, NOW( ) )";
$bind = array($varCategory,$textDescription,0,0,$parent_id,1);
$result = insert_pdo($query,$bind);
}else{

	$sql="INSERT INTO tblcategories (varCategory , textDescription , intHasChild , intHasArticles , intParentID , intIsNew , ttDateCreated ) 
			VALUES ('$varCategory', '$textDescription', '0', '0', '$parent_id', '1', NOW( ) )";
	$insert=$d->exec($sql);
}		
	header("location:index.php?filename=categories&pageno=".$pgno);
	die();
}

// DELETE record from database
if(isset($_REQUEST['a']) && trim($_REQUEST['a'])==3)
{
	if(isset($_REQUEST['catid']) && trim($_REQUEST['catid']!=""))
	{	
		$adsid =  sanitize_paranoid_string($_REQUEST['catid']);
		
if($pdo)
{
$query = "SELECT intParentID FROM tblcategories WHERE intID = ?";
$bind = array($adsid);
$result = select_pdo($query,$bind);
$parent_id = $result[0]['intParentID'];
}else{
$adsid = safeEscapeString($adsid); 
		$sql = "SELECT intParentID FROM tblcategories WHERE intID = '$adsid'";
		$result = $d->fetch($sql);
		$parent_id = $result[0]['intParentID'];
}
		
if($pdo)
{
$query = "SELECT intHasChild FROM tblcategories WHERE intID = ?";
$bind = array($parent_id);
$result1 = select_pdo($query,$bind);
}else{
$parent_id = safeEscapeString($parent_id);
		$sql1 = "SELECT intHasChild FROM tblcategories WHERE intID = '$parent_id'";
		$result1 = $d->fetch($sql1);
}
		
		if($result1)
		{
			$has_child = $result1[0]['intHasChild'];
			$has_child = $has_child - 1;
			if($has_child <= 0)
			{
				$has_child =0;
			}
      
if($pdo)
{
$query = "UPDATE tblcategories SET intHasChild = ? WHERE intID = ?";
$bind = array($has_child,$parent_id);
$result = update_pdo($query,$bind);
}else{
$sql2 = "UPDATE tblcategories SET intHasChild = '$has_child' WHERE intID = '$parent_id'";
			$result2 = $d->exec($sql2);
}
}

		
		$catid=Getcatiddel($adsid,0,0,$db,0);
		
		$catid=explode(",",$catid);
		for($i=0;$i<count($catid);$i++)
		{
			$adsid=$catid[$i];
      if($pdo)
{
$query = "DELETE FROM tblcategories WHERE intID = ?";
$bind = array($adsid);
$result = delete_pdo($query,$bind);
}else{
			$sql3 = "DELETE FROM tblcategories WHERE intID = '$adsid'";
			$d->exec($sql3);
}
			
      if($pdo)
{
$query = "SELECT intId FROM tblarticles WHERE intCategory = ?";
$bind = array($adsid);
$reslist = select_pdo($query,$bind);
}else{
			$sql = "SELECT intId FROM tblarticles WHERE intCategory ='$adsid'";
			$reslist=$d->fetch($sql);
}
			
			if($reslist != false)
			{
				for($k=0;$k<count($reslist);$k++)
				{
if($pdo)
{
$query = "DELETE FROM tblarticles WHERE intId = ?";
$bind = array($reslist[$k]['intId']);
$result = delete_pdo($query,$bind);
}else{
					$sql= "DELETE FROM tblarticles WHERE intId =".$reslist[$k]['intId'];
					$d->exec($sql);
}
				}
			}
			
		}		
		header("location:index.php?filename=categories&pageno=".$pgno);
		die();
	}
}


// UPDATE the record
$Parentid = "";
$Category="";
$Description="";
$action=1;
if((isset($_REQUEST['a']) && trim($_REQUEST['a'])==2) && isset($_REQUEST['catid']) && !isset($_POST['Submit']) && !isset($_POST['Update']))
{
 	if(isset($_REQUEST['catid']) && trim($_REQUEST['catid'])!="")
 	{
		$adsid = sanitize_paranoid_string($_REQUEST['catid']);
    
if($pdo)
{
$query = "select * from  tblcategories where intID = ?";
$bind = array($adsid);
$result = select_pdo($query,$bind);
}else{
		$sql = "select * from  tblcategories where intID ='".safeEscapeString($adsid)."'";
		$result = $d->fetch($sql);
}		
		if($result)
		{
			foreach($result as $row)
			{
				$Parentid = $row['intParentID'];
				$Category= stripString($row['varCategory']);
				$Description= stripString($row['textDescription']);
				
$has_child =0;

if($pdo)
{
$query = "SELECT intHasChild FROM tblcategories WHERE intID = ?";
$bind = array($Parentid);
$result = select_pdo($query,$bind);
}else{
				$sql = "SELECT intHasChild FROM tblcategories WHERE intID = '$Parentid'";
				$result = $d->fetch($sql);
}
				if($result)
				{
					$has_child = $result[0]['intHasChild'];
					$has_child = $has_child - 1;
				}
				if($has_child <= 0)
				{
					$has_child =0;
				}
if($pdo)
{
$query = "UPDATE tblcategories SET intHasChild = ? WHERE intID = ?";
$bind = array($has_child,$Parentid);
$result = update_pdo($query,$bind);
}else{
				$sql = "UPDATE tblcategories SET intHasChild = '$has_child' WHERE intID = '$Parentid'";
				$result = $d->exec($sql);
}
				
			}
      }
    $action=2;
		}
	}	
		if(isset($_REQUEST['Submit']) && trim($_REQUEST['Submit']) == "Update")
		{
			$parent_id = sanitize_paranoid_string($_REQUEST['parentId']);
			$varCategory = safeEscapeString($_REQUEST['name']);
			$textDescription =safeEscapeString($_REQUEST['desc']);
			if($parent_id <> 0)
			{
if($pdo)
{
$query = "UPDATE tblcategories SET intHasChild = intHasChild + ? WHERE intID = ?";
$bind = array($parent_id,1);
$result = update_pdo($query,$bind);
}else{
				$sql = "UPDATE tblcategories SET  intHasChild = intHasChild + 1 WHERE intID = '$parent_id'";
				$result = $d->exec($sql);
}
			}	
if($pdo)
{
$query = "UPDATE tblcategories SET varCategory = ?, textDescription = ?,
          intParentID = ?, ttDateCreated = NOW() WHERE intID = ?";
$bind = array($varCategory,$textDescription,$parent_id,$adsid);
$result = update_pdo($query,$bind);
}else{
			$sql = "UPDATE tblcategories SET varCategory = '$varCategory', textDescription = '".convert($textDescription)."',";
			$sql .= " intParentID = '$parent_id', ttDateCreated = NOW() WHERE intID = '$adsid'";
$result =	$d->exec($sql);
}
			$action=1;
			header("location:index.php?filename=categories&pageno=".$pgno);
			die();
		}
	
if(isset($_REQUEST['remove']) && $_REQUEST['remove'] == 'yes')
{
$query = "TRUNCATE TABLE tblcategories";
$result = select_pdo($query,$bind);

$query = "TRUNCATE TABLE tblarticles";
$result = select_pdo($query,$bind);

}else{
				$sql = "TRUNCATE TABLE tblcategories";
				$result = $d->fetch($sql);
        
        $sql = "TRUNCATE TABLE tblarticles";
				$result = $d->fetch($sql);
}


?>


<?php
if(isset($_REQUEST['script']))
{
	if($_REQUEST['script'] == "addcategory" || trim($_REQUEST['script']) == "editcategory")
	{
  
?>
<br>

<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td class="line_top"><div align="center">Categories</div></td>
  </tr>
  <tr>
    <td><table width="100%"  border="0" cellspacing="1" cellpadding="1" >
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
  <tr>
    <td>Parent ID : </td>
    <td><select name="parentId" id="parentId">
      <option value="0">ROOT</option>
				<?php 
						if($action == 2)
					{
						$selected = $Parentid;
            $check = 1;
					}else{
						$selected = 0;
            $check = 1;
					}	
					echo GetChild(0,0,$selected,$pdo,$check);
				?> 
		  </select></td>
  </tr>
  <tr>
    <td> Category Name : </td>
    <td><input name="name" type="text" id="name" size="55" value="<?=$Category;?>"></td>
  </tr>
  <tr>
    <td valign="top"> Description : </td>
    <td><textarea name="desc" cols="50" rows="8" id="desc"><?=$Description;?></textarea></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">
    <input type='hidden' name='pageno' value='<?php echo $pgno ?>'>
      <input name="Submit" type="submit" id="Submit" value="<? echo ($action==2) ? "Update":"Submit"; ?>" onClick="return confirmsubmit();">
    </div></td>
    </tr>
  
  </table></td>
  </tr>
</table>
<?php
	}
}
else
{
?>
<br>
<table width="500px" border="0" align="center" cellpadding="1" cellspacing="0">
	  <tr>
		<td height="20">
		<table border="0" align="center" cellpadding="1" cellspacing="0">
			<tr align="center" class="line_top">
				<td width="22%">Categories</td>
				<td width="78%" align="right"><a href="index.php?filename=categories&script=addcategory&pageno=<?php echo $pgno; ?>"><font color='#53A8BF'>Add New Category</font></a></td>
			</tr>
		</table>
		</td>
	  </tr>

		<td >
		<table  border="0" align="center" cellpadding="2" cellspacing="2" class="greyborder">
		  <tr >
			<td>Name</td>
			<td>Description</td>
			<td><div align="center">Edit</div></td>
			<td><div align="center">Delete</div></td>
		  </tr>
		  
		  
	<?
		/**************************************
		PAGING CODE START
		**************************************/
		
		//define('ROW_PER_PAGE',10);
		$row_per_page = 25;
		$tablename="tblcategories";
		$per_page_keywords= "";
		$per_page_sorts="";
		include("system/paging.inc.php");
    
		  $pgno = $page_no;
     

		/**************************************
		PAGING CODE ENDING
		**************************************/

if($pdo)
{
$limit = ($page_no*$row_per_page);
$query = "select * from tblcategories ORDER BY varCategory Limit ?,?";
$bind = array($limit,$row_per_page);
$sql = select_pdo($query,$bind);
}else{
	$sql_select = "select * from tblcategories ORDER BY varCategory Limit ".($page_no*$row_per_page).",".$row_per_page;
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
				<td><?php echo stripString($row['varCategory']); ?></td>
				<td><?php echo stripString($row['textDescription']); ?></td>
				<td align="center"><a class="link" href="index.php?filename=<?php  echo $_REQUEST['filename']; ?>&script=editcategory&a=2&catid=<?php echo $row['intID']; ?>&pageno=<?php echo $pgno; ?>" > <img src="images/edit.png" alt="Edit" border="0"> </a></td>
				<td align="center"><a class="link" href="index.php?filename=<?php  echo $_REQUEST['filename']; ?>&a=3&catid=<?php echo $row['intID']; ?>&pageno=<?php echo $pgno; ?>" onClick="return confirm('Click YES To Delete All Articles In This Category Also, NO To Stop And Move Articles First!');"> <img src="images/del.png" alt="Delete" border="0"> </a></td>
	        </tr>
  <?php 
	}
?>
	<tr	
  <td><p style='color:red;'><a href='index.php?filename=categories&remove=yes' onClick="return confirm('This will permanently delete all categories AND articles. Continue?');">Remove ALL Categories?</a></td>
  </tr>
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
</table></form>	
<?php
}
?>
