function confirmsubmit()
{
	var condition=true;
	if(document.form.firstname.value == "")
	{
		alert("Please enter First name.");
		if(condition==true)
		{
			document.form.firstname.focus();
		}
		condition=false;
		return false;
	}
	if(document.form.middlename.value == "")
	{
		alert("Please enter Middle name.");
		if(condition==true)
		{
			document.form.middlename.focus();
		}
		condition=false;
		return false;
	}
	if(document.form.lastname.value == "")
	{
		alert("Please enter Last name.");
		if(condition==true)
		{
			document.form.lastname.focus();
		}
		condition=false;
		return false;
	}
	if(document.form.email.value == "")
	{
		alert("Please enter email.");
		if(condition==true)
		{
			document.form.email.focus();
		}
		condition=false;
		return false;
	}
	if(document.form.phone.value == "")
	{
		alert("Please enter Phone no.");
		if(condition==true)
		{
			document.form.phone.focus();
		}
		condition=false;
		return false;
	}
	if(document.form.comments.value == "")
	{
		alert("Please enter Comments.");
		if(condition==true)
		{
			document.form.comments.focus();
		}
		condition=false;
		return false;
	}
}
