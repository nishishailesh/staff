<?php
session_start();
require_once '../common/common.php';
		//print_r($GLOBALS);


$link=connect();

//////////////////Code for salary//////////

if(isset($_POST['action']))
{
	if($_POST['action']=='View')
	{
		echo '<table align=center><tr><td>';
		display_staff($sal_link,$_POST['staff_id']);
		echo '</td><td>';
		display_bill($sal_link,$_POST['bill_group']);
		echo '</td></tr></table>';
		print_one_nonsalary_slip($sal_link,$_POST['staff_id'],$_POST['bill_group']);
		print_one_salary_slip($sal_link,$_POST['staff_id'],$_POST['bill_group']);
	}
	
	if($_POST['action']=='export')
	{
		$sal_link=mysqli_connect('127.0.0.1',$GLOBALS['main_user'],$GLOBALS['main_pass']);
		mysqli_select_db($sal_link,'c34');
		//print_r($_POST);
		export_annual_salary($sal_link,$_POST['staff_id']);
	}	
	
	exit(0);
}



function display_staff($link,$staff_id)
{
	$sql='select * from staff where staff_id=\''.$staff_id.'\'';

	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	echo '<table align=center class=border style="background-color:#F5D300">
	<tr><th>ID</th><th>Name</th></tr>
	<tr>';
	while($ar=mysqli_fetch_assoc($result))
	{
		echo '<td>'.$ar['staff_id'].'</td>'.
		'<td>'.$ar['fullname'].'</td>';
	}
	echo '</tr></table>';
}

function display_bill($link,$bill_group,$header='yes')
{
	$sql='select * from bill_group where bill_group=\''.$bill_group.'\'';

	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	echo '<table align=center class=border style="background-color:#ADD8E6">';
	if($header=='yes')
	{
		echo '<tr><th>Bill Group</th><th>Prepared on</th> <th>From</th> <th>To</th> <th>Head</th> 
		<th>Type</th><th>Remark</th></tr>
		<tr>';
	}
	while($ar=mysqli_fetch_assoc($result))
	{
		echo '<td>'.$ar['bill_group'].'</td>'.
		'<td>'.$ar['date_of_preparation'].'</td>'.
		'<td>'.$ar['from_date'].'</td>'.
		'<td>'.$ar['to_date'].'</td>'.
		'<td>'.$ar['head'].'</td>'.
		'<td>'.$ar['bill_type'].'</td>'.
		'<td>'.$ar['remark'].'</td>';
	}
	echo '</tr></table>';
}

function list_all_salary($link,$staff_id)
{

			echo '<table ><tr><td><h2>All Salary Slips of</h2></td><td>';
			display_staff($link,$staff_id);
			echo '</td></tr></table>';
			
	$sql='select distinct bill_group from salary where staff_id=\''.$staff_id.'\' order by bill_group desc';
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link); return FALSE;}	
	$header='yes';
	echo '<table align=center class=border style="background-color:#ADD8E6">';
	while($bg=mysqli_fetch_assoc($result))
	{
		$ar=get_raw($link,'select * from bill_group where bill_group=\''.$bg['bill_group'].'\'');
		if($header=='yes')
		{
			echo '<tr><th>Action</th><th>Bill Group</th><th>Prepared on</th> <th>From</th> <th>To</th> <th>Head</th> 
			<th>Type</th><th>Remark</th></tr>
			<tr>';
			$header='no';
		}
			echo '<tr><td>';
			echo '<form  style="margin-bottom:0;" method=post>';
			echo '<input type=hidden name=staff_id value=\''.$staff_id.'\'>';
			echo '<input type=hidden name=bill_group value=\''.$ar['bill_group'].'\'>';
			echo '<input type=submit formtarget=_blank name=action value=View>';
			echo '</form></td>'.
			'<td>'.$ar['bill_group'].'</td>'.
			'<td>'.$ar['date_of_preparation'].'</td>'.
			'<td>'.$ar['from_date'].'</td>'.
			'<td>'.$ar['to_date'].'</td>'.
			'<td>'.$ar['head'].'</td>'.
			'<td>'.$ar['bill_type'].'</td>'.
			'<td>'.$ar['remark'].'</td></tr>';
			
	}
	echo '</table>';

}

function print_one_nonsalary_slip($link,$staff_id,$bill_group,$format_table='')
{
	if(strlen($format_table)==0){$format_table='nonsalary_type';}
	$sql='select * from `'.$format_table.'`';

	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	$ptbl='';
	$count=0;
	while($ar=mysqli_fetch_assoc($result))
	{
		$dt=get_raw($link,'select * from nonsalary where 
								staff_id=\''.$staff_id.'\' and 
								bill_group=\''.$bill_group.'\' and 
								nonsalary_type_id=\''.$ar['nonsalary_type_id'].'\'');
		$title=$ar['nonsalary_type_id'];							 
		if($count%3==0){$t='<tr>';}else{$t='';}
		if($count%3==2){$tt='</tr>';}else{$tt='';}
		
		$ptbl=$ptbl.$t.'<td>'.$ar['name'].'</td>
										<td>'.$dt['data'].'</td>'.$tt;
		$count=$count+1;
	}
	

	$tbl='<table  width="100%" align=center id=nonsal class=border style="background-color:lightgray;display=block;">'.$ptbl.'</table>';
			
	echo $tbl;
}

function print_one_salary_slip($link,$staff_id,$bill_group,$format_table='')
{
	if(strlen($format_table)==0){$format_table='salary_type';}
	$sql='select * from `'.$format_table.'`';

	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	$ptbl='';
	$mtbl='';
	while($ar=mysqli_fetch_assoc($result))
	{
		$dt=get_raw($link,'select * from salary where 
								staff_id=\''.$staff_id.'\' and 
								bill_group=\''.$bill_group.'\' and 
								salary_type_id=\''.$ar['salary_type_id'].'\'');
								
		if($ar['type']=='+'){$ptbl=$ptbl.'<tr>
										<td width="65%">'.$ar['name'].'</td>
										<td width="15%">'.$dt['amount'].'</td>
										<td width="20%">'.$dt['remark'].'</td></tr>';}
										
		elseif($ar['type']=='-'){$mtbl=$mtbl.'<tr>
										<td width="65%">'.$ar['name'].'</td>
										<td width="15%">'.$dt['amount'].'</td>
										<td width="20%">'.$dt['remark'].'</td></tr>';}	
	}
	
	$tbl='<table width="100%" align=center id=sal class=border style="display=block;background-color:#A0CA94">	
				<tr><th>Payment</th><th>Deductions</th></tr>
				<tr><td valign=top><table class=border width="100%">'.$ptbl.'</table>
				</td><td valign=top><table class=border width="100%">'.$mtbl.'</table></td></tr>

		</table>';
			
	echo $tbl;
	$pmn=find_sums($link,$staff_id,$bill_group);

	echo '<table align=center><tr><td align=center><div align=center id="response">';
	
	echo '<table width="100%" class=border align="center" style="display:block;background:lightpink;"><tr>';
	echo '<th>Gross</th><th>Deductions</th><th>Net</th></tr><tr>';
	echo '<th>'.$pmn[0].'</th><th>'.$pmn[1].'</th><th>'.$pmn[2].'</th>';
	echo '</tr></table>';
	
	echo '</div></td></tr></table>';
}


function find_sums($link,$staff_id,$bill_group)
{
	$sql='select * from salary where 
								staff_id=\''.$staff_id.'\' and 
								bill_group=\''.$bill_group.'\'';
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	$p=0;
	$m=0;
	while($ar=mysqli_fetch_assoc($result))
	{	
		$dt=get_raw($link,'select * from salary_type where salary_type_id=\''.$ar['salary_type_id'].'\'');
		if($dt['type']=='+')
		{
			$p=$p+$ar['amount'];
		}
		elseif($dt['type']=='-')
		{
			$m=$m+$ar['amount'];
		}
	}	
	$n=$p-$m;	
	return array($p,$m,$n);				
}


function print_one_h_salary($link,$staff_id,$bill_group,$format_table='')
{
	if(strlen($format_table)==0){$format_table='salary_type';}
	$sql='select * from `'.$format_table.'`';

	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	$ptbl='';
	$mtbl='';
	
	
	while($ar=mysqli_fetch_assoc($result))
	{
		$dt=get_raw($link,'select * from salary where 
								staff_id=\''.$staff_id.'\' and 
								bill_group=\''.$bill_group.'\' and 
								salary_type_id=\''.$ar['salary_type_id'].'\'');
								
		if($ar['type']=='+'){$ptbl=$ptbl.'<td>'.$dt['amount'].'</td>';}
										
		elseif($ar['type']=='-'){$mtbl=$mtbl.'<td>'.$dt['amount'].'</td>';}	
	}
	
	$tbl=$ptbl.$mtbl;
			
	
	
	$pmn=find_sums($link,$staff_id,$bill_group);

	
	
	$summary_column='<th>'.$pmn[0].'</th><th>'.$pmn[1].'</th><th>'.$pmn[2].'</th>';
		
	echo $summary_column.$tbl;
}



function export_one_h_salary($link,$staff_id,$bill_group,$format_table='')
{
	if(strlen($format_table)==0){$format_table='salary_type';}
	$sql='select * from `'.$format_table.'`';

	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	$ptbl='';
	$mtbl='';
	
	
	while($ar=mysqli_fetch_assoc($result))
	{
		$dt=get_raw($link,'select * from salary where 
								staff_id=\''.$staff_id.'\' and 
								bill_group=\''.$bill_group.'\' and 
								salary_type_id=\''.$ar['salary_type_id'].'\'');
								
		if($ar['type']=='+'){$ptbl=$ptbl.'"'.$dt['amount'].'",';}
										
		elseif($ar['type']=='-'){$mtbl=$mtbl.'"'.$dt['amount'].'",';}	
	}
	
	$tbl=$ptbl.$mtbl;
			
	
	
	$pmn=find_sums($link,$staff_id,$bill_group);

	
	
	$summary_column='"'.$pmn[0].'","'.$pmn[1].'","'.$pmn[2].'",';
		
	return $summary_column.$tbl;
}

function export_one_h_salary_header($link,$format_table='')
{
	if(strlen($format_table)==0){$format_table='salary_type';}
	$sql='select * from `'.$format_table.'`';

	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	$ptbl='';
	$mtbl='';
	
	
	while($ar=mysqli_fetch_assoc($result))
	{				
		if($ar['type']=='+'){$ptbl=$ptbl.'"(+)'.$ar['name'].'",';}
										
		elseif($ar['type']=='-'){$mtbl=$mtbl.'"(-)'.$ar['name'].'",';}	
	}
	
	$tbl='"gross","deduction","net",'.$ptbl.$mtbl;
			
	return $tbl;
}

function list_annual_salary($link,$staff_id)
{
			echo '<table ><tr><td><h2>Annual Salary of</h2></td><td>';
			display_staff($link,$_POST['staff_id']);
			echo '</td></tr></table>';
			
	$sql='select distinct bill_group from salary where staff_id=\''.$staff_id.'\' order by bill_group desc';
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link); return FALSE;}	
	echo '<table align=center class=border style="background-color:#ADD8E6">';
	echo '<tr><th>Bill Group</th><th>Type</th><th>Remark</th><th>Gross</th><th>Deduction</th><th>Net</th>';
	print_one_h_salary_header($link,'');
	echo '</tr>';
			
	while($bg=mysqli_fetch_assoc($result))
	{
//		if(substr($bg['bill_group'],2,2)==$year)
//		{
		$ar=get_raw($link,'select * from bill_group where bill_group=\''.$bg['bill_group'].'\'');
		
			echo '<tr>
			<td>'.$ar['bill_group'].'</td>
			<td>'.$ar['bill_type'].'</td>
			<td>'.$ar['remark'].'</td>';
			print_one_h_salary($link,$staff_id,$bg['bill_group'],'');
			echo '</tr>';
//		}
	}
	echo '</table>';

}


function export_annual_salary($link,$staff_id)
{
			//echo '<table ><tr><td><h2>Annual Salary of</h2></td><td>';
			//display_staff($link,$_POST['staff_id']);
			//echo '</td></tr></table>';
			
	$sql='select distinct bill_group from salary where staff_id=\''.$staff_id.'\' order by bill_group desc';
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link); return FALSE;}	
	//echo '<table align=center class=border style="background-color:#ADD8E6">';
	//echo '<tr><th>Bill Group</th><th>Type</th><th>Remark</th><th>Gross</th><th>Deduction</th><th>Net</th>';
	//print_one_h_salary_header($link,'');
	//echo '</tr>';

			$fp = fopen('php://output', 'w');
			if ($fp) 
			{
				header('Content-Type: text/csv');
				header('Content-Disposition: attachment; filename="export.csv"');
				$head='Billgroup,billtype,remark,'.export_one_h_salary_header($link).PHP_EOL;
				fputs($fp, $head);

			
				while($bg=mysqli_fetch_assoc($result))
				{
							$ar=get_raw($link,'select * from bill_group where bill_group=\''.$bg['bill_group'].'\'');
					
							$row='"'.$ar['bill_group'].'","'.$ar['bill_type'].'","'.$ar['remark'].'",';
							$row=$row.export_one_h_salary($link,$staff_id,$bg['bill_group']).PHP_EOL;
							fputs($fp, $row);
				}
			}
	//echo '</table>';

}

function export_to_csv($sql,$link)
{
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);}
	$fp = fopen('php://output', 'w');
	if ($fp && $result) 
	{
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="export.csv"');
		
		$first='yes';
		
		while ($row = mysqli_fetch_assoc($result))
		{
			if($first=='yes')
			{
				fputcsv($fp, array_keys($row));
				$first='no';
			}
			
			fputcsv($fp, array_values($row));
		}
	}	
	
}

?>

