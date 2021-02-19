<?php
session_start();
require_once '/var/gmcs_config/staff.conf';

function login_varify()
{
	return mysqli_connect('127.0.0.1',$GLOBALS['main_user'],$GLOBALS['main_pass']);
}

function logout($message='')
{
	session_start(); //Start the current session
	//$GLOBALS['rootpath']."/index.php";
	session_destroy(); //Destroy it! So we are logged out now	
	header("location:".$GLOBALS['rootpath']."/index.php?".$message); //configure absolute path of this file for access from anywhere
}

/////////////////////////////////
function select_database($link)
{
	return mysqli_select_db($link,'staff');
}


function check_user($link,$u,$p)
{
	$sql='select * from staff where id=\''.$u.'\'';
	//echo $sql;
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	$result_array=mysqli_fetch_assoc($result);
	//check validation
	
	
	//First verify encrypted password
	if(password_verify($p,$result_array['epassword']))
	{
		//echo strtotime($result_array['expirydate']).'<'.strtotime(date("Y-m-d"));
		
		if(strtotime($result_array['expirydate']) < strtotime(date("Y-m-d")))
	    {
		echo '<body>
	            <link rel="stylesheet" href="../css/style.css"> <div id="container">
	            <br><br>
	            <div style="margin-left:450px;padding:15px">
               <form method=post>
                   <table  cellspacing="10" width="40%" style="background-color:lightgrey; border: 1px solid black;">
                    <tr>
		                  <th colspan=2 class="head">
		                      Password Expired
		                  </th>
		                
		            </tr>
		            <tr>
		                  <td></td>
		                  <td></td>
		            </tr>
	                <tr>
		                 <th>
			                  Login Id
		                 </th>
		                 <td>
			                  <input type=text readonly name=login id=id value=\''.$_SESSION['login'].'\'>
		                 </td>
	                </tr>';
                    //<tr>
		                 //<th>
		                  	//Password
		                 //</th>
		                 //<td>
			                 //<input type=password readonly name=password id=name value=\''.$_SESSION['password'].'\'>
	                 	//</td>
	               //</tr>
	             echo'  <tr>
		                <td></td>
		                <td>
                           <button style="background-color:lightgreen" class="menub" name=action type=submit value=change_password_step_1 formaction='.$GLOBALS['rootpath'].'/common/change_expired_pass.php ">Change Password</button>
	               	    </td>
	               </tr>
	              </table>
	              </form>
	              </div>';

			exit(0);
	    }
	    else
	    {
			//do nothing
	    }
		return true;	
	}
	
	//donot enter if password length stored is 0
	else if(strlen($result_array['password'])>0)
    {	
		if(md5($p)==$result_array['password'])		//last chance for md5
		{
			 $sqli="update staff set epassword='".password_hash($p,PASSWORD_BCRYPT)."' where id='$u'";	
	         //echo $sqli;
	         $user_pwd=run_query($link,'staff',$sqli);
	        // echo $user_pwd;
	         if($user_pwd>0)
	         {
		         //erase md5 password, set length 0
		         $sqlm="update staff set password='' where id='$u'";
				 //echo $sqlm;
				 $user_pwd=run_query($link,'staff',$sqlm);
				return true;	

			 }
	         else
	         {
		        return false;	//if encrypted password is not written
	         }
	         
		}
	}
	
	else //if encrypt fail and md5 lenght is zero, get out
	{
		return false;
	}
}
function run_query($link,$db,$sql)
{
	$db_success=mysqli_select_db($link,$db);
	
	if(!$db_success)
	{
		echo 'error2:'.mysqli_error($link); return false;
	}
	else
	{
		$result=mysqli_query($link,$sql);
	}
	
	if(!$result)
	{
		echo 'error3:'.mysqli_error($link); return false;
	}
	else
	{
	return $result;
	}	
}

function connect()
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
	
	if(!check_user($link,$_SESSION['login'],$_SESSION['password']))
	{
		echo 'application user could not be varified<br>';
		logout("message=Wrong Password");		
		exit();
	}
	
return $link;
}


function get_raw($link,$sql)
{
	//echo $sql;
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	if(mysqli_num_rows($result)!=1){echo mysqli_error($link);return false;}
	else
	{
		return mysqli_fetch_assoc($result);
	}
}

$link=connect();

function download_blob($file,$filename)
{
	header("Content-Disposition: attachment; filename=\"$filename\"");
			//header("Content-length: $length");
			//header("Content-type: $type");		
			echo $file;
}

//echo '<pre>';
//print_r($GLOBALS);
$wr=base64_decode($_POST['where']);
$sql='select * from '.$_POST['table'].' '.$wr;

$data=get_raw($link,$sql);
download_blob($data[$_POST['file']],$data[$_POST['filename']]);

?>
