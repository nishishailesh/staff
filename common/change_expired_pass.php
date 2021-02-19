<?php
session_start();
require_once 'common.php';

echo '<html>';
echo '<head>';

echo '</head>
<link rel="stylesheet" href="../css/style.css">';
echo '<body>';


/////////////Start  of Script///////////////

//$link=connect();

//menu();

//print_r($_POST);

if(isset($_POST['action']))
{
	if($_POST['action']=='change_password')
	{
		if(!$link=login_varify())
		{
			echo 'database login could not be verified<br>';
			logout("message=database not connected");	
			exit();
		}


		if(!select_database($link))
		{
			echo 'database could not be selected<br>';
			logout("message=database not connected");	
			exit();
		}
			
		if(is_valid_password($_POST['password_1'])==$_POST['password_1'])
		{
			if($_POST['password_1']==$_POST['password_2'])
			{
				//echo 'OK.  New passwords matches';
				if(check_old_password($link,$_POST['id'],$_POST['old_password']))
				{
					if(!update_password($link,$_POST['id'],$_POST['password_1'])){echo 'Password update failed!';}
					else
					{
						logout("message=Password changed successfully. Re login!!");
					}
				}
				else
				{
					logout("message=Change password failed!!<br> old password was wrong");
				}
			}
			else
			{
				logout("message=Change password failed!!<br> New Password mismatch");
			}
		}
		else
		{
			logout("message=Change password failed!! <br>Ensure mix of lower case, upper case, number and special characters");
		}
	}
	
	if($_POST['action']=='change_password_step_1')
	{
		read_password();
	}
}



/*
echo '<pre>';
print_r($_POST);
echo '</pre>';
*/
?>
