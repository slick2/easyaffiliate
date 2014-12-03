function confirmsubmits()
{
	var condition=true;
	if(document.form2.delay2.value=='choose')
	{
		alert("Please choose an email delay time.");
		if(condition==true)
		{
			document.form2.delay2.focus();
		}
		condition=false;
		return false;
	}
	
	if(document.form2.user_from2.value.length==0)
	{
		alert("Please enter your email address.");
		if(condition==true)
		{
			document.form2.user_from2.focus();
		}
		condition=false;
		return false;
	}
  
  if(document.form2.user_subject2.value.length==0)
	{
		alert("Please enter your subject.");
		if(condition==true)
		{
			document.form2.user_subject2.focus();
		}
		condition=false;
		return false;
	}
  
	if(document.form2.user_message2.value=="Type your message here")
	{
		alert("Please an email message.");
		if(condition==true)
		{
			document.form2.user_message2.focus();
		}
		condition=false;
		return false;
	}
	
}
