<?php
session_start();
require_once '/var/gmcs_config/staff.conf';

function login_varify()
{
	return mysqli_connect('127.0.0.1',$GLOBALS['main_user'],$GLOBALS['main_pass']);
}



/////////////////////////////////
function select_database($link)
{
	return mysqli_select_db($link,'staff');
}


function check_user($link,$u,$p)
{
	$sql='select * from staff where id=\''.$u.'\'';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	$result_array=mysqli_fetch_assoc($result);
	if(md5($p)==$result_array['password'])
	{
		return true;
	}
	else
	{
		return false;
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
	header("Content-Disposition: attachment; filename=$filename");
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
