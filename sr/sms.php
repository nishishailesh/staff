<?php
session_start();
require_once '../common/common.php';

function read_sms()
{
	echo '<form method=post>';
	echo '<table border=1>';
	echo '<tr><th colspan=2>Send SMS</th></tr>'; 
	echo '<tr><td>Mobile</td><td><input type=text name=num></td></tr>';
	echo '<tr><td>SMS</td><td><input type=text name=sms></td></tr>';
	echo '<tr><td colspan=2><input name=submit type=submit value=send></td></tr>';
	echo '</form>';
	echo '</table>';
}

function send_sms_mobi1()
{
	$str='http://mobi1.blogdns.com/httpmsgid/SMSSenders.aspx';
	$getdata = http_build_query
		(
		array(
			'UserID' => 'gmcsrttr',
			'UserPass' => 'gmc123',
			'Message'=>$_POST['sms'],
			'MobileNo'=>$_POST['num'],
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

$link=connect_office();
menu_office();

read_sms();

if(isset($_POST['submit']))
{
	$result=send_sms_mobi1();
	echo 'Report:'.$result;
}

/*
if(!isset($_POST['submit']))
{
print_r($_POST);
$getdata = http_build_query(
array(
    'UserID' => 'gmcsrttr',
    'UserPass' => 'gmc123',
 'Message'=>'sms with space',
 'MobileNo'=>'9426328832',
 'GSMID'=>'GMCSRT'
 )
);
echo $getdata;
exit();
}
*/

//print_r($_POST);


//$str='http://mobi1.blogdns.com/httpmsgid/SMSSenders.aspx?UserID=gmcsrttr&UserPass=gmc123&Message='
//					.$_POST['sms'].'&MobileNo='.$_POST['num'].'&GSMID=GMCSRT';




?>
