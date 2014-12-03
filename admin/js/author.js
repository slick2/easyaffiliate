function confirmsubmit()
{
	var condition=true;
	if(document.adminform.mail.value.length==0)
	{
		alert("Please enter e-mail.");
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
	if(document.adminform.password2.value.length==0)
	{
		alert("Please confirm your password.");
		if(condition==true)
		{
			document.adminform.password2.focus();
		}
		condition=false;
		return false;
	}
	if(document.adminform.fname.value.length==0)
	{
		alert("Please enter firstname.");
		if(condition==true)
		{
			document.adminform.fname.focus();
		}
		condition=false;
		return false;
	}
	if(document.adminform.lname.value.length==0)
	{
		alert("Please enter lastname.");
		if(condition==true)
		{
			document.adminform.lname.focus();
		}
		condition=false;
		return false;
	}
	if(document.adminform.add1.value.length==0)
	{
		alert("Please enter address1.");
		if(condition==true)
		{
			document.adminform.add1.focus();
		}
		condition=false;
		return false;
	}
	if(document.adminform.add2.value.length==0)
	{
		alert("Please enter address2.");
		if(condition==true)
		{
			document.adminform.add2.focus();
		}
		condition=false;
		return false;
	}
	if(document.adminform.zip.value.length==0)
	{
		alert("Please enter zip code.");
		if(condition==true)
		{
			document.adminform.zip.focus();
		}
		condition=false;
		return false;
	}
	if(document.adminform.city.value.length==0)
	{
		alert("Please enter city.");
		if(condition==true)
		{
			document.adminform.city.focus();
		}
		condition=false;
		return false;
	}
	if(document.adminform.state.value.length==0)
	{
		alert("Please enter state.");
		if(condition==true)
		{
			document.adminform.state.focus();
		}
		condition=false;
		return false;
	}
	if(document.adminform.country.value.length==0)
	{
		alert("Please select country.");
		if(condition==true)
		{
			document.adminform.country.focus();
		}
		condition=false;
		return false;
	}
	if(document.adminform.phone.value.length==0)
	{
		alert("Please enter phone number.");
		if(condition==true)
		{
			document.adminform.phone.focus();
		}
		condition=false;
		return false;
	}
	if(document.adminform.fax.value.length==0)
	{
		alert("Please enter fax number.");
		if(condition==true)
		{
			document.adminform.fax.focus();
		}
		condition=false;
		return false;
	}
	
	/*if(!IsNumeric(document.form1.age.value))
	{
		alert("PLease enter numeric value for Age.");
		if(condition==true)
		{
			document.form1.age.focus();
		}
		condition=false;
		return false;
			
	}*/
	
	if(document.adminform.password.value != "" && 
		document.adminform.password2.value != "" ) 
	{
		if(document.adminform.password.value == document.adminform.password2.value)
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
//  only insert integer no in Age field.
function IsNumeric(sText)
{
   var ValidChars = "0123456789.";
   var IsNumber=true;
   var Char;

   for (i = 0; i < sText.length && IsNumber == true; i++) 
      { 

      Char = sText.charAt(i); 
      if (ValidChars.indexOf(Char) == -1) 
         {
         IsNumber = false;
         }
      }
   return IsNumber;
}
