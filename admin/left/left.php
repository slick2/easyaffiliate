<?php
// If member is not login or session is not set
if(!isset($_SESSION['userid'])){
?>

<table width="100%" border="0" class="style8" cellpadding="5">
<tr>
 <td align="center" class="header"><strong>Navigation</strong><hr></td>
</tr>
 <tr>
  <td> 

<a href="index.php" class="line_top">Home</a><br>
<a href="index.php?filename=adminlogin" class="line_top">Log In</a>

</td>
</tr></table>
<?php
}else{
?>

<table width="100%" border="0" class="style8" cellpadding="5">
<tr>
 <td align="center" class="header"><strong >Admin Nav</strong></td>
</tr>
 <tr>
  <td> 
<a href="index.php?filename=stats">Admin Home</a><br>
<a href="index.php?filename=adminlogout">Log out</a>
<br><br>
</td>
</tr>
<tr>
 <td align="center" class="header"><strong>Authors</strong></td>
</tr>
 <tr>
  <td> 
 

<a href="index.php?filename=author"> View Approved</a><hr>
<a href="index.php?filename=authorunapproved"> View Unapproved</a><hr>
<a href="index.php?filename=bannedauthor">Banned Authors</a><hr>
<a href="index.php?filename=search">Search Authors</a><hr>
<a href="index.php?filename=mailing">Mass Email</a><hr>
<a href="index.php?filename=onemail">Email One</a><hr>
<a href="../admin/dataexport.php">CSV Email Export</a><hr>
<a href="index.php?filename=photos">Author Photos</a><hr>
<a href="index.php?filename=deleteunapproved" onClick="return confirm('Are you sure you wish to delete all unapproved authors?');">Delete All Unapproved Authors</a><hr>
<a href="index.php?filename=approveunapproved" onClick="return confirm('Are you sure you wish to approve all unapproved authors?');">Approve All Unapproved Authors</a><hr><br>

</td>
</tr>

<tr>
 <td align="center" class="header"><strong>Articles</strong></td>
</tr>

 <tr>
  <td> 
  
<a href="index.php?filename=articles">View Unapproved Articles</a><hr>
<a href="index.php?filename=all_articles">View Approved Articles</a><hr>
<a href="index.php?filename=articles&script=addarticle">Add Article</a><hr>
<a href="index.php?filename=articlesearch">Find An Article</a><hr>
<a href="index.php?filename=app">Keyword Approve</a><hr>
<a href="index.php?filename=dupe_form">Search/Dupe Content</a><hr>
<a href="index.php?filename=wordcount">Short Article Delete</a><hr>
<a href="index.php?filename=duplicated" >Dupe deleter</a><hr>
<a href="index.php?filename=stats" >Statistics</a><hr>


</td></tr><tr><td align="center" class="header"><strong>Mass Actions</strong></td></tr>
<tr>
<td>
<a href="index.php?filename=deleteallarticles" onClick="return confirm('Are you sure you wish to delete all unapproved articles?');" ><font color="red">Mass Delete unapproved articles</font></a><hr>
<a href="index.php?filename=approveallarticles" onClick="return confirm('Are you sure you wish to approve all unapproved articles?');"><font color="red">Mass Approve Articles</font></a><hr>
<a href="index.php?filename=massmove1">Mass Move Articles</a><hr>
<a href="index.php?filename=empty" >Remove Authors, No Articles</a><hr>
<a href="index.php?filename=empty_cache" >Empty Cache Folder</a><hr>

</td>
</tr>

<tr>
 <td align="center" class="header"><strong>Settings</strong></td>
</tr>

 <tr>
  <td> 
  
<a href="index.php?filename=settings">Site Settings</a><br>
<a href="index.php?filename=adminuser">Admin User</a><br>
<a href="index.php?filename=categories">Categories</a><br>
<a href="index.php?filename=country">Countries</a><hr>

</td>
</tr>

<tr>
 <td align="center" class="header"><strong>Links</strong></td>
</tr>

 <tr>
  <td> 
  
<a href="index.php?filename=new_links">Approve Links</a><hr>

</td>
<tr>
 <td align="center" class="header"><strong>Database</strong></td>
</tr>
<tr>
  <td> 
  
<a href="index.php?filename=optimizer">Optimize DB</a><br>
<a href="index.php?filename=backup">Backup/Zip DB</a><br>
<a href="index.php?filename=backup_list">Backup Management</a><br>
<a href="index.php?filename=cleanup">Cleanup DB</a><br>
</td>
</tr>
</table>
<?php
}
?>
