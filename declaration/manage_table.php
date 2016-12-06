<?php
session_start();//not required because called from another php as include
require_once '../common/common.php';
$link=connect();

echo '<script type="text/javascript" src="../date/datepicker.js"></script>';
echo '<script src="../js/jquery-3.1.1.min.js"></script>';
echo '<link rel="stylesheet" type="text/css" href="../date/datepicker.css" /> ';

function add_raw()
{
	
	$raw_html='<tr style="background-color:lightblue;">
			<td>
			<select name=qualification_degree id=qualification_degree>
				<option>MBBS</option>
				<option>MD</option>
				<option>MS</option>
				<option>DM</option>
				<option>MCh</option>
				<option>Diploma</option>
				<option>DNB</option>
			</select>
			<select name=qualification_subject id=qualification_subject>
				<option>Anatomy</option>
				<option>Physiology</option>
				<option>Biochemistry</option>
				<option>Pathology</option>
				<option>Pharmacology</option>
				<option>Microbiology</option>
				<option>Forensic Medicine</option>
				<option>Community Medicine</option>
				<option>Medicine</option>
				<option>General Surgery</option>
				<option>Paediatrics</option>
				<option>Obstetrics and Gynacology</option>
				<option>Opthalmology</option>
				<option>Orthopaedics</option>
				<option>Psychiatry</option>
				<option>Otorhinolaryngology</option>
				<option>Dentistry</option>
				<option>Respiratory Medicine</option>
				<option>IHBT</option>
				<option>Radiology</option>
				<option>Dematology</option>
				<option>Plastic Surgery</option>
				<option>Emergency Medicine</option>
				<option>Anesthesiology</option>
			</select>
				<input  class=upload type=file name=file_qualification id=file_qualification>
			</td>
			<td  ><input  type=text name=college_qualification id=college_qualification></td>
			<td  ><input  type=text name=university_qualification id=university_qualification></td>
			<td  ><input  type=text name=year_qualification id=year_qualification></td>
			<td >
				<table class=noborder><tr><td>
				<input placeholder="Reg. No" type=text name=reg_no_qualification id=reg_no_qualification>
				</td></tr><tr><td>
				<input placeholder="Reg. Dt" readonly name=reg_date_qualification id=reg_date_qualification class="datepicker" >
				</td></tr><tr><td>
				<input  type=file class=upload name=file_reg_qualification id=file_reg_qualification>
				</td></tr></table>
			</td>
			<td  ><input type=text name=council_qualification id=council_qualification></td>
			</tr>
			';
			
			echo '<form method=post><table>';
			echo $raw_html;
			echo '<input type=submit name=submit value=add_qualification>
					<input type=submit name=submit value=done>';
			echo '</table></form>';
}

function save_raw()
{
	
	
}

function view_table($link)
{
	$sql='select * from qualification where staff_id=\''.$_SESSION['login'].'\'';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	echo '<form method=post><table>';
	while($ra=mysqli_fetch_assoc($result))
	{		
	$raw_html='<tr style="background-color:lightgray;">
			<td><input type=submit name=delete value=X></td>
			<td>'.$ra['qualification'].'('.$ra['subject'].')
			</td>
			<td  >'.$ra['college'].'</td>
			<td  >'.$ra['university'].'</td>
			<td  >'.$ra['year'].'</td>
			<td >
				'.$ra['registration_number'].', date:'.$ra['registration_date'].'
			</td>
			<td  >'.$ra['medical_council'].'</td>
			</tr>
			';
			echo $raw_html;
		}
		echo '</table></form>';	
}

function delete_raw()
{
	
}


$link=connect();

add_raw();
view_table($link);
echo '<pre>';
print_r($_POST);
echo '</pre>';


?>
