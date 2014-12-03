function confirmsubmit()
{
	var condition=true;
	if(document.adminform.fullname.value.length==0)
	{
		alert("Please enter fullname.");
		if(condition==true)
		{
			document.adminform.fullname.focus();
		}
		condition=false;
		return false;
	}
	if(document.adminform.username.value.length==0)
	{
		alert("Please enter username.");
		if(condition==true)
		{
			document.adminform.username.focus();
		}
		condition=false;
		return false;
	}
	if(document.adminform.password.value.length==0)
	{
		alert("Please enter password.");
		if(condition==true)
		{
			document.adminform.password.focus();
		}
		condition=false;
		return false;
	}
	if(document.adminform.cpassword.value.length==0)
	{
		alert("Please varify password.");
		if(condition==true)
		{
			document.adminform.cpassword.focus();
		}
		condition=false;
		return false;
	}
	if(document.adminform.aemail.value.length==0)
	{
		alert("Please enter email.");
		if(condition==true)
		{
			document.adminform.aemail.focus();
		}
		condition=false;
		return false;
	}
	else
	if(!checkMail(document.adminform.aemail.value))
	{
		alert("Email must contain an email address.\n");
		if(condition==true)
		{
			document.adminform.aemail.focus();
		}
		condition=false;
		return false;
	}
	
	if(document.adminform.password.value != "" && 
		document.adminform.cpassword.value != "" ) 
	{
		if(document.adminform.password.value == document.adminform.cpassword.value)
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
//  Valid e_mail address
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
