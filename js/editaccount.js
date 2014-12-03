function editaccount()
{
	var condition=true;
	if(document.form11.fname.value.length==0)
	{
		alert("Please enter first name.");
		if(condition==true)
		{
			document.form11.fname.focus();
		}
		condition=false;
		return false;
	}
	if(document.form11.lname.value.length==0)
	{
		alert("Please enter last name.");
		if(condition==true)
		{
			document.form11.lname.focus();
		}
		condition=false;
		return false;
	}
	if(document.form11.add1.value.length==0)
	{
		alert("Please enter address line-1.");
		if(condition==true)
		{
			document.form11.add1.focus();
		}
		condition=false;
		return false;
	}
	if(document.form11.add2.value.length==0)
	{
		alert("Please enter address line-2.");
		if(condition==true)
		{
			document.form11.add2.focus();
		}
		condition=false;
		return false;
	}
	if(document.form11.zip.value.length==0)
	{
		alert("Please enter zip code.");
		if(condition==true)
		{
			document.form11.zip.focus();
		}
		condition=false;
		return false;
	}
	if(document.form11.city.value.length==0)
	{
		alert("Please enter city.");
		if(condition==true)
		{
			document.form11.city.focus();
		}
		condition=false;
		return false;
	}
	if(document.form11.state.value.length==0)
	{
		alert("Please enter state.");
		if(condition==true)
		{
			document.form11.state.focus();
		}
		condition=false;
		return false;
	}
	if(document.form11.country.value.length==0)
	{
		alert("Please select country.");
		if(condition==true)
		{
			document.form11.country.focus();
		}
		condition=false;
		return false;
	}
	if(document.form11.phone.value.length==0)
	{
		alert("Please enter phone no.");
		if(condition==true)
		{
			document.form11.phone.focus();
		}
		condition=false;
		return false;
	}
	if(document.form11.fax.value.length==0)
	{
		alert("Please enter fax no.");
		if(condition==true)
		{
			document.form11.fax.focus();
		}
		condition=false;
		return false;
	}
}
