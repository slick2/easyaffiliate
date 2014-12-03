function confirmsubmit()
{
	var condition=true;
	if(document.form1.delay.value=="choose")
	{
		alert("Please choose email delay time.");
		if(condition==true)
		{
			document.form1.delay.focus();
		}
		condition=false;
		return false;
	}
	if(document.form1.user_from.value.length==0)
	{
		alert("Please enter your email address.");
		if(condition==true)
		{
			document.form1.user_from.focus();
		}
		condition=false;
		return false;
	}
	if(document.form1.user_subject.value.length==0)
	{
		alert("Please and email subject.");
		if(condition==true)
		{
			document.form1.user_subject.focus();
		}
		condition=false;
		return false;
	}
	if(document.form1.user_message.value.length==0)
	{
		alert("Please enter an HTML message.");
		if(condition==true)
		{
			document.form1.user_message.focus();
		}
		condition=false;
		return false;
	}
}	
