<?php
/*
Register:
	1) mobile as username, password sent to mobile
	2) email as username, password sent to email
	3) sms email/mobile to ronakk, password will be generated and sent to same email/mobile in 24 hours

change password
forgot password
  -send sms/email
  -sms/email ronakk
  -generate and send back
  -if next login with new, disable old
  -if next login with old, disable new
  
Save
Lock
Print

*/

function get_mobile_reg_info()
{
	echo '<form method=post>';
	echo '<table border=1  align=center style="background-color:lightblue">';
	echo '<tr><th colspan=3 style="background-color:lightgray">Registration Method 1</th></tr>';
	echo '<tr><th colspan=3>Mobile number will be your username</th></tr>';
	echo '<tr><th colspan=3>Password will be sent to your mobile number<th></tr>';
	echo '<tr><td>Mobile</td><td>';
	echo '<input type=text placeholder="Write mobile number" name=mobile>';
	echo '</td>';
	echo '<td>';
	echo '<input type=submit  name=action value=send_sms>';	
	echo '</td></tr>';
	echo '</table>';
	echo '</form>';

}


function get_email_reg_info()
{
	echo '<form method=post>';
	echo '<table border=1 style="background-color:lightgreen"  align=center>';
	echo '<tr><th colspan=3 style="background-color:lightgray">Registration Method 2</th></tr>';
	echo '<tr><th colspan=3>email will be your username</th></tr>';
	echo '<tr><th colspan=3>Password will be sent to your email<th></tr>';
	echo '<tr><td>email</td><td>';
	echo '<input type=text placeholder="Write email id" name=email>';
	echo '</td>';
	echo '<td>';
	echo '<input type=submit  name=action value=send_email>';	
	echo '</td></tr>';
	echo '</table>';
	echo '</form>';

}


function manual_reg()
{
	echo '<form method=post>';
	echo '<table border=1 style="background-color:lightpink"  align=center>';
	echo '<tr><th colspan=3 style="background-color:lightgray">Registration Method 3</th></tr>';
	echo '<tr><th colspan=3>send SMS "TECH" to 1234567890</th></tr>';
	echo '<tr><th colspan=3>Or</th></tr>';
	echo '<tr><th colspan=3>write "TECH" as subject to email: serverroor@gmail.com<th></tr>';
	echo '<tr><th>password will be sent in 24 hours</th></tr>';
	echo '</table>';
	echo '</form>';

}

function login()
{
	echo '<form>';
	echo '<table border=1 style="background-color:#F49797" align=center>';
	echo '<tr><th colspan=3 style="background-color:lightgray">Registered users</th></tr>';
	echo '<tr><td>login id</td><td>';
	echo '<input type=text placeholder="Write login id" name=login>';
	echo '</td>';
	echo '<tr><td>password</td><td>';
	echo '<input type=password placeholder="Write password" name=password>';
	echo '</td></tr><tr>';
	echo '<th colspan=2>';
	echo '<input type=submit  name=action value=Login>';	
	echo '</th></tr>';
	echo '</table>';
	echo '</form>';
 
}

function send_sms($sms,$num)
{
	$str='http://mobi1.blogdns.com/httpmsgid/SMSSenders.aspx';
	$getdata = http_build_query
		(
		array(
			'UserID' => 'gmcsrttr',
			'UserPass' => 'gmc123',
			'Message'=>$sms,
			'MobileNo'=>$num,
			'GSMID'=>'GMCSRT'
			)
		);
								
	$hdr = "Content-Type: application/x-www-form-urlencoded";
                    
	$opts = array('http' =>
					array(
						'method'  => 'GET',
						'content' => $getdata,
						'header'  => $hdr
						)
				);

	$context  = stream_context_create($opts);
	//echo $str;
	$ret=file_get_contents($str,false,$context);
	return $ret;
}

////////////////Start/////////
print_r($_POST);
echo ' <h3  align=center style="background-color:#7B64AA" >Online application, Lab/X-Ray technician training course</h1>';
echo ' <h3  align=center style="background-color:#7B64AA">Government Medical College Surat</h1>';
 
login();

echo '<h3 style="background-color:#7B64AA"  align=center>Not registered, register by any one of following method</h3>';

get_mobile_reg_info();
get_email_reg_info();
manual_reg();

if(isset($_POST['action']))
{
	if($_POST['action']=='send_sms')
	{
		$text=rand(11111,99999);echo $text;
		$x=send_sms('password:'.$text,$_POST['mobile']);
		echo 'result:'.$x;
	}
	else
	{
		echo 'dddd';
	}
}
?>
