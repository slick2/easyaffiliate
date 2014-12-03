function confirmsubmit()
{
	var condition=true;
	
	if(document.frmlogin.uname.value == "")
	{
		alert("Please Enter Your Username.");
		if(condition==true)
		{
			document.frmlogin.uname.focus();
		}
		condition=false;
		return false;
	}
	
	if(document.frmlogin.pswd.value.length==0)
	{
		alert("Please Enter Your Password.");
		if(condition==true)
		{
			document.frmlogin.pswd.focus();
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