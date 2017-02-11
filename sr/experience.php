<?php
session_start();
require_once '../common/common.php';

$link=connect_office();
menu_office();

$gsql='select id,fullname,department from staff,staff_movement 
		where staff.id=staff_movement.staff_id 
			and to_date is null 
			and institute="Government Medical College Surat"  order by department,dob';

if(!$gresult=mysqli_query($link,$gsql)){return FALSE;}
        while($gra=mysqli_fetch_assoc($gresult))
        {
		view_table_experience($link,$gra['id'],$gra['fullname'],$gra['department']);
	}


function view_table_experience($link,$staff_id,$name,$department)
{
	//Designation 	Type 	Department 	Name of Institution 	From - To - Total
	echo '<table border=1>';	
	echo '<tr style="background-color:lightgreen;"><th colspan=10>'.$department.','.$name.'</th></tr>';
	echo '<tr style="background-color:lightblue;"><td>Post</td><td>Type</td><td>Department</td><td>College</td><td>From</td><td>To</td><td>Experience</td></tr>';
	$sql='select * from staff_movement where staff_id=\''.$staff_id.'\' order by `from_date`';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	while($ra=mysqli_fetch_assoc($result))
	{
	echo '<tr>';
	if(strlen($ra['to_date'])==0)
	{
		$to_date='<span style="background-color:lightpink;">till_date</span>';
		$diff=get_date_diff_as_ymd($ra['from_date'],date('Y-m-d'));
	}
	else
	{
		$to_date=$ra['to_date'];
		$diff=get_date_diff_as_ymd($ra['from_date'],$ra['to_date']);
	}
			
	$raw_html='<tr style="background-color:lightgray;">
			</td>
			<td  >'.$ra['post'].'</td>
			<td  >'.$ra['type'].'</td>
			<td  >'.$ra['department'].'</td>
			<td  >'.$ra['institute'].'</td>
			<td>'.$ra['from_date'].'</td><td>'.$to_date.'</td><td>'.$diff.'</td>
			';
			echo $raw_html;
	echo '</tr>';
	}
	echo '</table>';
}



?>
