<?php
session_start();
require_once 'common.php';
echo '<html>';
echo '<head>';

echo '

<style>
	
table{
   border-collapse: collapse;
}

.border td , .border th{
    border: 1px solid black;
}

.upload{
	background-color:lightpink;	
}

.noborder{
 border: none;
}


.hidedisable
{
	display:none;diabled:true
}

</style>


';
echo '</head>';
echo '<body>';
function read_password()
{
	echo '<table border=1 class="style2"><form method=post>';
	echo '<tr><th colspan=2 class="head">Change Password for access to Staff Database</th></tr>';
	echo '<tr><td>Login ID</td>	<td><input readonly=yes type=text name=id value=\''.$_SESSION['login'].'\'></td></tr>'; 
	echo '<tr><td>Old Password</td>	<td><input type=password name=old_password></td></tr>';
	echo '<tr><td>New Password</td>	<td><input type=password name=password_1></td></tr>';
	echo '<tr><td>Repeat New Password</td>	<td><input type=password name=password_2></td></tr>';
	echo '<tr><td colspan=2 align=center><button type=submit name=action value=change_password>Change Password</button></td></tr>';
	echo '</form></table>';
}

function check_old_password($link,$user,$password)
{
	$sql='select * from  staff where id=\''.$user.'\' ';
	//echo $sql;
	if(!$result=mysqli_query($link,$sql))
	{
		//echo mysql_error();
		return FALSE;
	}
	if(mysqli_num_rows($result)<=0)
	{
		//echo 'No such user';
		echo '<h3>wrong username/password</h3>';
		return false;
	}
	$array=mysqli_fetch_assoc($result);
	if(MD5($password)==$array['password'])
	{
	  	//echo 'You have supplied correct username and password';
		return true;
	}
	else
	{
		//echo 'You have suppled wrong password for correct user';
                echo '<h3>wrong username/password</h3>';
		return false;
	}
}


function update_password($link,$user,$new_password)
{
        $sql='update staff set password=MD5(\''.$new_password.'\') where id=\''.$user.'\' ';
        //echo $sql;
        if(!$result=mysqli_query($link,$sql))
        {
                echo mysqli_error($link);
                return FALSE;
        }

        if(mysqli_affected_rows($link)==1)
        {
                echo '<h3>Update successful. Close browser and restart it again</h3>';
		return true;
        }
		elseif(mysqli_affected_rows($link)==0)
		{
					echo '<h3>Old and new Passwords same. Nothing is changed.</h3>';
					return true;
		}
}


/////////////Start  of Script///////////////

$link=connect();
menu();

if(isset($_POST['action']))
{
	if($_POST['action']=='change_password')
	{
		if($_POST['password_1']==$_POST['password_2'])
		{
			//echo 'OK.  New passwords matches';
			if(check_old_password($link,$_POST['id'],$_POST['old_password']))
			{
				update_password($link,$_POST['id'],$_POST['password_1']);
			}
		}
		else
		{
			echo '<h3>New passwords supplied do not match</h3>';
		}
	}
}
else
{
	read_password();	
}

echo
'<table   class="help" >
		<tr><th class="head">Help</th></tr>
		<tr><td><li>Write old and new password carefully.</td></tr>
		<tr><td><li>close browser and start again after changing password</td></tr>
		<tr><td><li>Change password frequently</td></tr>
		<tr><td><li>Donot reveal your password to anybody.</td></tr>
		<tr><td><li>If your colleague needs access, ask them to visit gmcsurat.edu.in</td></tr>
		<tr><td><li>If you can not access your account, meet IT section of the college</td></tr>
 </table>';

/*
echo '<pre>';
print_r($_POST);
echo '</pre>';
*/
?>
