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
	
<script language="JavaScript">

function dosearch() {

var sf=document.searchform;

var submitto = sf.sengines.options[sf.sengines.selectedIndex].value + escape(sf.searchterms.value);

window.open(submitto);

return false;

}

</script>

<br>
<div align="center"><font size="3"><b>Articles Search & Dupe Content Finder</b></font></div>
<br>
<table height="50"  border="0" align="center" cellpadding="10" cellspacing="0">
  <tr>
    <td align="center" valign="middle" class="black_note">
<form action="index.php?filename=title_check" method="post">

<center>Enter the article title in the box below to check against our database.</p>

<input type="text" name="art_title" size="60" style="font-family: verdana; font-size: 11px; color: #333333; border: 1px solid #C0C0C0" value=""><br><br>

<input type="submit"></center></form><br><br>



<form action="index.php?filename=body_check" method="post">

<center>Enter the part of the article body text you'd like to check in the box below.  It is not necessary to

enter in the entire article.   A sentence or paragraph is usually sufficient.</p>

<textarea rows="5" name="art_text" cols="60" style="font-family: verdana; font-size: 11px; color: #333333; border: 1px solid #C0C0C0"></textarea><br><br>

<input type="submit"></center></form><br><br>



<center>Enter a phrase, sentence or title, then choose the search engine

to search on.<br><br><form name="searchform" onSubmit="return dosearch();">

<table border="1" cellpadding="10" cellspacing="0" bgcolor="#F2F2F2">

<tr>

<td>

Search:&nbsp;

<select name="sengines">

<option value="http://www.webcrawler.com/cgi-bin/WebQuery?searchText=" selected>WebQuery</option>

<option value="http://www.google.com/search?q=">Google</option>

<option value="http://www.altavista.com/web/results?q=">Alta Vista</option>

<option value="http://uk.ask.com/web?q=">Ask.com</option>

<option value="http://search.yahoo.com/search?p=">Yahoo!</option>

<option value="http://search.msn.com/results.aspx?q=">MSN</option>

</select>

&nbsp;&nbsp;For:&nbsp;

<input type="text" name="searchterms">

<input type="submit" name="SearchSubmit" value="Search">

</td>

</tr>

</table>

</form></center>
</td>
  </tr>
</table>

