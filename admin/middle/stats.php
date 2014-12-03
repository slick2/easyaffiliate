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
if($pdo)
{
$query = "SELECT intID FROM tblarticles";
$connection = select_pdo($query,"","art_count.af",3600);
}else{
$connection = $d->fetch("SELECT intID FROM tblarticles","daily","art_count.af");
$counter = count($connection);
}

$this_date = date("Y-m-d");
$yesterday = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-2,date("Y")));

if($pdo)
{
$query = "SELECT intID FROM tblarticles WHERE ttSubmitDate > ? AND word_count > ?";
$bind = array($this_date,0);
$today_art = select_pdo($query,$bind,"art_today.af",3600);
}else{
$today_art = $d->fetch("SELECT intID FROM tblarticles WHERE ttSubmitDate > '$this_date' AND word_count > '0'","daily","art_today.af");
}
$art_today = count($today_art);
 
 if ($art_today <= 0){
  $art_today = "0";
 }
 
if($pdo)
{
$query = "SELECT intID FROM tblarticles WHERE ttSubmitDate > ? AND word_count = ?";
$bind = array($this_date,0);
$am_art = select_pdo($query,$bind,"am_today.af",3600);
}else{
 $am_art = $d->fetch("SELECT intID FROM tblarticles WHERE ttSubmitDate > '$this_date' AND word_count = 0","daily","am_today.af");
}
 $am_today = count($am_art);
 
 if ($am_today <= 0){
  $am_today = 0;
 }

if($pdo)
{
$query = "SELECT intID FROM tblauthor WHERE dtRegisteredDate = ?";
$bind = array($this_date);
$today_auth = select_pdo($query,$bind,"today_auth.af",3600);
}else{ 
 $today_auth = $d->fetch("SELECT intID FROM tblauthor WHERE dtRegisteredDate = '$this_date'","daily","today_auth.af");
 }
$auth_today = count($today_auth);
 
 if ($auth_today <= 0){
  $auth_today = 0;
 } 

 if($pdo)
{
$query = "SELECT intID FROM tblauthor WHERE dtRegisteredDate = ?";
$bind = array($yesterday);
$yesterday_auth = select_pdo($query,$bind,"yesterday_auth.af",3600);
}else{
$yesterday_auth = $d->fetch("SELECT intID FROM tblauthor WHERE dtRegisteredDate = '$yesterday'","daily","yesterday_auth.af");
}
$auth_yesterday = count($yesterday_auth);
 
 if ($auth_yesterday <= 0){
  $auth_yesterday = 0;
 }

if($pdo)
{
$query = "SELECT intID FROM tblauthor WHERE authPhoto IS NOT NULL";
//$bind = array("NULL");
$pic_auth = select_pdo($query,"","auth_pic.af",3600);
}else{ 
 $pic_auth = mysql_query("SELECT intID FROM tblauthor WHERE authPhoto > ''","daily","auth_pic.af");
 }
$auth_pic = count($pic_auth);
 
 if ($auth_pic <= 0){
  $auth_pic = 0;
 }

if($pdo)
{
$query = "SELECT intID FROM tblauthor WHERE txtBAN = ?";
$bind = array("Yes");
$ban_auth = select_pdo($query,$bind,"auth_ban.af",3600);
}else{   
 $ban_auth = $d->fetch("SELECT intID FROM tblauthor WHERE txtBAN = 'Yes'","daily","auth_ban.af");
 }
$auth_ban = count($ban_auth);
 
 if ($auth_ban <= 0){
  $auth_ban = 0;
 }
 
 if($pdo)
{
$query = "SELECT intID FROM tblarticles WHERE intStatus = ?";
$bind = array(1);
$art_total = select_pdo($query,$bind,"total_app.af",3600);
}else{
 $art_total = $d->fetch("SELECT intID FROM tblarticles WHERE intStatus = 1","daily","total_app.af");
 }
$total_approved = count($art_total);
 
 if ($total_approved <=0){
  $total_approved = 0;
 }

if($pdo)
{
$query = "SELECT intID FROM tblarticles WHERE intStatus = ?";
$bind = array(0);
$art_totals = select_pdo($query,$bind,"total_unapp.af",3600);
}else{ 
 $art_totals = $d->fetch("SELECT intID FROM tblarticles WHERE intStatus = '0'","daily","total_unapp.af");
 }
$total_unapproved = count($art_totals);
 
 if ($total_unapproved <=0){
  $total_unapproved = 0;
 }

if($pdo)
{
$query = "SELECT intID FROM tblauthor WHERE intStatus = ?";
$bind = array(1);
$auth_total = select_pdo($query,$bind,"auth_app.af",3600);
}else{ 
 $auth_total = $d->fetch("SELECT intID FROM tblauthor WHERE intStatus = 1","daily","auth_app.af");
 }
$auth_approved = count($auth_total);
 
 if ($auth_approved <=0){
  $auth_approved = 0;
 }
 
 if($pdo)
{
$query = "SELECT intID FROM tblauthor WHERE intStatus = ?";
$bind = array(0);
$auth_unapproved = select_pdo($query,$bind,"auth_unapp.af",3600);
}else{
 $auth_totals = $d->fetch("SELECT intID FROM tblauthor WHERE intStatus = '0'","daily","auth_unapp.af");
 }
$auth_unapproved = count($auth_totals);
 
 if ($auth_unapproved <=0){
  $auth_unapproved = 0;
 }
 
//  if($pdo)
// {
// $query = "SELECT * FROM tblsettings";
// $bind = array();
// $result = select_pdo($query,$bind);
// }else{
//   $sql =  "SELECT count(intId), intCountry FROM tblauthor GROUP BY intCountry";
//   $country_list = $d->fetch($sql);
//    
//   }
//   
//   $tots = mysql_fetch_array($country_list);
//   
//   $sql_2 = "SELECT * FROM tblcountry WHERE ".$tots['intCountry']." = intId"; 
//   $country_count = mysql_query($sql_2);
//   

if($pdo)
{
$query = "SELECT SUM(intHit) FROM tblarticles";
$roww = select_pdo($query,"","lets_get.af",3600);
}else{
$roww = $d->fetch("SELECT SUM(intHit) FROM tblarticles","daily","lets_get.af");
}
$downloads = $roww[0]['SUM(intHit)'];
if($pdo)
{
$query = "SELECT intID FROM tblauthor";
$connection2 = select_pdo($query,"","conn2.af",3600);
}else{ 
$connection2 = $d->fetch("SELECT intID FROM tblauthor","daily","conn2.af");
}
$counter2 = count($connection2);

  
if(isset($_SESSION['msg']) && $_SESSION['msg'] > '')
{
$msg = $_SESSION['msg'];
unset($_SESSION['msg']);
?>
<br>&nbsp;&nbsp;&nbsp;<p class='pad'><font color="red" size="3"><?php echo $msg ?></font></p>
<?php
}
?>
<br>
<center><?php echo $title ?> Stats</center><br><br>
<div align="center">Articles</div>
<table border="0" align="center" class="stat" cellpadding="6" cellspacing="6">
  <tr>
    <td align="left" valign="middle" style="border-right: 1px solid; border-bottom: 1px solid;">
	Total Articles: 
      <font color="red" size="1"><?php echo $counter ?></font></td>
      <td align="left" valign="middle" style="border-right: 1px solid; border-bottom: 1px solid;">Live Author Submissions Today: 
      <font color="red" size="1"> <?php echo $art_today ?></font></td>
      <td align="left" valign="middle" style="border-right: 1px solid; border-bottom: 1px solid;">Article Marketer Submissions Today: 
      <font color="red" size="1"> <?php echo $am_today ?></font></td>
      <td align="left" valign="middle" style="border-right: 1px solid; border-bottom: 1px solid;">Total Article Views: 
      <font color="red" size="1"> <?php echo $downloads ?></font></td>
    </tr><tr>
      <td align="left" valign="middle" style="border-right: 1px solid; border-bottom: 1px solid;">
	Total Approved: 
      <font color="red" size="1"><?php echo $total_approved ?></font></td>
      <td align="left" valign="middle" style="border-right: 1px solid; border-bottom: 1px solid;">
	Total Unapproved: 
      <font color="red" size="1"><?php echo $total_unapproved ?>&nbsp;&nbsp;(<a href="index.php?filename=articles" target="_blank">View</a>)</font></td>
      
      
  </tr>
 </table><br><br>
 
 <div align="center">Authors</div>
 <table border="0" align="center" class="stat" cellpadding="6" cellspacing="6">
   <tr>
    <td align="center" valign="middle" style="border-right: 1px solid; border-bottom: 1px solid;">
     Total:  
     <font color="red" size="1"> <?php echo $counter2 ?></font></td>
     <td align="left" valign="middle" style="border-right: 1px solid; border-bottom: 1px solid;">
      Signups Today:
      <font color="red" size="1"><?php echo $auth_today ?></font></td>
      <td align="left" valign="middle" style="border-right: 1px solid; border-bottom: 1px solid;">
      Yesterdays Signups:
      <font color="red" size="1"><?php echo $auth_yesterday ?></font></td> 
      <td align="left" valign="middle" style="border-right: 1px solid; border-bottom: 1px solid;">
      Author Photos:
      <font color="red" size="1"><?php echo $auth_pic ?>&nbsp;&nbsp;&nbsp;(<a href="index.php?filename=photos" target="_blank">View</a>)</font></td> 
       <td align="left" valign="middle" style="border-right: 1px solid; border-bottom: 1px solid;">
      Banned Authors:
      <font color="red" size="1"><?php echo $auth_ban ?>&nbsp;&nbsp;&nbsp;(<a href="index.php?filename=bannedauthor" target="_blank">View</a>)</font></td>
      </tr><tr>
      <td align="left" valign="middle" style="border-right: 1px solid; border-bottom: 1px solid;">
	Approved: 
      <font color="red" size="1"><?php echo $auth_approved ?></font></td>
      <td align="left" valign="middle" style="border-right: 1px solid; border-bottom: 1px solid;">
	Unapproved: 
      <font color="red" size="1"><?php echo $auth_unapproved ?>&nbsp;&nbsp;(<a href="index.php?filename=authorunapproved" target="_blank">View</a>)</font></td>
   </tr>
   <tr>
   <td colspan="4"><font color="red" size="2">Remove all non-contributing authors?</font> (authors that haven't submitted an article)&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php?filename=empty" onClick="return confirm('Are you sure you wish to delete all non-contributing authors ?');"> <img src="images/del.png" alt="Delete" border="0"> </a></td>
   </tr>
 </table><br>
 
 
