<?php


require_once 'menu.php';
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

function check_office_user($link,$u,$p)
{
	$sql='select * from office_staff where id=\''.$u.'\'';
	//echo $sql;
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	$result_array=mysqli_fetch_assoc($result);
	//print_r($result_array);
	//echo md5($p);
	if(md5($p)==$result_array['password'])
	{
	//echo 'ok';
	return true;
	}
	else
	{
	//echo ' not ok';
	return false;
	}
}

function connect_office()
{
if(!$link=login_varify())
{
	echo 'database login could not be verified<br>';
	logout("message=database login could not be verified");
	exit();
	}
	if(!select_database($link))
	{
	echo 'database could not be selected<br>';
	logout("message=database could not be selected");
	exit();
	}
	if(!check_office_user($link,$_SESSION['login'],$_SESSION['password']))
	{
	echo 'application user could not be varified<br>';
	logout("message=Wrong Password");
	exit();
	}
	return $link;
}
function logout($message='')
{
	session_start(); //Start the current session
	//$GLOBALS['rootpath']."/index.php";
	session_destroy(); //Destroy it! So we are logged out now	
	header("location:".$GLOBALS['rootpath']."/index.php?".$message); //configure absolute path of this file for access from anywhere
}
///////////////////////////////////
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

function mk_select_from_table($link,$field,$disabled,$default)
{
	$sql='select `'.$field.'` from '.$field;
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	
		echo '<select  '.$disabled.' name='.$field.'>';
		while($result_array=mysqli_fetch_assoc($result))
		{
		if($result_array[$field]==$default)
		{
			echo '<option selected  > '.$result_array[$field].' </option>';
		}
		else
			{
				echo '<option  > '.$result_array[$field].' </option>';
			}
		}
		echo '</select>';	
		return TRUE;
}


function mk_select_from_sql($link,$sql,$field_name,$form_name,$disabled,$default)
{

	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	
		echo '<select  '.$disabled.' name='.$form_name.' id='.$form_name.'>';
		while($result_array=mysqli_fetch_assoc($result))
		{
		if($result_array[$field_name]==$default)
		{
			echo '<option selected  > '.$result_array[$field_name].' </option>';
		}
		else
			{
				echo '<option  > '.$result_array[$field_name].' </option>';
			}
		}
		echo '</select>';	
		return TRUE;
}

function mk_select_from_array($ar,$form_name,$disabled,$default)
{

		echo '<select  '.$disabled.' name='.$form_name.' id='.$form_name.'>';
		foreach($ar as $value)
		{
			if($value==$default)
		{
			echo '<option selected  > '.$value.' </option>';
		}
		else
			{
				echo '<option  > '.$value.' </option>';
			}
		}
		echo '</select>';	
		return TRUE;
}

function mk_select_from_sql_with_separate_id($link,$sql,$field_name,$form_name,$id_name,$disabled,$default)
{

	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	
		echo '<select  '.$disabled.' name='.$form_name.' id='.$id_name.'>';
		while($result_array=mysqli_fetch_assoc($result))
		{
		if($result_array[$field_name]==$default)
		{
			echo '<option selected  > '.$result_array[$field_name].' </option>';
		}
		else
			{
				echo '<option  > '.$result_array[$field_name].' </option>';
			}
		}
		echo '</select>';	
		return TRUE;
}


function combo_entry($link,$sql,$name,$disabled,$default)
{
	echo '<table><tr><td>';
	mk_select_from_sql($link,$sql,$name,$disabled,$default);
	echo '</td><td>';
	echo '<input type=text name=\'i_'.$name.'\'>';
	echo '<input type=checkbox name=\'ck_'.$name.'\'>';
	echo '</td></tr></table>';
	
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


function update_field_by_id($link,$table,$id_field,$id_value,$field,$value)
{
	$sql='update `'.$table.'` set `'.$field.'`=\''.$value.'\' where `'.$id_field.'`=\''.$id_value.'\'';
	//echo $sql;
	
	
	if(!$result=mysqli_query($link,$sql)){mysql_error();return FALSE;}
	else
	{
		return mysqli_affected_rows($link);
	}
}

function delete_raw_by_id($link,$table,$id_field,$id_value)
{
	$sql='delete from `'.$table.'` where `'.$id_field.'`=\''.$id_value.'\'';
	//echo $sql;
	
	
	if(!$result=mysqli_query($link,$sql)){mysql_error();return FALSE;}
	else
	{
		return mysqli_affected_rows($link);
	}
}

function update_or_insert_field_by_id($link,$table,$id_field,$id_value,$field,$value)
{
	if(get_raw($link,'select `'.$id_field.'` from `'.$table.'` where `'.$id_field.'`=\''.$id_value.'\'')===FALSE)
	{
		//Try to insert
		$sqli='insert into `'.$table.'` (`'.$id_field.'`,`'.$field.'`) values (\''.$id_value.'\', \''.$value.'\')';
		//echo $sqli;
		if(!$resulti=mysqli_query($link,$sqli)){echo mysqli_error($link);return FALSE;}
		else
		{
			return mysqli_affected_rows($link);
		}
	}
	else
	{
		//Else update
		$sql='update `'.$table.'` set `'.$field.'`=\''.$value.'\' where `'.$id_field.'`=\''.$id_value.'\'';
		//echo $sql;
		if(!$result=mysqli_query($link,$sql))
		{
			echo mysqli_error($link);
			return FALSE;
		}
	}
}

function insert_field_by_id($link,$table,$id_field,$id_value,$field,$value)
{
		//Try to insert
		$sqli='insert into `'.$table.'` (`'.$id_field.'`,`'.$field.'`) values (\''.$id_value.'\', \''.$value.'\')';
		//echo $sqli;
		if(!$resulti=mysqli_query($link,$sqli)){echo mysqli_error($link);return FALSE;}
		else
		{
			return mysqli_insert_id($link);
		}
}

function update_or_insert_filename_field_by_id($link,$table,$id_field,$id_value,$field,$value)
{
	if(strlen($value)>0)
	{
		if(get_raw($link,'select `'.$id_field.'` from `'.$table.'` where `'.$id_field.'`=\''.$id_value.'\'')===FALSE)
		{
			//Try to insert
			$sqli='insert into `'.$table.'` (`'.$id_field.'`,`'.$field.'`) values (\''.$id_value.'\', \''.$value.'\')';
			echo $sqli;
			if(!$resulti=mysqli_query($link,$sqli)){echo mysqli_error($link);return FALSE;}
			else
			{
				return mysqli_affected_rows($link);
			}
		}
		else
		{
			//Else update
			$sql='update `'.$table.'` set `'.$field.'`=\''.$value.'\' where `'.$id_field.'`=\''.$id_value.'\'';
			//echo $sql;
			if(!$result=mysqli_query($link,$sql))
			{
				echo mysqli_error($link);
				return FALSE;
			}
		}
	}
}



function india_to_mysql_date($ddmmyyyy)
{
	$ex=explode('-',$ddmmyyyy);
	if(count($ex)==3)
	{
		return $ex[2].'-'.$ex[1].'-'.$ex[0];
	}
	else
	{
		return false;
	}
}

function mysql_to_india_date($yyyymmdd)
{
	$ex=explode('-',$yyyymmdd);
	if(count($ex)==3)
	{
		return $ex[2].'-'.$ex[1].'-'.$ex[0];
	}
	else
	{
		return false;
	}
}

function date_diff_to_year_month_days($from,$to)
{
	//dates as yyyy-mm-dd format only
	//To    2016-03-04
	//From  2015-05-20
	//      0000-09-(N) 
	
	$exf=explode('-',$from);
	$ext=explode('-',$to);
	if(count($exf)!=3||count($ext)!=3)
	{
		return false;
	}
	
	if(in_array('00',$exf)===TRUE || in_array('0000',$exf)===TRUE)
	{
		//print_r($exf);
		return false;
	}
	
	$days_of_from_month=cal_days_in_month(CAL_GREGORIAN,$exf[1],$exf[0]);
	if($days_of_from_month===FALSE)
	{
		return FALSE;
	}
	$days=$ext[2]+($days_of_from_month-$exf[2]);
	
	
	$months=$ext[1]+12-$exf[1]-1;
	
	$years=$ext[0]-$exf[0]-1;
	
	if($days>cal_days_in_month(CAL_GREGORIAN,$exf[1],$exf[0])){$days=abs($ext[2]-$exf[2]);$months=$months+1;}
	if($months>11){$years=$years+1;$months=$months-12;}
	
	//echo "<h1>".$to." and ".$from."</h1>";
	//echo "<h1>".$years.",".$months.",".$days."</h1>";
	
	return $years." yr, ".$months." mo, ".$days." d";
/*
	$y=$ext[0]-$exf[0];

	$m=$ext[1]-$exf[1];
	if($m<0){$y=$y-1;$m=12+$m;}
	
	$d=$ext[1]-$exf[1];
	if($d<0){$m=$m-1;$d=cal_days_in_month(CAL_GREGORIAN,$exf[1],$exf[0])-$d;}
	
	if($m<0){$y=$y-1;$m=12+$m;}
	
	echo "<h1>".$to." and ".$from."</h1>";
	echo "<h1>".$y.",".$m.",".$d."</h1>";
*/
}

//functions for file upload management//////////////

function file_to_str($link,$file)
{
	$fd=fopen($file['tmp_name'],'r');
	$size=$file['size'];
	$str=fread($fd,$size);
	return mysqli_real_escape_string($link,$str);
}

function insert_attachment($link,$table,$id_field,$id_value,$files_field,$files_value)
{
	$str=file_to_str($link,$files_value);

	$sql='insert into `'.$table.'` 
			(`'.$id_field.'`,`'.$files_field.'`) values(\''.$id_value.'\',"'.$str.'")';

		
	if(!$result=mysqli_query($link,$sql))
	{		
		//echo 'Error()';
		echo mysqli_error($link);
	}
	else
	{
		//echo 'insert success';
		return mysqli_insert_id($link);
	}
}	

function read_year($name,$y,$yy)
{
	echo '<select name=\''.$name.'\'>';
	for($i=$y;$i<$yy;$i++)
	{
			echo '<option>'.$i.'</option>';
	}
	echo '</select>';
	
}

function update_or_insert_attachment($link,$table,$id_field,$id_value,$files_field,$files_value)
{	
	//echo '<pre>'; print_r( $files_value);echo '</pre>';
	if($files_value['size']>0)
	{
		if(get_raw($link,'select `'.$id_field.'` from `'.$table.'` where `'.$id_field.'`=\''.$id_value.'\'')===FALSE)
		{
		//insert

			$str=file_to_str($link,$files_value);

			$sql='insert into `'.$table.'` 
					(`'.$id_field.'`,`'.$files_field.'`) values(\''.$id_value.'\',"'.$str.'")';

				
			if(!$result=mysqli_query($link,$sql))
			{		
				//echo 'Error()';
				echo mysqli_error($link);
			}
			else
			{
				//echo 'insert success';
				return mysqli_insert_id($link);
			}
		}
		//update
		else
		{
			$str=file_to_str($link,$files_value);
			$sql='update `'.$table.'` set 
					`'.$files_field.'` ="'.$str.'"
					where
					`'.$id_field.'` =\''.$id_value.'\'';
			//echo $sql;
			if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);}
			else
			{
				//echo 'update success';
				return $id_value;
			}			
		}
	}
}



function find_primary_key_array($link,$table)
{
	//This function is useful when primary key is madeup of multiple fields
	$sql_p='SHOW KEYS FROM  `'.$table.'` WHERE Key_name = \'PRIMARY\'';
	if(!$result_p=mysqli_query($link,$sql_p)){echo mysqli_error($link);return FALSE;}
	$pk=array();
	while($array_p=mysqli_fetch_assoc($result_p))
	{
		$pk[]=$array_p['Column_name'];
	}
	return $pk;
}

function read_primary_key($parray,$array)
{
	$ret_array=array();
	foreach($parray as $key=>$value)
	{
		$ret_array[$value]=$array[$value];
	}
	return $ret_array;
}

function prepare_where($ar)
{		
		if(count($ar)>0)
		{
			$where=' where ';
			foreach($ar as $k=>$v)
			{
				$where=$where.'`'.$k.'`='.'\''.$v.'\' and ';
			}
			$where=substr($where,0,-4);
		}
		else
		{
			$where='';
		}
		//echo '<h3>'.$where.'</h3>';
		return $where;
}


function display_photo($link,$photo)
{
		//if($ar['lng']>0)
		//{
			echo '<img style="width:3cm;height:4cm;" src="data:image/jpeg;base64,'.base64_encode($photo).'"/>';
		//}
		//else
		//{
		//	echo 'RECENT PHOTOGRAPH TO BE COUNTER SIGNED BY  THE DEAN/ PRINCIPAL';
		//}
}


function if_in_interval($dt,$from_dt,$to_dt)
{


	//f d t
	if(strtotime($dt)-strtotime($from_dt)>=0 && strtotime($dt)-strtotime($to_dt)<=0)
	{
		return 0;
	}
	
	//d f t
	elseif(strtotime($dt)-strtotime($from_dt)<0 && strtotime($dt)-strtotime($to_dt)<0)
	{
		return -1;
	}

	//f t d
	elseif(strtotime($dt)-strtotime($from_dt)>0 && strtotime($dt)-strtotime($to_dt)>0)
	{
		return 1;
	}
	
	//t f is illogical
	else
	{
		return FALSE;
	}
		
	/*

	$dtt=date_create($dt);
	$from_dtt=date_create($from_dt);
	$to_dtt=date_create($to_dt);
	
	$diff_from=date_diff($dtt,$from_dtt);
	print_r($diff_from);
	
	$diff_to=date_diff($dtt,$to_dtt);
	print_r($diff_to);	
	*/
	
}

function date_diff_grand($from_dt,$to_dt)
{
	$from_dtt=date_create($from_dt);
	$to_dtt=date_create($to_dt);
	
	$diff=date_diff($from_dtt,$to_dtt);
	
	//echo '<pre>';
	//print_r($diff);
	//echo '</pre>';
	
	return $diff;

}

function get_date_diff_as_ymd($from_dt,$to_dt)
{
	$diff=date_diff_grand($from_dt,$to_dt);
	return date_interval_format($diff,'%r%Y y,%r%M m,%r%D d');
}


function get_experience_good($link,$id)
{

	$sql='select * from staff_movement where staff_id=\''.$id.'\' order by `from_date`';
	echo '<table border=1>';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	
	while($ra=mysqli_fetch_assoc($result))
	{
		$raw_data[]=$ra;
	}

	foreach($raw_data as $key=>$value)
	{
		$first_summary[]=array('department'=>$value['department'],'post'=>$value['post'],'from_date'=>$value['from_date'],'to_date'=>$value['to_date']);
	}
	
	$num=count($first_summary);

	$mrg=array();
	$final_count=0;
	for($i=0;$i<$num;$i++)
	{
		if(count($mrg)==0)
		{
				$mrg[$final_count]=array('department'=>$first_summary[$i]['department'],'post'=>$first_summary[$i]['post'],
								'from_date'=>$first_summary[$i]['from_date'], 
								'to_date'=>$first_summary[$i]['to_date']);					
		}
		else
		{
			if($first_summary[$i]['department']==$mrg[$final_count]['department'] 
			&& $first_summary[$i]['post']==$mrg[$final_count]['post'])
			{
				$mrg[$final_count]['to_date']=$first_summary[$i]['to_date'];
			}
			else
			{
				$final_count++;
				$mrg[$final_count]=array('department'=>$first_summary[$i]['department'],'post'=>$first_summary[$i]['post'],
								'from_date'=>$first_summary[$i]['from_date'],
								'to_date'=>$first_summary[$i]['to_date']);				
			}
		}
		
	}
	return $mrg;
	//print_r($mrg);
}


function get_experience_mci($link,$id)
{

	$sql='select * from staff_movement where staff_id=\''.$id.'\' order by `from_date`';
	echo '<table border=1>';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	
	while($ra=mysqli_fetch_assoc($result))
	{
		$raw_data[]=$ra;
	}

	foreach($raw_data as $key=>$value)
	{
		$first_summary[]=array('institute'=>$value['institute'],'department'=>$value['department'],'post'=>$value['post'],'from_date'=>$value['from_date'],'to_date'=>$value['to_date']);
	}
	
	//echo '<pre>';
	//print_r($first_summary);
	$num=count($first_summary);

	$mrg=array();
	$final_count=0;
	for($i=0;$i<$num;$i++)
	{
		if(count($mrg)==0)
		{
				$mrg[$final_count]=array(
							'institute'=>$first_summary[$i]['institute'],
							'department'=>$first_summary[$i]['department'],
							'post'=>$first_summary[$i]['post'],
							'from_date'=>$first_summary[$i]['from_date'], 
							'to_date'=>$first_summary[$i]['to_date']);					
		}
		else
		{
			if($first_summary[$i]['department']==$mrg[$final_count]['department'] 
			&& $first_summary[$i]['post']==$mrg[$final_count]['post']
			&& $first_summary[$i]['institute']==$mrg[$final_count]['institute'])
			{
				$mrg[$final_count]['to_date']=$first_summary[$i]['to_date'];
			}
			else
			{
				$final_count++;
				$mrg[$final_count]=array(
								'institute'=>$first_summary[$i]['institute'],
								'department'=>$first_summary[$i]['department'],
								'post'=>$first_summary[$i]['post'],
								'from_date'=>$first_summary[$i]['from_date'],
								'to_date'=>$first_summary[$i]['to_date']);				
			}
		}
		
	}
	return $mrg;
	//print_r($mrg);
}



?>
