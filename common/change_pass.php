
<?php
session_start();
require_once 'common.php';
echo '<html>';
echo '<head>';

echo '</head>';
echo '<body>';


////when user reach here, encrypt is already functioning



/////////////Start  of Script///////////////

$link=connect();
menu($link);

if(isset($_POST['action']))
{
	if($_POST['action']=='change_password')
	{
		
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
}
else
{
	read_password();	
}




/*
echo '<pre>';
print_r($_POST);
echo '</pre>';
*/
?>
