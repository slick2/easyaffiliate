<?php
// If member is not login or session is not set
if(!isset($_SESSION['userid']) || $_SESSION['userid'] == '')
{
	header("location:index.php?filename=adminlogin");
	die();
}
	
$subject = "Your Article Submission";
				
								/* message */
				$message = stripString("
						<html>
						<head>
						<title>Article notification From ".$title.".</title>
						</head>
						<body>
						
						
						<table>
						<tr>
						  <td>Dear ".$fname.",</td>
						</tr>
						<tr>
						  <td>We are pleased to inform you that your article entitled  <a href='".$site_URL."articledetail.php?artid=".$articleid."&catid=".$intCategory."'>".$varArticleTitle."</a>  has been
              approved!<br> 
							</td>
						</tr>
						<tr>
						  <td><p>Thank You</p><p>Regards,</p><p>".$owner_name."</p></td>
						</tr>
						<tr>
						 <td>".$title."</td>
						</tr>
						<tr>
                            <td>
                          #########################################################<br>
                          <b>Be sure to check out the same software that powers our Article Publishing site.<br>
                          It's called Article Friendly and you can see it in action right here:  <a href='http://www.articlefriendly.com'>Article Friendly Article Publishing Script</a><br><br>
                          Ton's of options and at a great price too!  Free lifetime support, upgrades & addons at <a href='http://www.articlefriendly.info/forum/'>AF User Support Forum</a><br><br>
                          Would you like to submit your Articles to Article Dashboard and Article Friendly sites (hundreds) in minutes rather than hours?<br>
                          This is THE FASTEST Article submission script on the net, and at the LOWEST price.. FREE!<br>
                          <a href='http://www.articlemover.com'>AF Article Submitter</a><br><br></b>
                          #########################################################<br><br>
                          </p></td>
                        </tr>
						</table>
						</body>
						</html>
						");
?>