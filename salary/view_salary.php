<?php
session_start();
require_once '../common/common.php';
require_once('../tcpdf/tcpdf.php');
//require_once('Numbers/Words.php');
$link=connect();

//////////////////Code for salary//////////

$sal_link=mysqli_connect('127.0.0.1',$GLOBALS['main_user'],$GLOBALS['main_pass']);

mysqli_select_db($sal_link,'c34');

class ACCOUNT1 extends TCPDF {

	public function Header() 
	{
	}
	
	public function Footer() 
	{
	    $this->SetY(-15);
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}	
}
if(isset($_POST['action']))
{
	if($_POST['action']=='View')
	{
		ob_start();
		/*echo '  <link rel="stylesheet" href="../css/style.css">';
       echo '<table style="margin:0 auto;"><tr><td>';
		display_staff($sal_link,$_POST['staff_id']);
		echo '</td><td>';
		display_bill($sal_link,$_POST['bill_group']);
		echo '</td></tr></table>';
		print_one_nonsalary_slip($sal_link,$_POST['staff_id'],$_POST['bill_group']);
		print_one_salary_slip($sal_link,$_POST['staff_id'],$_POST['bill_group']);*/

       print_one_complate_slip($sal_link,$_POST['staff_id'],$_POST['bill_group']);
		
	}
	
	if($_POST['action']=='export')
	{
		$sal_link=mysqli_connect('127.0.0.1',$GLOBALS['main_user'],$GLOBALS['main_pass']);
		mysqli_select_db($sal_link,'c34');
		//print_r($_POST);
		export_annual_salary($sal_link,$_POST['staff_id']);
		exit(0);
	}	
	$myStr = ob_get_contents();
	ob_end_clean();
   
 //echo $myStr;
 //exit(0);

	 $pdf = new ACCOUNT1('P', 'mm', 'A4', true, 'UTF-8', false);
	     $pdf->SetFont('dejavusans', '', 9);
	     $pdf->SetMargins(30, 20, 30);
	     $pdf->AddPage();
	     $pdf->writeHTML($myStr, true, false, true, false, '');
	     $pdf->Output($_POST['bill_group'].'_salary_slip_pdf2.pdf', 'I');
}

if(isset($_POST['action']))
{
	if($_POST['action']!='export')
	{
		menu($link);
	}
}
else
{
	menu($link);	
}

echo '<form method=post>
			<button	formtarget=_blank 
					class=submitbutton
					type=submit 
					name=action 
					value=export>Export Salary</button>
			<input type=hidden name=staff_id value=\''.$_SESSION['login'].'\'>
		</form>';

echo '<div style="margin:0 auto;background-color:#FFD4D4;">';
list_all_salary($sal_link,$_SESSION['login']);
echo '</div>';

function display_staff_pdf($link,$staff_id)
{
	$sql='select * from staff where staff_id=\''.$staff_id.'\'';

	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	echo '<table border="1">
	<tr><th><h4>ID</h4></th><th><h4>Name</h4></th></tr>
	<tr>';
	while($ar=mysqli_fetch_assoc($result))
	{
		echo '<td>'.$ar['staff_id'].'</td>'.
		'<td>'.$ar['fullname'].'</td>';
	}
	echo '</tr></table>';
}

function display_staff($link,$staff_id)
{
	$sql='select * from staff where staff_id=\''.$staff_id.'\'';

	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	echo '<table border="1">
	<tr><th><h4>ID</h4></th><th><h4>Name</h4></th></tr>
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
	echo '<table border="1" >';
	if($header=='yes')
	{
		echo '<tr><th>Bill Group</th><th>Prepared on</th><th>From</th><th>To</th><th>Head</th> 
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

			echo '<table border=1><tr><td><h2>All Salary Slips of</h2></td><td style="margin:0 auto;background-color:#F5D300">';
			display_staff($link,$staff_id);
			echo '</td></tr></table>';
			
	$sql='select distinct bill_group from salary where staff_id=\''.$staff_id.'\' order by bill_group desc';
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link); return FALSE;}	
	$header='yes';
	echo '<table align=center border=1 style="background-color:#ADD8E6">';
	while($bg=mysqli_fetch_assoc($result))
	{
		
		$ar=get_raw($link,'select * from bill_group where bill_group=\''.$bg['bill_group'].'\'');
		if($ar['locked']==1)
		{
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
			echo '<button type=submit class=submitbutton formtarget=_blank name=action value=View>View</button>';
			echo '</form></td>'.
			'<td>'.$ar['bill_group'].'</td>'.
			'<td>'.$ar['date_of_preparation'].'</td>'.
			'<td>'.$ar['from_date'].'</td>'.
			'<td>'.$ar['to_date'].'</td>'.
			'<td>'.$ar['head'].'</td>'.
			'<td>'.$ar['bill_type'].'</td>'.
			'<td>'.$ar['remark'].'</td></tr>';
		}
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
		
		$ptbl=$ptbl.$t.'<td width="13%">'.substr($ar['name'],0,7).'</td>
										<td width="20%">'.$dt['data'].'</td>'.$tt;
		$count=$count+1;
	}
		if($count%3!=0){$ptbl=$ptbl.'</tr>';}
	$tbl='<table  align="center" border="1" width="100%">'.$ptbl.'</table>';

	
			
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
										<td width="60%">'.substr($ar['name'],0,20).'</td>
										<td width="20%">'.$dt['amount'].'</td>
										<td width="20%">'.$dt['remark'].'</td></tr>';}
										
		elseif($ar['type']=='-'){$mtbl=$mtbl.'<tr>
										<td width="60%">'.substr($ar['name'],0,20).'</td>
										<td width="20%">'.$dt['amount'].'</td>
										<td width="20%">'.$dt['remark'].'</td></tr>';}	
	}
	
	$tbl='<table width="100%" border="1" align="center" id="sal">	
				<tr><th><h4>Payment</h4></th><th><h4>Deductions</h4></th></tr>
				<tr><td valign=top><table border="1" width="100%">'.$ptbl.'</table>
				</td><td valign=top><table border="1" width="100%">'.$mtbl.'</table></td></tr>

		</table>';
			
	echo $tbl;
	$pmn=find_sums($link,$staff_id,$bill_group);
    echo '<div style="margin:0 auto" >
          <br><br><br>
          <table width="100%">
           <tr>
           <td width="20%"></td>
           <td width="60%">
                <table  align="center" border="1"><tr>
	            <th><h4>Gross</h4></th><th><h4>Deductions</h4></th><th><h4>Net</h4></th></tr><tr>
	            <th>'.$pmn[0].'</th><th>'.$pmn[1].'</th><th>'.$pmn[2].'</th>
	           </tr></table>
           </td>
           <td width="20%"></td>
           </tr>
          </table></div>';
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

function print_one_h_salary_header($link,$format_table='')
{
	if(strlen($format_table)==0){$format_table='salary_type';}
	$sql='select * from `'.$format_table.'`';

	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	$ptbl='';
	$mtbl='';
	
	
	while($ar=mysqli_fetch_assoc($result))
	{				
		if($ar['type']=='+'){$ptbl=$ptbl.'<td>(+)'.$ar['name'].'</td>';}
										
		elseif($ar['type']=='-'){$mtbl=$mtbl.'<td>(-)'.$ar['name'].'</td>';}	
	}
	
	$tbl=$ptbl.$mtbl;
			
	echo $tbl;
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
	
	$tbl='"bill group","type","remark","Grand Total","Deductions","Net",'.$ptbl.$mtbl;
			
	return $tbl.PHP_EOL;
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
			
	$sql='select distinct bill_group from salary where staff_id=\''.$staff_id.'\' order by bill_group desc';
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link); return FALSE;}	

	$fp = fopen('php://output', 'w');
	if ($fp) 
	{
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="export.csv"');
	}
	$head=export_one_h_salary_header($link);
	fputs($fp, $head);
	
	while($bg=mysqli_fetch_assoc($result))
	{
		$ar=get_raw($link,'select * from bill_group where bill_group=\''.$bg['bill_group'].'\'');
		if($ar['locked']==1)
		{
			$row='"'.$ar['bill_group'].'","'.$ar['bill_type'].'","'.$ar['remark'].'",';
			$row=$row.export_one_h_salary($link,$staff_id,$bg['bill_group']).PHP_EOL;
			fputs($fp, $row);
		}
	}
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
function print_one_complate_slip($link,$staff_id,$bg)
{
			  $sql='select * from staff where staff_id=\''.$staff_id.'\'';

           $result_array= get_raw($link,$sql);
		    echo'<h3 align="center">'.$GLOBALS['college'].''.$GLOBALS['city'].'</h3>';
		    
		    echo'<h3 align="center">'.$result_array['fullname'].'</h3>';
		    echo '<br>
            <table width="100%">
            <tr>
            <td width="20%"></td>
            <td width="60%">
                  <table align="center" border="1">';
			      display_staff($link,$result_array['staff_id']);
		    echo '</table>
            </td>
            <td width="20%"></td>
            </tr>
            </table>';
			echo '<br><br>';
			echo '<table border="1" align="center">';
			 	   display_bill($link,$bg);
			echo '</table>';
	        echo '<br><br>';
			print_one_nonsalary_slip($link,$result_array['staff_id'],$bg);
            echo '<br><br>';
			print_one_salary_slip($link,$result_array['staff_id'],$bg);
		  
}

?>

