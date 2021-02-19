<?php
session_start();
require_once '../common/common.php';

$link=connect_office();
if(!isset($_POST['experience']))
{
	menu_office();
	echo '<form method=post target=_blank>';
	get_staff_id($link);
	echo '<input type=submit name=experience>';
	echo '</form>';
}


echo '<pre>';
if(isset($_POST['experience']) && isset($_POST['staff_id']))
{

	$staff_detail=get_raw($link,'select * from staff where id=\''.$_POST['staff_id'].'\'');
	
	$ex=get_experience_good($link,$_POST['staff_id']);
	//print_r($ex);	
	
	$ce=get_current_experience($link,$_POST['staff_id']);
	//print_r($ce);	

	$pe=get_experience_good_previous($link,$_POST['staff_id'],$ce['from_date']);
	//print_r($pe);	
	
	$str1='This is to certify that Dr. '
			.$staff_detail['fullname'].' is working as '
			.$ce['post'].' in the department of '
			.$ce['department'].' of this college('.$ce['institute'].') since '.mysql_to_india_date($ce['from_date']) ;
//	echo $str1;
	echo '<br>';
	$str2='Before joining this institute the officer has worked in Medical Colleges 
			under Medical Education Research department, Government of Gujarat as follows.';
	echo '<table border=1>';

echo '<table style="width:20cm;background-color:lightgray;" align=center>';
echo '<tr><td align=center><h1>'.$ce['institute'].'</h1></td></tr>';
echo '<tr><td align=center><h2>Experience Certificate</h2></td></tr>';
echo '<tr><td>'.$str1.'</td></tr>';
if($pe!==false)
{

	echo '<tr><td>'.$str2.'</td></tr>';
	echo '<tr><td>.</td></tr>';
	echo '<tr><td>.</td></tr>';
	echo '<tr><td>.</td></tr>';

	echo '<tr><td><table align=center border=1>';
	foreach($pe as $key=>$value)
	{

                        $ex_dym=get_date_diff_as_ymd($value['from_date'],$value['to_date']);
			//<td>'.$ex_dym.'</td>

		echo '<tr>
			<td>'.$value['department'].'</td>
			<td>'.$value['post'].'</td>
			<td>'.mysql_to_india_date($value['from_date']).'</td>
			<td>'.mysql_to_india_date($value['to_date']).'</td>
			</tr>';
	}
}
	echo '</table></td></tr>';
	echo '<tr><td>.</td></tr>';
	echo '<tr><td>.</td></tr>';
	echo '<tr><td>.</td></tr>';
	echo '<tr><td>.</td></tr>';
	echo '<tr><td><table width="100%">';
	echo '<tr><td width="50%"></td><td>The Dean</td><tr>';
	echo '<tr><td></td><td>'.$ce['institute'].'</td><tr>';
	echo '</table></td></tr>';
	//print_r($pe);
}
echo '</table>';



function get_current_experience($link,$id)
{

	$sql='select * from staff_movement where staff_id=\''.$id.'\' order by `from_date` ';
	echo '<table border=1>';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	
	while($ra=mysqli_fetch_assoc($result))
	{
		$raw_data[]=$ra;
	}

		$institute=null;
	foreach($raw_data as $key=>$value)
	{
		if($institute===null)
		{
			$institute=$value['institute'];
			$post=$value['post'];
			$department=$value['department'];
			$from_date=$value['from_date'];
		}
		else
		{
			if($institute==$value['institute'] &&	$post==$value['post'] && $department==$value['department'])
			{
				
			}
			else
			{
				$institute=$value['institute'];
				$post=$value['post'];
				$department=$value['department'];
				$from_date=$value['from_date'];				
			}
			
		}
	}
		$ar=array('institute'=>$institute,'post'=>$post,'department'=>$department,'from_date'=>$from_date);
		return $ar;
		//print_r($ar);
}


function get_experience_good_previous($link,$id,$date)
{

	$sql='select * from staff_movement where staff_id=\''.$id.'\' and from_date< \''.$date.'\' order by `from_date` ';
	
	//echo $sql;
	
	echo '<table border=1>';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	
	$raw_data=null;
	while($ra=mysqli_fetch_assoc($result))
	{
		$raw_data[]=$ra;
	}

	if($raw_data==null){return false;}

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
			$ddiff=date_diff_grand($mrg[$final_count]['to_date'],$first_summary[$i]['from_date']);
			$days_diff=date_interval_format($ddiff,'%r%a');
			//echo $days_diff.'<br>';
			if($first_summary[$i]['department']==$mrg[$final_count]['department'] 
			&& $first_summary[$i]['post']==$mrg[$final_count]['post']
			&& $days_diff<=1)
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

function get_staff_id($link)
{
	$sql='select id,fullname,institute,department,post from staff,staff_movement
						where 
							staff.id=staff_movement.staff_id
							and
							to_date is null
							order by department,post';
							
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	echo '<select name=staff_id>';
	while($ar=mysqli_fetch_assoc($result))
	{
		echo '<option value=\''.$ar['id'].'\'>'.'['.$ar['department'].']'.'['.$ar['post'].']'.
					$ar['fullname'].'['.$ar['id'].']'.'['.$ar['institute'].']'.'</option>';
	}
	echo '</select>';	
}

?>

