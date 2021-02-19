<?php
session_start();
require_once '../common/common.php';
require_once('../tcpdf/tcpdf.php');
//require_once('Numbers/Words.php');
$link=connect();
//print_r($_POST);
//////////////////Code for salary//////////

$sal_link=mysqli_connect('127.0.0.1',$GLOBALS['main_user'],$GLOBALS['main_pass']);

mysqli_select_db($sal_link,'c34');
$staff_id=$_SESSION['login'];
//echo $staff_id;
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
		
		print_one_complate_slip($sal_link,$_POST['staff_id'],$_POST['bill_group']);
		
	   $myStr = ob_get_contents();
	   ob_end_clean();
   
      //echo $myStr;
     //exit(0);

	     $pdf = new ACCOUNT1('P', 'mm', 'A4', true, 'UTF-8', false);
	     $pdf->SetFont('dejavusans', '', 9);
	     $pdf->SetMargins(30, 20, 30);
	     $pdf->AddPage();
	     $pdf->writeHTML($myStr, true, false, true, false, '');
	     $pdf->Output($_POST['bill_group'].'_salary_slip_pdf3.pdf', 'I');
}

}
if(isset($_POST['action']))
{
	if($_POST['action']!='All_Export_Salary')
	{
		menu($link);
	echo '<table><tr><td><form method=post>
			<button	formtarget=_blank 
					class=submitbutton
					type=submit 
					name=action 
					value=All_Export_Salary>Export Salary</button>
			<input type=hidden name=staff_id value=\''.$_SESSION['login'].'\'>
			<input type=hidden name=fyear value=\''.$_POST['fyear'].'\'>
			<input type=hidden name=fmonth value=\''.$_POST['fmonth'].'\'>
			<input type=hidden name=tyear value=\''.$_POST['tyear'].'\'>
			<input type=hidden name=tmonth value=\''.$_POST['tmonth'].'\'>
		</form></td><td></td>';
		
	}
}
else
{
	menu($link);	
		echo '<br><form method=post >
		       <center>
				 <div class="container" >
					 <div class="row">
					 <div class="col-lg-6 mx-auto">
				  <table class="table table-borderless" border="1" >
				<th colspan=2 style="background-color:lightblue;text-align: center;"><h4>Choose Salary for Selected Months Wise.</h4></th>';
		echo'<tr><td><h4>From(YY)(MM)</h4></td><td><input type=number min=1 max=99 placeholder="YY" name=fyear value=\''.(date('y')-1).'\'> 	&nbsp;<input type=number min=1 placeholder="MM" max=99 value=3 name=fmonth></td></tr>';
		echo'<tr><td><h4>To(YY)(MM)</h4></td><td><input type=number min=1 max=99 placeholder="YY" name=tyear value=\''.date('y').'\'> 	&nbsp;<input type=number min=1 placeholder="MM" max=99 value=2 name=tmonth></td></tr>';
		echo	'<tr>';
		echo 		'<td></h4>Font size:</h4><input style="width: 50px;" name=fontsize type=number value=8></td><td colspan="2">';
		echo 			'<input type=submit  style="background-color:#FFD4D4;color:blue;" class="btn btn-success"  name=action value=view>
							</td>';
		echo 	'</tr>';
		echo 	'</table></div></div></div></center></form>';	
}


if(isset($_POST['action']))
{
	
	if($_POST['action']=='view')
	{
		$sal_link=mysqli_connect('127.0.0.1',$GLOBALS['main_user'],$GLOBALS['main_pass']);
		mysqli_select_db($sal_link,'c34');
		//print_r($_POST);
			
	  list_all_salary($sal_link,$staff_id,$_POST['fyear'],$_POST['fmonth'],$_POST['tyear'],$_POST['tmonth']);
	
	}
	
	if($_POST['action']=='All_Export_Salary')
	{
		
	    $sal_link=mysqli_connect('127.0.0.1',$GLOBALS['main_user'],$GLOBALS['main_pass']);
		mysqli_select_db($sal_link,'c34');
		//print_r($_POST);

		export_annual_salary_selected($sal_link,$staff_id,$_POST['fyear'],$_POST['fmonth'],$_POST['tyear'],$_POST['tmonth']);
	}	
	
	exit(0);
}
function export_annual_salary_selected($link,$staff_id,$fyear,$fmonth,$tyear,$tmonth)
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
		$from=$fyear*100+$fmonth;
		$to=$tyear*100+$tmonth;
		if(substr($bg['bill_group'],2,4)>=$from && substr($bg['bill_group'],2,4)<=$to)
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



function list_all_salary($link,$staff_id,$fyear,$fmonth,$tyear,$tmonth)
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
		
	 $from=$fyear*100+$fmonth;
	 $to=$tyear*100+$tmonth;
	 if(substr($bg['bill_group'],2,4)>=$from && substr($bg['bill_group'],2,4)<=$to)
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
	}
	echo '</table>';

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

?>
