function confirmsubmit()
{
	var condition=true;
	if(document.adminform.author.value.length==0)
	{
		alert("Please select author name.");
		if(condition==true)
		{
			document.adminform.author.focus();
		}
		condition=false;
		return false;
	}
	if(document.adminform.category.value.length==0)
	{
		alert("Please select category name.");
		if(condition==true)
		{
			document.adminform.category.focus();
		}
		condition=false;
		return false;
	}
	if(document.adminform.title.value.length==0)
	{
		alert("Please enter title.");
		if(condition==true)
		{
			document.adminform.title.focus();
		}
		condition=false;
		return false;
	}
	if(document.adminform.summery.value.length==0)
	{
		alert("Please enter summery.");
		if(condition==true)
		{
			document.adminform.summery.focus();
		}
		condition=false;
		return false;
	}
	if(document.adminform.keywords.value.length==0)
	{
		alert("Please enter keywords.");
		if(condition==true)
		{
			document.adminform.keywords.focus();
		}
		condition=false;
		return false;
	}
	if(document.adminform.textarea.value.length==0)
	{
		alert("Please enter article description.");
		if(condition==true)
		{
			document.adminform.textarea.focus();
		}
		condition=false;
		return false;
	}
	if(document.adminform.resources.value.length==0)
	{
		alert("Please enter resources.");
		if(condition==true)
		{
			document.adminform.resources.focus();
		}
		condition=false;
		return false;
	}
}
