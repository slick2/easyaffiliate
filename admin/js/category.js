function confirmsubmit()
{
	var condition=true;
	if(document.adminform.parentId.value.length==0)
	{
		alert("Please select root of category.");
		if(condition==true)
		{
			document.adminform.parentId.focus();
		}
		condition=false;
		return false;
	}
	if(document.adminform.name.value.length==0)
	{
		alert("Please enter category name.");
		if(condition==true)
		{
			document.adminform.name.focus();
		}
		condition=false;
		return false;
	}
	if(document.adminform.desc.value.length==0)
	{
		alert("Please enter category description.");
		if(condition==true)
		{
			document.adminform.desc.focus();
		}
		condition=false;
		return false;
	}
}