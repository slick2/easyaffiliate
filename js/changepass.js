function changepassword()
{
	var condition=true;
	if(document.form11.old_pass.value.length==0)
	{
		alert("Please enter your old password.");
		if(condition==true)
		{
			document.form11.old_pass.focus();
		}
		condition=false;
		return false;
	}
	if(document.form11.new_pass.value.length==0)
	{
		alert("Please enter your new password.");
		if(condition==true)
		{
			document.form11.new_pass.focus();
		}
		condition=false;
		return false;
	}
	if(document.form11.cpass.value.length==0)
	{
		alert("Please confirm your new password.");
		if(condition==true)
		{
			document.form11.cpass.focus();
		}
		condition=false;
		return false;
	}
	if(document.form11.new_pass.value != "" && document.form11.cpass.value != "" ) 
	{
		if(document.form11.new_pass.value == document.form11.cpass.value)
		{
			return true;
		}
		else
		{
			alert("New password string and confirm password string does not match.");
			return false;
		}
	}
}
