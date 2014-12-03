function confirmsubmit()
{
		var condition=true;
		if(document.adminform.url.value.length==0)
		{
			alert("Please enetr full URL.");
			if(condition==true)
			{
				document.adminform.url.focus();
			}
			condition=false;
			return false;
		}
		if(document.adminform.name.value.length==0)
		{
			alert("Please enter site name.");
			if(condition==true)
			{
				document.adminform.name.focus();
			}
			condition=false;
			return false;
		}
		if(document.adminform.mail.value.length==0)
		{
			alert("Please enter email address.");
			if(condition==true)
			{
				document.adminform.mail.focus();
			}
			condition=false;
			return false;
		}
		
		if(document.adminform.mail.value.length==0)
		{
			alert("Please enter email address.");
			if(condition==true)
			{
				document.adminform.mail.focus();
			}
			condition=false;
			return false;
		}
		else
		if(!checkMail(document.adminform.mail.value))
		{
			alert("Email must contain an email address.\n");
			if(condition==true)
			{
				document.adminform.mail.focus();
			}
			condition=false;
			return false;
		}
		
		
		if(document.adminform.total_article.value.length==0)
		{
			alert("Please enter total no of articles in home.");
			if(condition==true)
			{
				document.adminform.total_article.focus();
			}
			condition=false;
			return false;
		}
		if(document.adminform.related_article.value.length==0)
		{
			alert("Please enter related article.");
			if(condition==true)
			{
				document.adminform.related_article.focus();
			}
			condition=false;
			return false;
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
