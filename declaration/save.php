<?php
//session_start();
require_once '../common/common.php';
/*
echo '<pre>';
print_r($GLOBALS);
echo '</pre>';
*/


function save($link)
{
	//following exist for all users. So only update is required
	//Staff(fullname,dob)
	update_field_by_id($link,'staff','id',$_POST['id'],'fullname',$_POST['name']);
	//Removed, saved from exterience table
	//update_field_by_id($link,'staff','id',$_POST['id'],'department',$_POST['present_department']);
	update_field_by_id($link,'staff','id',$_POST['id'],'dob',india_to_mysql_date($_POST['dob']));
	update_field_by_id($link,'staff','id',$_POST['id'],'residencial_address',rtrim($_POST['residencial_address']));

	//following may not be existing for a given user, so update, if it fail, insert is required
	//photo and photo id
	update_or_insert_field_by_id($link,'photo','id',$_POST['id'],'proof_type',$_POST['photo_id']);
	update_or_insert_field_by_id($link,'photo','id',$_POST['id'],'proof_number',$_POST['photo_id_number']);
	update_or_insert_field_by_id($link,'photo','id',$_POST['id'],'proof_issued_by',$_POST['photo_id_issued_by']);

	update_or_insert_attachment($link,'photo','id',$_POST['id'],'photo_id',$_FILES['photo_id']);
	update_or_insert_filename_field_by_id($link,'photo','id',$_POST['id'],'photo_id_filename',$_FILES['photo_id']['name']);

	update_or_insert_attachment($link,'photo','id',$_POST['id'],'photo',$_FILES['photo']);
	update_or_insert_filename_field_by_id($link,'photo','id',$_POST['id'],'photo_filename',$_FILES['photo']['name']);

	update_or_insert_attachment($link,'photo','id',$_POST['id'],'photo',$_FILES['photo']);
	update_or_insert_filename_field_by_id($link,'photo','id',$_POST['id'],'photo_filename',$_FILES['photo']['name']);

	/////appointment order upload
	$staff_movement_raw=get_raw($link,'select * from staff_movement where staff_id=\''.$_POST['id'].'\' and to_date is null');
	//upload only if appointment entered
	if($staff_movement_raw)
	{
		//try only if upload supplied
		if($_FILES['present_appointment_order']['size']>0)
		{
			$sql_att='select * from staff_movement_attachment where movement_id=\''.$staff_movement_raw['movement_id'].'\' and type=\'appointment_order\'';		
			if(!$result_att=mysqli_query($link,$sql_att)){echo mysqli_error($link);return FALSE;}
			//update  if raw found
			if($array_att=mysqli_fetch_assoc($result_att))
			{
				$pk=find_primary_key_array($link,'staff_movement_attachment');
				$pk_result=read_primary_key($pk,$array_att);
				$wr=prepare_where($pk_result);
				//echo 'trying update';
				$sql_update_att='update staff_movement_attachment set 
									attachment=\''.file_to_str($link,$_FILES['present_appointment_order']).'\',
									attachment_filename=\''.$_FILES['present_appointment_order']['name'].'\' '.$wr;
									
				if(!$result_update_att=mysqli_query($link,$sql_update_att)){echo mysqli_error($link);}
				else
				{
					//echo 'update success';
				}
									
			}
			//insert if no raw found	
			else
			{
				$sql_insert_att='insert into staff_movement_attachment (movement_id,type,attachment,attachment_filename)
								 values(
									\''.$staff_movement_raw['movement_id'].'\',
									\'appointment_order\',
									\''.file_to_str($link,$_FILES['present_appointment_order']).'\',
									\''.$_FILES['present_appointment_order']['name'].'\'
								)';
						
				if(!$result=mysqli_query($link,$sql_insert_att))
				{		
					echo mysqli_error($link);
				}
				else
				{
					//echo 'insert success';
				}
			}
		}	
	}
	else
	{
		//echo 'Please current appointment details in experience table (3.1a), before uploading current appointment order ';
	}																				


	update_or_insert_field_by_id($link,'last_mci','id',$_POST['id'],'last_mci_date',
								india_to_mysql_date($_POST['last_mci_date']));	
	update_or_insert_field_by_id($link,'last_mci','id',$_POST['id'],'same_institute',
								$_POST['appeared_in_same_institute']);	
	update_or_insert_field_by_id($link,'last_mci','id',$_POST['id'],'same_designation',
								$_POST['appeared_with_same_designation']);

	update_or_insert_attachment($link,'residencial_address_proof','id',$_POST['id'],'proof',$_FILES['proof_of_residence']);
	update_or_insert_filename_field_by_id($link,'residencial_address_proof',
											'id',$_POST['id'],'filename',$_FILES['proof_of_residence']['name']);


	if($_POST['MET']=='yes')
	{
		update_or_insert_field_by_id($link,'met','id',$_POST['id'],'center',
									$_POST['met_center']);
		update_or_insert_field_by_id($link,'met','id',$_POST['id'],'place',
									$_POST['met_place']);
		update_or_insert_field_by_id($link,'met','id',$_POST['id'],'observer',
									$_POST['met_observer']);
		update_or_insert_field_by_id($link,'met','id',$_POST['id'],'date',
									india_to_mysql_date($_POST['met_date']));
	}
	elseif($_POST['MET']=='no')
	{
		delete_raw_by_id($link,'met','id',$_POST['id']);
	}


	update_field_by_id($link,'staff','id',$_POST['id'],'residencial_phone',$_POST['residencial_phone']);
	update_field_by_id($link,'staff','id',$_POST['id'],'office_phone',$_POST['office_phone']);
	update_field_by_id($link,'staff','id',$_POST['id'],'mobile',$_POST['mobile']);
	update_field_by_id($link,'staff','id',$_POST['id'],'email',$_POST['email']);


/////joining order upload////////////////
	
	//$staff_movement_raw is from above
	if($staff_movement_raw)
	{
		//try only if upload supplied
		if($_FILES['present_joining_order']['size']>0)
		{
			$sql_att='select * from staff_movement_attachment where movement_id=\''.$staff_movement_raw['movement_id'].'\' and type=\'joining_order\'';		
			if(!$result_att=mysqli_query($link,$sql_att)){echo mysqli_error($link);return FALSE;}
			//update  if raw found
			if($array_att=mysqli_fetch_assoc($result_att))
			{
				$pk=find_primary_key_array($link,'staff_movement_attachment');
				$pk_result=read_primary_key($pk,$array_att);
				$wr=prepare_where($pk_result);
				//echo 'trying update';
				$sql_update_att='update staff_movement_attachment set 
									attachment=\''.file_to_str($link,$_FILES['present_joining_order']).'\',
									attachment_filename=\''.$_FILES['present_joining_order']['name'].'\' '.$wr;
									
				if(!$result_update_att=mysqli_query($link,$sql_update_att)){echo mysqli_error($link);}
				else
				{
					//echo 'update success';
				}
									
			}
			//insert if no raw found	
			else
			{
				$sql_insert_att='insert into staff_movement_attachment (movement_id,type,attachment,attachment_filename)
								 values(
									\''.$staff_movement_raw['movement_id'].'\',
									\'joining_order\',
									\''.file_to_str($link,$_FILES['present_joining_order']).'\',
									\''.$_FILES['present_joining_order']['name'].'\'
								)';
						
				if(!$result=mysqli_query($link,$sql_insert_att))
				{		
					echo mysqli_error($link);
				}
				else
				{
					//echo 'insert success';
				}
			}
		}	
	}
	else
	{
		//echo 'Please current appointment details in experience table (3.1a), before uploading current appointment order ';
	}																				
////////////joining order section ends here//////////////////

///////////Qualification management///////////
/*
	[qualification_degree] => MBBS
    [qualification_subject] => Anatomy
    [college_qualification] => 
    [university_qualification] => 
    [year_qualification] => 
    [reg_no_qualification] => 
    [reg_date_qualification] => 
    [council_qualification] => 
    [action] => qualification

*/
if(isset($_POST['action']))
	{
		if($_POST['action']=='add_qualification')
		{
			if(	strlen($_POST['college_qualification'])>0 &&
				strlen($_POST['university_qualification'])>0 &&
				strlen($_POST['year_qualification'])>0
				)
			{
				$q_sql='insert into qualification values (
							\'\',
							\''.$_SESSION['login'].'\',
							\''.$_POST['qualification_degree'].'\',
							\''.$_POST['qualification_subject'].'\',
							\''.$_POST['college_qualification'].'\',
							\''.$_POST['university_qualification'].'\',
							\''.$_POST['year_qualification'].'\',
							\''.$_POST['reg_no_qualification'].'\',
							\''.india_to_mysql_date($_POST['reg_date_qualification']).'\',
							\''.$_POST['council_qualification'].'\')';
				///echo $q_sql;
				if(!$result=mysqli_query($link,$q_sql))
				{		
					echo mysqli_error($link);
				}
				else
				{
					$insert_id=mysqli_insert_id($link);
					////upload degree
					if($_FILES['file_qualification_degree']['size']>0)
					{
						$sql_q_a='insert into qualification_attachment
									(attachment_id,qualification_id,type,attachment,attachment_filename)
									 values(
										\'\',
										\''.$insert_id.'\',
										\'degree_certificate\',
										\''.file_to_str($link,$_FILES['file_qualification_degree']).'\',
										\''.$_FILES['file_qualification_degree']['name'].'\'
									)';
							
						if(!$result=mysqli_query($link,$sql_q_a))
						{		
							echo mysqli_error($link);
						}
						else
						{
							//echo 'insert success';
						}
					}
					
					///upload registration				
					if($_FILES['file_qualification_reg']['size']>0)
					{
						$sql_q_a='insert into qualification_attachment
									(attachment_id,qualification_id,type,attachment,attachment_filename)
									 values(
										\'\',
										\''.$insert_id.'\',
										\'reg_certificate\',
										\''.file_to_str($link,$_FILES['file_qualification_reg']).'\',
										\''.$_FILES['file_qualification_reg']['name'].'\'
									)';
							
						if(!$result=mysqli_query($link,$sql_q_a))
						{		
							echo mysqli_error($link);
						}
						else
						{
							//echo 'insert success';
						}
					}
				}	
				
			}
		}
	}

if(isset($_POST['delete_qualification']))
{
			$d_sql='delete from qualification where qualification_id=\''.$_POST['delete_qualification'].'\'';
			if(!$result=mysqli_query($link,$d_sql))
			{		
				echo mysqli_error($link);
			}
			else
			{
				$da_sql='delete from qualification_attachment where qualification_id=\''.$_POST['delete_qualification'].'\'';
				if(!$result=mysqli_query($link,$da_sql))
				{		
					echo mysqli_error($link);
				}
			}	
}	
///////////Qualification management ends here//////////////

///////////Experience management///////////
/*
   [experience_designation] => Assistant Professor
    [experience_type] => Adhoc
    [experience_department] => N/A
    [experience_institute] => XXXXXXXXX
    [from_experience] => 01-11-2006
    [from_experience_time] => FN
    [to_experience] => till_date XXXXXXXXX
    [to_experience_time] => AN
    [to_experience_checkbox] => on
    [action] => add_experience

*/

	if(isset($_POST['action']))
	{
		if($_POST['action']=='add_experience')
		{

			if(	(strlen($_POST['experience_institute_text'])>0 || strlen($_POST['experience_institute_select'])>0) &&
				strlen($_POST['from_experience'])>0 &&
				(strlen($_POST['to_experience_text'])>0 || strlen($_POST['to_experience_pk'])>0)
				)
			{

				if(isset($_POST['to_experience_checkbox']))	//which means it is on
				{
					$to_date='NULL';
				}
				else
				{
					$to_date='\''.india_to_mysql_date($_POST['to_experience_pk']).'\'';
				}

				if(isset($_POST['experience_institute_checkbox']))
				{
					$exp_inst=$_POST['experience_institute_text'];
				}
				else
				{
					$exp_inst=$_POST['experience_institute_select'];
				}
								
				$q_sql='insert into staff_movement values (
							\'\',
							\''.$_SESSION['login'].'\',
							\''.$exp_inst.'\',
							\''.$_POST['experience_department'].'\',
							\''.$_POST['experience_designation'].'\',
							\''.india_to_mysql_date($_POST['from_experience']).'\',
							\''.$_POST['from_experience_time'].'\','
							.$to_date.',
							\''.$_POST['to_experience_time'].'\',
							\''.$_POST['experience_type'].'\')';
				///echo $q_sql;
				if(!$result=mysqli_query($link,$q_sql))
				{		
					echo mysqli_error($link);
				}
				else
				{
					$insert_id=mysqli_insert_id($link);
				}	
				
			}
		}
	}


	if(isset($_POST['delete_experience']))
	{
				$d_sql='delete from staff_movement where movement_id=\''.$_POST['delete_experience'].'\'';
				if(!$result=mysqli_query($link,$d_sql))
				{		
					echo mysqli_error($link);
				}
				else
				{
				$da_sql='delete from staff_movement_attachment where movement_id=\''.$_POST['delete_experience'].'\'';
					if(!$result=mysqli_query($link,$da_sql))
					{		
						echo mysqli_error($link);
					}
				}	
	}	
	///////////Experience management ends here//////////////


	return true;
}


?>
