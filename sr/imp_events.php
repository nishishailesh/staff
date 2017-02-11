<?php
session_start();
require_once '../common/common.php';
require_once 'save.php';

$link=connect();
menu();


echo '<h2>Please note following. It will help you to maintain staff database uptodate</h2>'; 

/////////////staff table////////////////

$staff_detail=get_raw($link,'select * from staff where id=\''.$_SESSION['login'].'\'');

if(strlen($staff_detail['fullname'])==0){echo '<h4><li>Write fullname with surname first, as in service records</li></h4>'; }

if(
	strlen($staff_detail['dob'])==0 
	||
	$staff_detail['dob']=='0000-00-00'
	)
	{echo '<h4><li>Write proper date of birth</li></h4>'; }
	
if(strlen($staff_detail['residencial_address'])==0){echo '<h4><li>Write proper recidencial address</li></h4>'; }

if(strlen($staff_detail['residencial_phone'])==0){echo '<h4><li>Write proper residencial phone</li></h4>'; }
if(strlen($staff_detail['mobile'])<10){echo '<h4><li>Write proper mobile number</li></h4>'; }
if(strlen($staff_detail['email'])==0 || !filter_var($staff_detail['email'],FILTER_VALIDATE_EMAIL))
	{echo '<h4><li>Write proper email</li></h4>'; }
if(strlen($staff_detail['catagory'])==0){echo '<h4><li>Select catagory as in service records</li></h4>'; }

/////////////staff table complate////////////////

/////////////photo table////////////////

$photo=get_raw($link,'select * from photo where id=\''.$_SESSION['login'].'\'');
if(strlen($photo['proof_type'])==0){echo '<h4><li>Select photo id proof type</li></h4>'; }
if(strlen($photo['proof_number'])==0){echo '<h4><li>Write photo id proof number</li></h4>'; }
if(strlen($photo['proof_issued_by'])==0){echo '<h4><li>Write issue authority of photo id proof</li></h4>'; }
if(strlen($photo['photo_id'])==0){echo '<h4><li><span style="background:lightpink;">Upload</span> photo id. preferably in jpg or pdf format</li></h4>'; }
if(strlen($photo['photo'])==0){echo '<h4><li><span style="background:lightpink;">Upload</span> photo. If it is not jpg format, it will fail to display</li></h4>'; }

/////////////photo table complate////////////////

/////////////departmental_exam table ////////////////
$dep_ex=get_raw($link,'select * from departmental_exam where staff_id=\''.$_SESSION['login'].'\'');

if(strlen($dep_ex['cccplus'])==0){echo '<h4><li>Select CCC+ exam status as per service records</li></h4>'; }
if(strlen($dep_ex['gujarati'])==0){echo '<h4><li>Select GUJARATI exam status as per service records</li></h4>'; }
if(strlen($dep_ex['hindi'])==0){echo '<h4><li>Select HINDI exam status as per service records</li></h4>'; }

/////////////departmental_exam table complate////////////////
 
//////////////Qualification,experience, check for at least one raw/////////

function count_qualification($link)
{
	$sql='select * from qualification where staff_id=\''.$_SESSION['login'].'\'';
	if(!$result=mysqli_query($link,$sql)){return 0;}
	return mysqli_num_rows($result);
}
if(count_qualification($link)==0){echo '<h4><li>Add Qualification. You must be having at least one!!</li></h4>'; }

function check_current_experience($link)
{
	$sql='select * from staff_movement where staff_id=\''.$_SESSION['login'].'\' and to_date is null ';
	//echo $sql;
	if(!$result=mysqli_query($link,$sql)){return 0;}
	return mysqli_num_rows($result);
}

$cur_exp_num=check_current_experience($link);

if($cur_exp_num==0)
{
	echo '<h4><li>Add CURRENT job details in Experience tab. <li>Have you forgotten to select til_date in current experience? <li>current experience needs to be added before its appointment orders, joining order and previous relieving order is uploaded</li></h4>'; 
}
elseif($cur_exp_num>1)
{
	echo '<h4><li>There can not be more than two current experience. Have you added more than one "<span style="background:lightpink;"> till_date"</span></li></h4>';
	
}
else
{
	$current_appointment=get_raw($link,'select * from staff_movement where staff_id=\''.$_SESSION['login'].'\' and to_date is NULL');
		
	$current_appointment_order=get_raw($link,'select * from staff_movement_attachment 
			where movement_id=\''.$current_appointment['movement_id'].'\' and type=\'appointment_order\'');
			
	if($current_appointment_order===FALSE)
	{echo '<h4><li><span style="background:lightpink;">Upload</span> Current appointment order</li></h4>'; }
	
	$current_joining_order=get_raw($link,'select * from staff_movement_attachment 
			where movement_id=\''.$current_appointment['movement_id'].'\' and type=\'joining_order\'');
	if($current_joining_order===FALSE)
	{echo '<h4><li><span style="background:lightpink;">Upload</span> joining order at current institute</li></h4>'; }

	$previous_relieving_order=get_raw($link,'select * from staff_movement_attachment 
			where movement_id=\''.$current_appointment['movement_id'].'\' and type=\'relieving_order\'');
	if($previous_relieving_order===FALSE)
	{echo '<h4><li><span style="background:lightpink;">Upload</span> relieving order from previous institute</li></h4>'; }
	
}
	
///////PAN card////////////
$dep_ex=get_raw($link,'select * from pan where staff_id=\''.$_SESSION['login'].'\'');

if(strlen($dep_ex['attachment'])==0){echo '<h4><li><span style="background:lightpink;">Upload</span> PAN card</li></h4>'; }

?>

