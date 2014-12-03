function confirmsubmit()
{
	var condition=true;
	if(document.frmsignup.email.value.length==0)
	{
		alert("Please enter e-mail.");
		if(condition==true)
		{
			document.frmsignup.email.focus();
		}
		condition=false;
		return false;
	}
	else
	if(!checkMail(document.frmsignup.email.value))
	{
		alert("Email must contain an email address.\n");
		if(condition==true)
		{
			document.frmsignup.email.focus();
		}
		condition=false;
		return false;
	}
	if(document.frmsignup.email_v.value.length==0)
	{
		alert("Please enter e-mail.");
		if(condition==true)
		{
			document.frmsignup.email_v.focus();
		}
		condition=false;
		return false;
	}
	else
	if(!checkMail(document.frmsignup.email_v.value))
	{
		alert("Email must contain an email address.\n");
		if(condition==true)
		{
			document.frmsignup.email_v.focus();
		}
		condition=false;
		return false;
	}
	
	if(document.frmsignup.pswd.value.length==0)
	{
		alert("Please enter password.");
		if(condition==true)
		{
			document.frmsignup.pswd.focus();
		}
		condition=false;
		return false;
	}
	if(document.frmsignup.cpswd.value.length==0)
	{
		alert("Please confirm your password.");
		if(condition==true)
		{
			document.frmsignup.cpswd.focus();
		}
		condition=false;
		return false;
	}
	if(document.frmsignup.fname.value.length==0)
	{
		alert("Please enter firstname.");
		if(condition==true)
		{
			document.frmsignup.fname.focus();
		}
		condition=false;
		return false;
	}
	if(document.frmsignup.lname.value.length==0)
	{
		alert("Please enter lastname.");
		if(condition==true)
		{
			document.frmsignup.lname.focus();
		}
		condition=false;
		return false;
	}
	if(document.frmsignup.add1.value.length==0)
	{
		alert("Please enter address1.");
		if(condition==true)
		{
			document.frmsignup.add1.focus();
		}
		condition=false;
		return false;
	}
	if(document.frmsignup.add2.value.length==0)
	{
		alert("Please enter address2.");
		if(condition==true)
		{
			document.frmsignup.add2.focus();
		}
		condition=false;
		return false;
	}
	if(document.frmsignup.zip.value.length==0)
	{
		alert("Please enter zip code.");
		if(condition==true)
		{
			document.frmsignup.zip.focus();
		}
		condition=false;
		return false;
	}
	if(document.frmsignup.city.value.length==0)
	{
		alert("Please enter city.");
		if(condition==true)
		{
			document.frmsignup.city.focus();
		}
		condition=false;
		return false;
	}
	if(document.frmsignup.state.value.length==0)
	{
		alert("Please enter state.");
		if(condition==true)
		{
			document.frmsignup.state.focus();
		}
		condition=false;
		return false;
	}
	if(document.frmsignup.country.value=="Select Country")
	{
		alert("Please select country.");
		if(condition==true)
		{
			document.frmsignup.country.focus();
		}
		condition=false;
		return false;
	}
	if(document.frmsignup.phone.value.length==0)
	{
		alert("Please enter phone number.");
		if(condition==true)
		{
			document.frmsignup.phone.focus();
		}
		condition=false;
		return false;
	}
	if(document.frmsignup.fax.value.length==0)
	{
		alert("Please enter fax number.");
		if(condition==true)
		{
			document.frmsignup.fax.focus();
		}
		condition=false;
		return false;
	}
	if(document.frmsignup.terms.checked==false)
	{
		alert("Please accept terms and conditions.");
		if(condition==true)
		{
			document.frmsignup.terms.focus();
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
	
	
	if(document.frmsignup.pswd.value != "" && 
		document.frmsignup.cpswd.value != "" ) 
	{
		if(document.frmsignup.pswd.value == document.frmsignup.cpswd.value)
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
