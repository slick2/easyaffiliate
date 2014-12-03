<style type="text/css">
<!--
A.pagingstyleonly:link {
	MARGIN: auto;
	COLOR: #000066;
	TEXT-DECORATION: none;
	font-weight: bold;
}
A.pagingstyleonly:visited {
	MARGIN: auto;
	COLOR: #003366;
	TEXT-DECORATION: none;
	font-weight: bold;
}
A.pagingstyleonly:hover {
	MARGIN: auto; COLOR: #333300; TEXT-DECORATION: none
}
A.pagingstyleonly:active {
	MARGIN: auto; COLOR: #660000; TEXT-DECORATION: none
}
.simplepagedisplay {
	MARGIN: auto;
	COLOR: #CC3300;
	TEXT-DECORATION: none;
	font-weight: bold;
	font-size: 10px;
}

.simplearrrowdisplay {
	MARGIN: auto;
	COLOR: #000000;
	font-weight: bold;
	font-size: 11px;
	font-weight: bold;
}

A.arrowstyleonly:link {
	MARGIN: auto;
	COLOR: #660000;
	TEXT-DECORATION: none;
	font-weight: bold;
	font-size: 11px;
}
A.arrowstyleonly:visited {
	MARGIN: auto;
	COLOR: #660000;
	TEXT-DECORATION: none;
	font-weight: bold;
	font-size: 11px;
}
A.arrowstyleonly:hover {
	MARGIN: auto;
	COLOR: #000066;
	TEXT-DECORATION: none;
	font-size: 11px;
	font-weight: bold;
}
A.arrowstyleonly:active {
	MARGIN: auto;
	COLOR: #333300;
	TEXT-DECORATION: none;
	font-size: 11px;
	font-weight: bold;
}

.simpledisplayno{
	MARGIN: auto;
	COLOR:#000066;
	TEXT-DECORATION: none;
	font-weight: normal;
	font-size: 10px;
}

-->
</style>

<?

//function for finding the value , if request or set by user on the page
function valueofvar_reqorset($varreq,$varsetuser,$varconfigset)
{

	$val_final="";
	if(isset($_REQUEST[$varreq]) && $_REQUEST[$varreq]!="" )
	{
		$val_final=$_REQUEST[$varreq];
		return $val_final;
	}
	else if (isset($varsetuser)  && $varsetuser!="")
	{
		$val_final=$varsetuser;
		return $val_final;
	}
	else if($varconfigset != "")
	{
		
		$val_final=$varconfigset;
		return $val_final;
	}
	return $val_final;

}

$total_db_rec="";
	
$div_page_no= "";
// FOR THE ROW PER PAGE 
if(isset($rowperpage))
{
	$row_per_page=$rowperpage;
}
else
{
	$row_per_page=valueofvar_reqorset('rwpge',"",ROW_PER_PAGE);
}

//echo "11111===".$row_per_page;

// FOR THE PAGE NUMBER
if(isset($page_no))
{
	$page_no=valueofvar_reqorset('pgeno',$page_no,'0');
}
else
{
	$page_no=valueofvar_reqorset('pgeno',"",'0');
}

//echo "11111===".$page_no;


// FOR THE SETTING THE TABLE NAME
if(isset($tablename))
{
	$tablename=valueofvar_reqorset('tblname',$tablename,'');
}
else
{
	echo " Table Name is empty , please declare table name";
	die();
}

//echo "11111===".$tablename;



// for setting the limit
$start_rec= ( ($page_no) * $row_per_page );

//Request keyword as filled in the querystrings
$req_querystr="";
foreach($_REQUEST as $key => $val )
{
		if($key=="pgeno" || $key=="tblname" || $key=="rwpge" )
		{
		
		}
		else
		{ 	
			if($req_querystr=="")
			{
				$req_querystr .= $key."=".$val;
			}
			else
			{
				$req_querystr .= "&".$key."=".$val;
			}
		}
}
$req_querystr .="&tblname=".$tablename."&rwpge=".$row_per_page;

	$sql_total_row="SELECT * FROM ".$tablename." ";
	if( isset($per_page_keywords) && $per_page_keywords!="")
	{
		$sql_total_row .= " WHERE ".$per_page_keywords;	
	}
	if( isset($per_page_sorts) && $per_page_sorts!="")
	{
		$sql_total_row .= "  ORDER BY  ".$per_page_sorts;	
	}
	
  if($pdo)
{
$query = $sql_total_row;
$res_count_total = select_pdo($query);
}else{ 
	$res_count_total=$d->fetch($sql_total_row);
}
	if( count($res_count_total) > 0 )
	{
		$total_db_rec=count($res_count_total);
	
		$div_page_no= ceil($total_db_rec / $row_per_page) ;
	}
	
	
	
//function for showing the paging details on the PAGE
function pagindet_atbotttom_page($div_page_no,$page_no,$req_querystr,$total_db_rec,$row_per_page)
{

	$tempstart="";
	$tempend="";

	if($div_page_no > 0 )
	{
		if($div_page_no < $page_no )
		{
				$page_no=0;
		}
		
		if($div_page_no==1)
		{
			echo "<span class=simpledisplayno> Page No. 1  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  * Total Records (".$total_db_rec.")  </span>";
		}
		else
		{
				
				if( $div_page_no >= 10 )
				{
					$p=$div_page_no/5;
					$tempstart=floor($page_no / 10);
					$tempstart=($tempstart * 10 );
					$tempend=($tempstart + 10 );
					if($div_page_no <= $tempend)
					{
						$tempend=$div_page_no;
						$tempstart=( $div_page_no - 10) ;
					}
							
				}
				else
				{
					$tempstart=0;
					$tempend=$div_page_no;
				}
				echo "<table  width='100%' border='0' cellpadding='0' cellspacing='0'> ";
				echo "<tr>";
				if($page_no==0)
				{
					echo " <td><span class=simplearrrowdisplay> << </span></td>";
				}
				else
				{
					echo "  <td><a href=".$_SERVER['PHP_SELF']."?".$req_querystr."&pgeno=".($page_no-1)." class='arrowstyleonly' > << </a></td>";
				}
				
				for($i=$tempstart;$i<$tempend;$i++)
				{
					if($i==$page_no)
					{
						echo "<td>&nbsp;&nbsp;<span class=simplepagedisplay>".($i+1)."</span>&nbsp;&nbsp;</td>";
					}
					else
					{
						echo  "<td><a href=".$_SERVER['PHP_SELF']."?".$req_querystr."&pgeno=".($i)." class='pagingstyleonly' >&nbsp;&nbsp;".($i+1)."&nbsp;&nbsp;</a></td>";
					}
				}
				if(($page_no+1)==$div_page_no)
				{
					echo "<td> <span class=simplearrrowdisplay> >> </span></td>";
				}
				else
				{
					echo " <td> <a href=".$_SERVER['PHP_SELF']."?".$req_querystr."&pgeno=".($page_no+1)."  class='arrowstyleonly' > >> </a> </td>";
				}
				echo "</tr>";
				$enddisptemp=(($page_no*$row_per_page)+$row_per_page);
				if($total_db_rec < $enddisptemp )
				{
					$enddisptemp=$total_db_rec;
				}
				if($div_page_no >=10 )
				{
					$tmpcolspan=11;
				}
				else
				{
					$tmpcolspan= (1+$div_page_no);
				}
				echo "<tr><td align='right' colspan=".$tmpcolspan." class=simpledisplayno> * Total Records (".$total_db_rec.") &nbsp;&nbsp; Showing ( ".(($page_no*$row_per_page)+1)." - ".$enddisptemp." ) </td><td>&nbsp;</td></tr></table>";
			}
		
	}
	else
	{
		
	}
}




?>
