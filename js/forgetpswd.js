function confirmsubmit()
{
	var condition=true;
	if(document.frmfgtpswd.email.value.length==0)
	{
		alert("Please enter e-mail.");
		if(condition==true)
		{
			document.frmfgtpswd.email.focus();
		}
		condition=false;
		return false;
	}
	else
	if(!checkMail(document.frmfgtpswd.email.value))
	{
		alert("Email must contain an email address.\n");
		if(condition==true)
		{
			document.frmfgtpswd.email.focus();
		}
		condition=false;
		return false;
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
