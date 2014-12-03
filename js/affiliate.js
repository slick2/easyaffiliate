function confirmsubmit()
{
	var condition=true;
	//alert(document.frmmainindex.email[0].value);
	if(document.frmmainindex.email.value.length==0)
	{
		alert("Please enter email.");
		if(condition==true)
		{
			document.frmmainindex.email.focus();
		}
		condition=false;
		return false;
	}
	else
	if(!checkMail(document.frmmainindex.email.value))
	{
		alert("Email must contain an email address.\n");
		if(condition==true)
		{
			document.frmmainindex.email.focus();
		}
		condition=false;
		return false;
	}
	
	if(document.frmmainindex.password.value.length==0)
	{
		alert("Please enter Password.");
		if(condition==true)
		{
			document.frmmainindex.password.focus();
		}
		condition=false;
		return false;
	}
	if(document.frmmainindex.conpassword.value.length==0)
	{
		alert("Please conform your password.");
		if(condition==true)
		{
			document.frmmainindex.conpassword.focus();
		}
		condition=false;
		return false;
	}
	
	if(document.frmmainindex.firstname.value.length==0)
	{
		alert("Please enter First name.");
		if(condition==true)
		{
			document.frmmainindex.firstname.focus();
		}
		condition=false;
		return false;
	}
	if(document.frmmainindex.middlename.value.length==0)
	{
		alert("Please enter Middlename.");
		if(condition==true)
		{
			document.frmmainindex.middlename.focus();
		}
		condition=false;
		return false;
	}
	if(document.frmmainindex.lastname.value.length==0)
	{
		alert("Please enter Lastname.");
		if(condition==true)
		{
			document.frmmainindex.lastname.focus();
		}
		condition=false;
		return false;
	}
	if(document.frmmainindex.address.value.length==0)
	{
		alert("Please enter Address.");
		if(condition==true)
		{
			document.frmmainindex.address.focus();
		}
		condition=false;
		return false;
	}
	if(document.frmmainindex.city.value.length==0)
	{
		alert("Please enter City.");
		if(condition==true)
		{
			document.frmmainindex.city.focus();
		}
		condition=false;
		return false;
	}
	if(document.frmmainindex.state.value.length==0)
	{
		alert("Please enter State.");
		if(condition==true)
		{
			document.frmmainindex.state.focus();
		}
		condition=false;
		return false;
	}
	if(document.frmmainindex.zipcode.value.length==0)
	{
		alert("Please enter Zipcode.");
		if(condition==true)
		{
			document.frmmainindex.zipcode.focus();
		}
		condition=false;
		return false;
	}
	if(document.frmmainindex.country.value.length==0)
	{
		alert("Please select country.");
		if(condition==true)
		{
			document.frmmainindex.country.focus();
		}
		condition=false;
		return false;
	}
	if(document.frmmainindex.phone.value.length==0)
	{
		alert("Please enter Time Phone.");
		if(condition==true)
		{
			document.frmmainindex.phone.focus();
		}
		condition=false;
		return false;
	}
	
	if(document.frmmainindex.password.value != "" && document.frmmainindex.conpassword.value != "" ) 
	{
		if(document.frmmainindex.password.value == document.frmmainindex.conpassword.value)
		{
			return true;
		}
		else
		{
			alert("Password string and confirm password string does not match.");
			return false;
		}
	}
	
}
function checkMail(email)
{
	var x = email;
	var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (filter.test(x)) 
	{
	 return true;
	}
	else 
	{
	  return false;
	}
}
