<?php
session_start();
require_once '../common/common.php';

//echo '<pre>';print_r($_POST);echo '</pre>';

/////////////Main script start from here//////////////


echo '
<script type="text/javascript" src="../date/datepicker.js"></script>
<script src="../js/jquery-3.1.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="../date/datepicker.css" /> 
<script src="../js/gmcs_javascript.js" type="text/javascript"></script>
';


echo '<script>


function my_combo(ck,yes_target,no_target)
{
	if(ck.checked==true)
	{
		document.getElementById(yes_target).style.display="block";
		document.getElementById(no_target).style.display="none";
	}
	else
	{
		document.getElementById(no_target).style.display="block";	
		document.getElementById(yes_target).style.display="none";
	}	
	
}	
</script>


';

$link=connect();

menu($link);


function add_staff($link)
{

	$staff_id=$_SESSION['login'];
	echo '<table class=border id=add style="display:none">';
	echo '<tr style="background-color:lightgreen;"><th  colspan="3" style="color:blue;margin:2px;">Add Experience</th></tr>';
	
	
		echo '<tr style="background-color:blue;"><td>';
		echo '<form method=post style="margin-bottom:0;">';
		echo '<input type=hidden readonly name=staff_id value=\''.$staff_id.'\' >';	
		echo '</td></tr>';

		$st="background-color:lightpink";

		
			echo '<tr style="'.$st.'"><th>Institute</th><td>';	
				mk_combo_new($link,'select institute from institute',
						'institute','institute','institute','','');	

			echo '</td></tr><tr style="'.$st.'"><th>Department</th><td>';	

			mk_select_from_sql($link,'select department from department',
			'department','department','','');
			echo '</td></tr><tr style="'.$st.'"><th>Post</th><td>';	

			mk_select_from_sql($link,'select designation_type from designation_type',
			'designation_type','post','','');
			echo '</td></tr><tr style="'.$st.'"><th>Appintment type</th><td>';	

					mk_select_from_sql($link,'select appointment_type from appointment_type',
			'appointment_type','appointment_type','','');
			echo '</td></tr>';	
			
		$j_time=array("FN","AN");

		echo '
					<tr style="'.$st.'">
						<td>From:</td>
						<td><input readonly class=datepicker name=from_date id=\'from_date_new\' >
						';
						mk_select_from_array($j_time,'from_time','','');
						echo '</td>
					</tr>
					<tr style="'.$st.'">
						<td>To:</td>
						<td>	<input  readonly class=datepicker name=to_date id=\'to_date_new\' >
						';
						mk_select_from_array($j_time,'to_time','','');
						echo '
						<input type=checkbox name=to_date_checkbox id=to_date_checkbox > Till Date
						Tick mark (current status)</td>
					</tr>
				';
						
			echo '<tr style="'.$st.'"><th colspan=5  style="background-color:red;">';	

				
		echo '<button class=submitbutton type=submit name=action value=add>Add</button>';
		echo '</th>';
		echo '</tr></form>';
	echo '</table>';
}

function edit_staff($link)
{

	$sql='select * from staff_movement where staff_id=\''.$_SESSION['login'].'\' order by `from_date`';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	echo '<table class=border>';
	echo '<tr style="background-color:lightgreen;"><th style="color:blue;margin:2px;">Edit/ Delete Experience

	<form style="display:inline;" method=post action=new.php><button style="background-color:lightblue" class="menub" >
	<h4 style="margin: 0px; padding: 0px;" >Return to Declaration Form</h4></button></form></th>
	</tr>';
	echo '<tr style="background-color:lightpink;"><th  colspan="3" >Scroll down to edit newer experiences</th></tr>';


	$count=0;
	while($ra=mysqli_fetch_assoc($result))
	{
		echo '<tr style="background-color:blue;"><td>';
		echo '<form method=post style="margin-bottom:0;">';
		echo '<input type=hidden readonly name=movement_id value=\''.$ra['movement_id'].'\'>';	
		echo '<input type=hidden readonly name=staff_id value=\''.$ra['staff_id'].'\'>';	
		echo '</td></tr>';
		
		if(($count%2)==0)
		{
			$st="background-color:lightblue";
		}
		else
		{
			$st="background-color:lightgray";
		}
		
			echo '<tr style="'.$st.'"><td>';	
				mk_combo($link,'select institute from institute',
						'institute','institute','institute','',$ra['institute']);	

			echo '</td></tr><tr style="'.$st.'"><td>';	

			mk_select_from_sql($link,'select department from department',
			'department','department','',$ra['department']);
			echo '</td></tr><tr style="'.$st.'"><td>';	

			mk_select_from_sql($link,'select designation_type from designation_type',
			'designation_type','post','',$ra['post']);
			echo '</td></tr><tr style="'.$st.'"><td>';	

					mk_select_from_sql($link,'select appointment_type from appointment_type',
			'appointment_type','appointment_type','',$ra['type']);
			echo '</td></tr><tr style="'.$st.'"><td>';	
			
	if(strlen($ra['to_date'])==0)
	{
		$tick='checked';
		$to_date='';
	}
	else
	{
		$to_date=$ra['to_date'];
		$tick='';
	}		

$j_time=array("FN","AN");

		echo '<table>
					<tr style="'.$st.'">
						<td>From:</td>
						<td><input readonly class=datepicker name=from_date id=\'from_date_'.$ra['movement_id'].'\'  value=\''.mysql_to_india_date($ra['from_date']).'\'></td>
						<td>';
						mk_select_from_array($j_time,'from_time','',$ra['from_time']);
						echo '</td>
					</tr>
					<tr style="'.$st.'">
						<td>To:</td>
						<td>	<input  readonly class=datepicker name=to_date id=\'to_date_'.$ra['movement_id'].'\' value=\''.mysql_to_india_date($to_date).'\'></td>
						<td>';
						mk_select_from_array($j_time,'to_time','',$ra['to_time']);
						echo '</td>
					</tr>
					<tr style="'.$st.'">
						<td><input type=checkbox name=to_date_checkbox id=to_date_checkbox '.$tick.'> Till Date</td>
						<td colspan=2>Tick mark if this is your current status</td>
					</tr>
				</table>';
						
			echo '</td></tr><tr style="'.$st.'"><td>';	

				
		echo '<button type=submit name=action value=update>Modified, Now Save</button>';
		echo '<button type=submit name=action value=delete onclick="return confirm(\'Data will be deleted permanenty\')" >Delete</button>';
		echo '</td>';
		echo '</tr></form>';
		echo '<tr style="background-color:blue" ><td></td></tr>';
		$count++;
	}

	echo '</table>';
}


function insert($link)
{
	
	if(isset($_POST['institute_text_check']))
	{
		$institute=$_POST['institute_text'];
	}
	else
	{
		$institute=$_POST['institute'];
	}

	if(isset($_POST['to_date_checkbox']))
	{
		$to_date='null';
		$to_time='null';
	}
	else
	{
		$to_date='\''.india_to_mysql_date($_POST['to_date']).'\'';
		$to_time='\''.$_POST['to_time'].'\'';
	}
		
	$sql='insert into staff_movement 
	
	(staff_id,institute,department,post,from_date,from_time,to_date,to_time,type) 
	values(	\''.$_POST['staff_id'].'\',
			\''.$institute.'\',
			\''.$_POST['department'].'\',
			\''.$_POST['post'].'\',
			\''.india_to_mysql_date($_POST['from_date']).'\',
			\''.$_POST['from_time'].'\','
			.$to_date.','
			.$to_time.','
			.'\''.$_POST['appointment_type'].'\')';
			
	//echo $sql;
	
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link); return FALSE;}								
}

function update($link)
{
	
	if(isset($_POST['institute_text_check']))
	{
		$institute=$_POST['institute_text'];
	}
	else
	{
		$institute=$_POST['institute'];
	}

	if(isset($_POST['to_date_checkbox']))
	{
		$to_date='null';
		$to_time='null';
	}
	else
	{
		$to_date='\''.india_to_mysql_date($_POST['to_date']).'\'';
		$to_time='\''.$_POST['to_time'].'\'';
	}	
	
	$sql='update staff_movement set
							institute=\''.$institute.'\',
							department=\''.$_POST['department'].'\',
							post=\''.$_POST['post'].'\',
							type=\''.$_POST['appointment_type'].'\',
							from_date=\''.india_to_mysql_date($_POST['from_date']).'\',
							from_time=\''.$_POST['from_time'].'\',
							to_date='.$to_date.',
							to_time='.$to_time.'
					where 
							movement_id=\''.$_POST['movement_id'].'\'';
	//echo $sql;
	
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link); return FALSE;}								
}

function del($link)
{
	$sql='delete from staff_movement where movement_id=\''.$_POST['movement_id'].'\'';
	
	//echo $sql;
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link); return FALSE;}else{echo 'deleted: '.mysqli_affected_rows($link);}

}




function edit_single_experience($link,$mid)
{
	ob_start();
	$sql='select * from staff_movement where movement_id=\''.$mid.'\'';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
//	echo '<table class=border>';
//	echo '<tr style="background-color:lightgreen;"><th style="color:blue;margin:2px;">Edit/ Delete Experience


//	</tr>';
//	echo '<tr style="background-color:lightpink;"><th  colspan="3" >Scroll down to edit newer experiences</th></tr>';


//	$count=0;
	$html='';
	$ra=mysqli_fetch_assoc($result);
	
		
		echo '<table style="display:none;" id=mid_'.$mid.'>';
		echo '<form method=post style="margin-bottom:0;">';
		echo '<input type=hidden readonly name=movement_id value=\''.$ra['movement_id'].'\'>';	
		echo '<input type=hidden readonly name=staff_id value=\''.$ra['staff_id'].'\'>';	
		
			$st="background-color:lightblue";

		
			echo '<tr style="'.$st.'"><th>Institute</th><td>';	
				mk_combo($link,'select institute from institute',
						'institute','institute','institute','',$ra['institute']);	

			echo '</td></tr><tr style="'.$st.'"><th>Department</th><td>';	

			mk_select_from_sql($link,'select department from department',
			'department','department','',$ra['department']);
			echo '</td></tr><tr style="'.$st.'"><th>Post</th><td>';	

			mk_select_from_sql($link,'select designation_type from designation_type',
			'designation_type','post','',$ra['post']);
			echo '</td></tr><tr style="'.$st.'"><th>Appointment Type</th><td>';	

					mk_select_from_sql($link,'select appointment_type from appointment_type',
			'appointment_type','appointment_type','',$ra['type']);
			echo '</td></tr>';	
			
	if(strlen($ra['to_date'])==0)
	{
		$tick='checked';
		$to_date='';
	}
	else
	{
		$to_date=$ra['to_date'];
		$tick='';
	}		

$j_time=array("FN","AN");

		echo '
					<tr style="'.$st.'">
						<th>From:</th>
						<td><input readonly class=datepicker name=from_date id=\'from_date_'.$ra['movement_id'].'\'  value=\''.mysql_to_india_date($ra['from_date']).'\'>
						';
						mk_select_from_array($j_time,'from_time','',$ra['from_time']);
						echo '</td>
					</tr>
					<tr style="'.$st.'">
						<th>To:</th>
						<td>	<input  readonly class=datepicker name=to_date id=\'to_date_'.$ra['movement_id'].'\' value=\''.mysql_to_india_date($to_date).'\'>
						';
						mk_select_from_array($j_time,'to_time','',$ra['to_time']);
						echo '
						<input type=checkbox name=to_date_checkbox id=to_date_checkbox '.$tick.'> Till Date (Current Status)</td>
					</tr>
				';
						
			echo '<tr style="background-color:blue;"><td colspan=7 align=center>';	

				
		echo '<button style="width: 350px;" class=submitbutton type=submit name=action value=update>Modified, Now Save</button>';
		echo '<button class=submitbutton type=submit name=action value=delete onclick="return confirm(\'Data will be deleted permanenty\')" >Delete</button>';
		echo '</td>';
		echo '</tr></form>';
		echo '</table>';
		
	$myStr = ob_get_contents();
	ob_end_clean();
	return $myStr;
//		$count++;
	

//	echo '</table>';
}

function view_table_experience_js($link)
{
	//Designation 	Type 	Department 	Name of Institution 	From - To - Total

	$sql='select * from staff_movement where staff_id=\''.$_SESSION['login'].'\' order by `from_date`';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	
	echo '<tr style="background-color:white;">
						  
						  <th>E</th><th align=left width="10%" >Designation</th>
						  <th align=left width="10%">Type</th>
						  <th align=left width="20%">Department</th>
						  <th align=left width="30%">Name of Institution</th>
						  <th align=left width="30%">From-To-Total</th>
					 </tr>';
	
	while($ra=mysqli_fetch_assoc($result))
	{		
		if(strlen($ra['to_date'])==0)
		{
			$to_date='<span style="background-color:lightpink;">till_date</span>';
			$diff=get_date_diff_as_ymd($ra['from_date'],date('Y-m-d'));
		}
		else
		{
			$to_date=mysql_to_india_date($ra['to_date']);
			$diff=get_date_diff_as_ymd($ra['from_date'],$ra['to_date']);
		}

		$jsid='mid_'.$ra['movement_id'];		
		$raw_html='   
				<tr style="background-color:lightgray;">
				<th title="Edit this exterience" onclick="showhide(\''.$jsid.'\');"><img height=35 src="../image/edit.png"/></th>
				<td>
				'.$ra['post'].'
				</td>
				<td  >'.$ra['type'].'</td>
				<td  >'.$ra['department'].'</td>
				<td  >'.$ra['institute'].'</td>
				<td>'.mysql_to_india_date($ra['from_date']).','.$ra['from_time'].'>>'.$to_date.','.$ra['to_time'].' ('.$diff.')</td>
				<tr><td colspan=10>'.edit_single_experience($link,$ra['movement_id']).'</td></tr>';
				
		
		echo $raw_html;
	}
}


if(isset($_POST['action']))
{
		if($_POST['action']=='add')
		{
			insert($link);
		}
		if($_POST['action']=='update')
		{
			update($link);
		}		
		if($_POST['action']=='delete')
		{
			del($link);
		}		
}	

//echo '<h3 style="background-color:white;display:inline;color:red;">On left side is facity to <span style="color:blue;margin:2px;">edit/delete </span> entries. On right side is facility to <span style="color:blue;margin:2px;">add new entry.</span></h3><br><br>';

	echo '<form style="display:inline;" method=post action=new.php><button style="background-color:lightblue" class="menub" >
	<h4 style="margin: 0px; padding: 0px;" >Return to Declaration Form</h4></button></form></th>';
echo '<table><tr><td style="vertical-align:top">';
//		edit_staff($link);
//echo '</td><td style="vertical-align:top">';

		
//		echo '<table class=border>';
//		echo '<tr><th style="background-color:lightgreen;" colspan=10 >Summary of experience</th></tr>';
		view_table_experience_js($link);
//		echo '</table>';
echo '</td></tr></table>';

echo '<table><th title="Add new experience" onclick="showhide(\'add\');"><img height=35 src="../image/add.png"/></th></table>';
add_staff($link);

?>


