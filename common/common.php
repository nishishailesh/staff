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

/*
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
*/

//New with MD5 to encrypt transition
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

function mk_combo($link,$sql,$field_name,$form_name,$id_name,$disabled,$default)
{
	$selected='no';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
		echo '<table class=border><tr><td>';
		echo '<select  '.$disabled.' name='.$form_name.' id='.$id_name.'>';
		while($result_array=mysqli_fetch_assoc($result))
		{
			if($result_array[$field_name]==$default)
			{
				echo '<option selected  > '.$result_array[$field_name].' </option>';
				$selected='yes';
			}
			else
			{
				echo '<option  > '.$result_array[$field_name].' </option>';
			}
		}
		echo '</select></td><td>';
		
		if($selected=='no'){
								echo 'tick if not in List:<input type=checkbox checked
								id=\''.$id_name.'_text_check\' name=\''.$form_name.'_text_check\' >';
								echo '</td><td><input type=text name=\''.$form_name.'_text\'  value=\''.$default.'\' >';
								
							}
		else
			{
				echo 'Tick if not in List:<input type=checkbox 
								id=\''.$id_name.'_text_check\' name=\''.$form_name.'_text_check\' >';
				echo '</td><td><input type=text      		 name=\''.$form_name.'_text\' >';
			}
		echo '</td></tr></table>';
		return TRUE;
}


function mk_combo_new($link,$sql,$field_name,$form_name,$id_name,$disabled,$default)
{
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
		echo '<table class=border><tr><td>';
		echo '<select  '.$disabled.' name='.$form_name.' id='.$id_name.'>';
		while($result_array=mysqli_fetch_assoc($result))
		{
				echo '<option  > '.$result_array[$field_name].' </option>';
		}
		echo '</select></td><td>';
		
		echo 'tick if not in List:<input type=checkbox
								id=\''.$id_name.'_text_check\' name=\''.$form_name.'_text_check\' >';	
		
		echo '</td><td><input type=text name=\''.$form_name.'_text\' placeholder="write here if not in list">';
		echo '</td></tr></table>';
		return TRUE;
}
function mk_combo_new1($link,$sql,$field_name,$form_name,$id_name,$disabled,$default)
{
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
		echo '<table class=border><tr><td>';
		echo '<select  '.$disabled.' name='.$form_name.' id='.$id_name.'>';
		while($result_array=mysqli_fetch_assoc($result))
		{
				echo '<option  > '.$result_array[$field_name].' </option>';
		}
		echo '</select></td>';
	    echo '</tr></table>';
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
	
	
	if(!$result=mysqli_query($link,$sql)){mysqli_error($link);return FALSE;}
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
	for($i=$y;$i<=$yy;$i++)
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

function view_table_experience($link)
{
	//Designation 	Type 	Department 	Name of Institution 	From - To - Total

	$sql='select * from staff_movement where staff_id=\''.$_SESSION['login'].'\' order by `from_date`';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
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
			
	$raw_html='<tr style="background-color:lightgray;">
			<td>
			'.$ra['post'].'
			</td>
			<td  >'.$ra['type'].'</td>
			<td  >'.$ra['department'].'</td>
			<td  >'.$ra['institute'].'</td>
			<td>'.mysql_to_india_date($ra['from_date']).','.$ra['from_time'].'>>'.$to_date.','.$ra['to_time'].' ('.$diff.')</td>
			';
			echo $raw_html;
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

//when user reach here, encrypt is already functioning
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
	
	if(password_verify($password,$array['epassword']))
	//if(MD5($password)==$array['password'])
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

	//$eDate = date('Y-m-d');
    //$eDate = date('Y-m-d', strtotime("+6 months", strtotime($eDate)));
    $eDate = date('Y-m-d', strtotime("+12 months"));
    // echo $eDate;	
	$sqli="update staff set epassword='".password_hash($new_password,PASSWORD_BCRYPT)."',expirydate='$eDate' where id='$user'";	
	$user_pwd=run_query($link,'staff',$sqli);
	if($user_pwd>0)
	{
		return true;	
	}
	else
	{
		return false;	
	}
}

function is_valid_password($pwd){
// accepted password length minimum 8 its contain lowerletter,upperletter,number,special character.
    if (preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{8,}$/", $pwd))
   {
	  // $msgpwd='<p>contain at least one lowercase letter, one uppercase letter, one numeric digit, and one special character at least 8 or more characters</p>';
   
        return true;
	}
    else{
		
        return false;
	}
}

function read_password()
{
	echo '<center></center><table border=1 class="style2" style="background-color:lightgray;margin:10px;padding:20px;"><form method=post>';
	echo '<tr><th colspan=2 class="head">Change Password for access to Staff Database</th></tr>';
	echo '<tr><td>Login ID</td>	<td><input readonly=yes type=text name=id value=\''.$_SESSION['login'].'\'></td></tr>'; 
	echo '<tr><td>Old Password</td>	<td><input style="width:100%;" type=password name=old_password></td></tr>';
		echo '<tr><td>New Password</td>	<td><input style="width:100%;" type=password name=password_1  
			pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{8,}" 
			title=" contain at least one lowercase letter, one uppercase letter, one numeric digit, and one special character at least 8 or more characters" required>
			</td></tr>';
	echo '<tr><td>Repeat New Password</td>	<td><input  style="width:100%;" type=password name=password_2></td></tr>';
	echo '<tr><td colspan=2 align=center><button  style="background-color:lightgreen"class="menub"  type=submit name=action value=change_password>Change Password</button></td></tr>';
	echo '</form></table>';
	echo '	<table width="65%" border="2" style="background-color:white;margin:10px;padding:30px;">
			<tr><th colspan=3>Password Hints</th></tr>
			<tr><td>iamgood</td><td>Unacceptable</td><td>No capital, no number, no special character, less than 8</td></tr>
			<tr><td>Iamgood007</td><td>Unacceptable</td><td>no special character</td></tr>
			<tr><td>Iamgood007$</td><td>Acceptable</td><td>special characters-> ! @ # $ % ^ & * ( ) _ - += { [ } ] | \ / < , > . ; : " \'</td></tr>
            </table>';
            
echo
'<br><table  border=1 class="style2"  style="background-color:white;margin:10px;padding:30px;color:black;" >
		<tr><td>Change password frequently</td></tr>
		<tr><td>Donot reveal your password to anybody.</td></tr>
		<tr><td>If your colleague needs access, ask them to request appropriate authority</td></tr>
		<tr><td>If you can not access your account, request appropriate authority</td></tr>
 </table>';            
}

function expirydate($link,$d,$u)
{
     $sql='select * from staff where id=\''.$u.'\'';
     $result_ld=run_query($link,'staff',$sql);
     $row_ld=get_raw($link,$sql);
     $t_name=$row_ld['expirydate'];
     //$t_username=$row_ld['fullname'];
     //echo $t_name;
     return $t_name ;

 }

?>
