function confirmsubmit()
{
	var condition=true;
	if(document.frmsubart.author.value.length==0)
	{
		alert("Please Select Author.");
		if(condition==true)
		{
			document.frmsubart.author.focus();
		}
		condition=false;
		return false;
	}
	
	if(document.frmsubart.cat.value.length==0)
	{
		alert("Please Select Catagory.");
		if(condition==true)
		{
			document.frmsubart.cat.focus();
		}
		condition=false;
		return false;
	}
	
	if(document.frmsubart.f_arttitle.value.length==0)
	{
		alert("Please enter Article Title.");
		if(condition==true)
		{
			document.frmsubart.f_arttitle.focus();
		}
		condition=false;
		return false;
	}
	if(document.frmsubart.f_artsummary.value.length==0)
	{
		alert("Please enter Article Summery.");
		if(condition==true)
		{
			document.frmsubart.f_artsummary.focus();
		}
		condition=false;
		return false;
	}
	if(document.frmsubart.f_artbody.value.length==0)
	{
		alert("Please enter Article Body.");
		if(condition==true)
		{
			document.frmsubart.f_artbody.focus();
		}
		condition=false;
		return false;
	}
	if(document.frmsubart.f_artres.value.length==0)
	{
		alert("Please enter Resource Box Text.");
		if(condition==true)
		{
			document.frmsubart.f_artres.focus();
		}
		condition=false;
		return false;
	}
	if(document.frmsubart.f_artkey.value.length==0)
	{
		alert("Please enter Keyword.");
		if(condition==true)
		{
			document.frmsubart.f_artkey.focus();
		}
		condition=false;
		return false;
	}
	
}
