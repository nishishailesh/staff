<?php
session_start();

require_once('../tcpdf/tcpdf.php');
require_once('Numbers/Words.php');
require_once '../common/common.php';

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
$link=connect();

$save_msg='';
if(		isset($_POST['action']) || 
		isset($_POST['delete_qualification'])===true || 
		isset($_POST['delete_experience'])===true ||
		isset($_POST['delete_mci'])===true)
{
	if(save($link)===true )
	{
		$save_msg='Saved at --->'.strftime("%T").'';
	}
}

//even if following variables are false, thet are created and donot result in 'variable not found' error
//A variable set to FALSE is very useful, to prevent unnesseary errors
 
$staff_detail=get_raw($link,'select * from staff where id=\''.$_SESSION['login'].'\'');
$photo=get_raw($link,'select * from photo where id=\''.$_SESSION['login'].'\'');


//find current appointment
//find appointment order only if current appointment exist
$current_appointment=FALSE;
$current_appointment=get_raw($link,'select * from staff_movement where staff_id=\''.$_SESSION['login'].'\' and to_date is NULL');
//print_r($current_appointment);
$current_appointment_order=FALSE;
$current_joining_order=FALSE;
$current_joining_attached='No';
$previous_relieving_order=FALSE;

	$current_appointment=get_raw($link,'select * from staff_movement where staff_id=\''.$_SESSION['login'].'\' and to_date is NULL');
	
if($current_appointment)
{
	$current_appointment_order=get_raw($link,'select * from staff_movement_attachment 
			where movement_id=\''.$current_appointment['movement_id'].'\' and type=\'appointment_order\'');
	$current_joining_order=get_raw($link,'select * from staff_movement_attachment 
			where movement_id=\''.$current_appointment['movement_id'].'\' and type=\'joining_order\'');

	$previous_relieving_order=get_raw($link,'select * from staff_movement_attachment 
			where movement_id=\''.$current_appointment['movement_id'].'\' and type=\'relieving_order\'');

						
	if($current_joining_order!==FALSE){$current_joining_attached='Yes';}
}

//$last_mci=get_raw($link,'select * from last_mci where id=\''.$_SESSION['login'].'\' ');


$proof_of_residence_attached='No';
$r_proof=get_raw($link,'select * from residencial_address_proof where id=\''.$_SESSION['login'].'\' ');
if(!$r_proof){$proof_of_residence_attached='No';}else{$proof_of_residence_attached='Yes';}


$met=false;
$met=get_raw($link,'select * from met where id=\''.$_SESSION['login'].'\' ');

$degree_attachment_str='';
$reg_attachment_str='';


$last_mci_date=get_raw($link,'select max(`date`) md from mci where staff_id=\''.$_SESSION['login'].'\'');

$publication=get_raw($link,'select * from publication where staff_id=\''.$_SESSION['login'].'\'');


//if same date joining-relieving with FN,AN, this function will give wrong result
function find_staff_movement_details_at_specific_date($link,$dt)
{
	$sql='select * from staff_movement where staff_id=\''.$_SESSION['login'].'\'';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	
	while($ra=mysqli_fetch_assoc($result))
	{		
		if(strlen($ra['to_date'])==0)
		{
			$tdate=date("Y-m-d");
			//echo '<h1>'.$tdate.'</h1>';
		}
		else
		{
			$tdate=$ra['to_date'];
		}

		if(if_in_interval($dt,$ra['from_date'],$tdate)==0)
		{
			return $ra;
		}
	}
}

$last_mci_details=find_staff_movement_details_at_specific_date($link,$last_mci_date['md']);

function find_staff_movement_details_of_previous_institute($link,$current_institute_name)
{
	$sql='select * from staff_movement where staff_id=\''.$_SESSION['login'].'\' order by from_date desc';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	

	while($ra=mysqli_fetch_assoc($result))
	{		
		if($ra['institute']!=$current_institute_name)
		{
			return $ra;
		}
	}
}

$publication=get_raw($link, 'select * from publication where staff_id=\''.$_SESSION['login'].'\'');
$pan=get_raw($link,'select * from pan where staff_id=\''.$_SESSION['login'].'\' ');

$donot_hide='';
if(isset($_POST['action']))
{
	if($_POST['action']=='add_experience')
	{
		$donot_hide='experience';
	}
	
	if($_POST['action']=='add_qualification')
	{
		$donot_hide='qualification';
	}
}




    ob_start();
    echo'<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
	<div style="display:table;width:20cm;margin:1cm 1cm 1cm 1cm;">

<b>NAME OF THE COLLEGE:- ';
echo $current_appointment['institute'];
echo'</b>
<br>

<table border="1" width="90%">
	<tr>
		<td><b>Date of <br>Assessment</b></td>
		<td style="width:40mm;"></td>
		<td><b>Remarks</b></td>
	</tr>
	<tr>
		<td><b>Accepted?<br>(YES/NO)</b></td>
		<td></td>
		<td rowspan="3"></td>
	</tr>
	<tr>
		<td><b>Name of the <br>Assessor</b></td>
		<td></td>
	</tr>
	<tr>
		<td><b>Signature of <br>Assessor</b></td>
		<td></td>
	</tr>
</table>
<H3 align="center"><u>DECLARATION FORM : 2019 - 2020 - FACULTY</u></H3>
(Note: It is responsibility of Dean, HOD & Faculty to submit only the declaration form of faculty who has not
appeared for assessment in ny other college during the academic year and working fulltime)
<br><br>

<table>
  <tr>
     <td width="70%">
	   <b>1.(a)</b> Name: ';
	    echo $staff_detail['fullname']; 
        echo'<br><br><b>1.(b)</b> Date of Birth ';
        echo mysql_to_india_date($staff_detail['dob']); 
        echo'&amp; Age:';
         $diff=get_date_diff_as_ymd($staff_detail['dob'],strftime("%Y-%m-%d"));
	    echo $diff;
	    echo'<br><br><b>1.(c)</b> Submit Photo ID proof issued by Govt. Authorities :<br><br>';
	
		echo '<table><tr><td>';
		echo 'Photo ID submitted: '.$photo['proof_type'];
		echo '</td></tr>';
		echo '<tr><td>';
		echo 'Number:'.$photo['proof_number'];	
		echo ' </td></tr><tr><td>Issued by:'.$photo['proof_issued_by'];	
		echo '</td></tr>';
		echo '</table>
	
</td>
<td>';
	
			echo '<table>
			<tr>
			<td align=center>';
				//display_photo($link,$photo['photo']);
			echo '</td>
			</tr>';
			echo '</table>

</td>
</tr>
</table>
<br>
<b>Note:</b>
<br><b>1)</b> Without Photo ID, Declaration form will be rejected and will notbe considered as teaching   faculty. 2)</b>Original Certificates are mandatory for verification. All Certificates/Documents/Certified Translations, must be in English
<br><br><b>1.(d)i.</b>Present Designation:-';

	echo $current_appointment['post'];

echo'
<br><br><b>1.(d)(i)</b>a Certified copies of present appointment order at present institute attached.

<br><br><b>1.(d)ii.</b> Department:- ';
		echo $current_appointment['department'];
echo'
<br><br><b>1.(d)iii.</b>College:- ';
		echo $current_appointment['institute'];
echo'
<br><br><b>1.(d)iv.</b>City:-';
		$ex=explode(' ', $current_appointment['institute']);
		$city=$ex[count($ex)-1];
		echo $city;

echo'
<br><br><b>1.(d)v.</b>Nature of appointment:- ';
 if($current_appointment['type']=='Contract'){echo '(a)Contactual';}else{echo '(a)Regular';}
	echo '(b) Full time (c) Without private practice';

	
		if($last_mci_details['institute']==$current_appointment['institute'] && strlen($current_appointment['institute'])>0)
		{
			$last_mci_in_current_institute='Yes';
		}
		else
		{			
			$last_mci_in_current_institute='No';
		}

		if($last_mci_details['post']==$current_appointment['post'] && strlen($current_appointment['post'])>0)
		{
			$last_mci_as_current_post='Yes';
		}
		else
		{			
			$last_mci_as_current_post='No';
		}
		
		echo '<br><br><b>1.(d)vi.</b>Date of appearance in Last MCI – UG/PG/Any Other Assessment:-'.$last_mci_date['md'].'';
		echo '<br><br><b>1.(d)vii.</b>Whether appeared in Last MCI - UG/PG Assessment in the same Institute - <b>'.$last_mci_in_current_institute.'</b>';

		echo '<br><br><b>1.(d)viii.</b> Whether appeared in Last MCI - UG/PG Assessment on same Designation -<b>'.
		$last_mci_as_current_post.'</b>';
		
		echo '<br><br><b>1.(d)ix.</b> Whether you have retired from Government Medical College - Yes/No.<br><br><table ><tr><td></td><td>If Yes, Designation ____________________</td></tr></table> 
<br><br><table style="width:95%"><tr><td>________________</td><td align=right>________________</td></tr><tr><td>Signature of Faculty</td><td align=right>Signature of Dean with stamp</td></tr></table>
<p style="page-break-after:always;"></p>
<table >
	<br><tr>
		<td><b>1.(e)(a)</b> Present Residential  Address of employee :';
		
   echo ''.$staff_detail['present_residential_address'].'';		
	echo'</td>
	</tr>
	<br><tr>
		<td><b>1.(e)(b)</b> Permanent Residential  Address of employee :';
	
		echo ''.$staff_detail['permanent_residential_address'].'';
		echo'	
		</td>
	</tr>
</table>
<br><br><b>1.(f)</b> Have you undergone Training in "Basic Course Workshop" at MCI Regional Centre<br>in MET or in your college under Regional Centre observership';

if($met!==FALSE)
{
	echo ':Yes';	

	
	echo '<div width="110%" >
	<table border="1">';
}
else
{
	echo ':No';
 
}
	

if($met!==false)
{
	echo '<table>';
	echo'<tr>
			<th colspan="2" width="65%">Name of MCI Regional Centre where Training was done/If<br> training was done in college, give the details of the<br> observer from RC</th>
			<th colspan="2" width="30%">Date and place of training</th>
		</tr>
		<tr>
			<td >MET Center:</td><td>';
			
					echo $met['center'];
			
			echo'
			</td>
			<td >MET Place:</td><td>';
			
					echo $met['place'];
		echo'
			
			</td>

		</tr>
		<tr>
			<td >MET Observer:</td>
			<td>';
		
					echo $met['observer'];
			echo'
			</td>
			<td>Date</td>
			<td>';
			
					echo mysql_to_india_date($met['date']);
			echo'</td>

		</tr>

	</table>';
}

echo '
</div>
 <br>
	<table>
		<tr>
			<td colspan="2">
                 <b>1.(g)</b>
                  Copy of Passport /Voter Card / Electricity Bill /Landline Telephone Bill / Aadhar Card / attached as a proof of residence.:';
		       	   echo $proof_of_residence_attached.'</td>';
		echo'</tr>
	</table>
	 <br><br>
<table>
<tr><td><b>1.(h)</b>Contact Particulars:</td></tr>
</table>
<table>
 <tr>
  <td>Tel (Office)(with STD code):</td><td>';
			echo $staff_detail['office_phone'];
   echo'</td></tr>
         <tr><td>Tel(Residence): (with STD code)</td><td>';
			
					echo $staff_detail['residencial_phone'];
	echo'</td></tr>
         <tr><td>E-mail address: </td><td>';
			
					echo $staff_detail['email'];
	echo'</td></tr>
         <tr><td>Mobile Number: </td><td>';
			
					echo $staff_detail['mobile'];
	echo'</td></tr>
   </table>
    <br><br>	
   <table>
   <tr>
      <td>
  <b>1.(i)</b>Date of joining present institution :-
      </td>
   <td>';
       echo mysql_to_india_date($current_appointment['from_date']).' as '. $current_appointment['post'];
   echo'</td></tr>
     </table>
     <br><br>
    <table>
	<tr>
		<td><br><b>1.(j)</b>Joining report at the present institute attached:-';
		 echo $current_joining_attached;
	echo'</td>
	</tr>
</table>
<br><br>
<b>2.</b>
    Qualifications :
 <br>
 <table border="1">
  <tr>
	<th align="center">Qualification</th>
	<th align="center">College</th>
	<th align="center">University</th>
	<th align="center">Year</th>
	<th align="center">Registration No of UG & PG with date</th>
	<th align="center">Name of the State Medical Council</th>
  </tr>';
view_table_qualification($link);
echo'</table>
<br><br>
Note: For PG-Post PG qualification additional Registration certificate particulars be furnished and subject be indicated within brackets after scoring out whichever is not applicable.
<br><br>

<b>2.(a)</b>Copy of Degree certificates  of MBBS and PG degree attached - ';
 if(strlen($degree_attachment_str)>0){echo 'Yes';}else{echo 'No';} 
echo'

<br><br>
<b>2.(b)</b>Copy of Registration of MBBS and PG degree attached -  ';
if(strlen($reg_attachment_str)>0){echo 'Yes';}else{echo 'No';} 
echo'
<br><br>
<b>3.(a)</b> Details of the teaching experience till date.                                                              
	<br>
	<table border="1">';
$ar=get_experience_mci($link,$_SESSION['login']);
print_experience_delcaration($ar);
echo'
</table>
<br><br>
Note:-Tutor/Resident working in Anesthesia and Radio-diagnosis must have 3 years teaching experience in the respective departments in a recognized /permitted medical institute to be consider as senior resident.
<br><br>
<p style="page-break-after:always;"></p>
<b>3.(b)</b>To be filled in by Ex Army Personnel only
<br><br>
	<table border="1" style="width:100%;">
		<tr>
			<th rowspan="2" width="10%" align="center">S.No.</th>
			<th  rowspan="2" width="30%" align="center" >Designation</th>
			<th  rowspan="2" width="25%" align="center">Institution</th>
			<th colspan="2" width="35%" align="center" >Period</th>
		</tr>
		<tr>
			<th align="center">From</th>
			<th align="center">To</th>
		</tr>
		<tr>
			<td>1.</td>
			<td>Graded Specialist</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>2.</td>
			<td>Classified Specialist</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>	
	     <tr>
			<td>3.</td>
			<td>Adviser</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
</table>
<br><br>
Note:Have you been considered in any UG/PG inspection at any other institution/medical college during last 3 years.  <br>If yes, please give details.

Date of appearance:';
echo mysql_to_india_date($last_mci_date['md']);
echo'
<br><br>';
 $previous_institute_details=find_staff_movement_details_of_previous_institute($link,$current_appointment['institute']);
            echo'<b>4.</b>Before joining present institution I was working at ';
			echo $previous_institute_details['institute']; 
			echo ' as ';
			echo $previous_institute_details['post']; 
		    echo' and relieved on ';
		    echo mysql_to_india_date($previous_institute_details['to_date']); 
			echo' after resigning / retiring / transfering.
			<br> (Relieving order is enclosed from the previous institution)<br>';
echo '			
	<br><table>
	<tr>
		<td><b>5.</b> Number of  Research publications in Index Journals:</td>
	</tr>
	<tr>
		<td>
			<b>5.(a)</b>International Journals:';
			echo $publication['international'];
	echo'</td>
	</tr>
	<tr>
		<td>
			<b>5.(b)</b>National Journals:';
           echo $publication['national'];
	echo' </td>
	</tr>
	<tr>
		<td>
			<b>5.(c)</b>State/Institutional Journals:';
	     echo $publication['state'];
	 echo' </td>
   </tr>
</table>
<br><br>
<table>
	<br><tr><td><b>6.(a)</b>My PAN Card No. is :-';
          echo $pan['pan'];
    echo'
	</td></tr>
	<br><tr><td><b>6.(b)</b>My AADHAR Card No. is :-';
		echo $staff_detail['id'];
	echo'
	</td></tr>
</table>
<br><br>
<b>6.(c)</b>I have drawn total emoluments from this college in the current financial year as under:-
<br><br>
<table border="1" width="100%">	
	<tr align="center"><th width="20%" align="center"><b>Month</b></th><th width="40%" align="center"><b>Amount Received</b></th><th width="40%" align="center"><b>TDS</b></th></tr>
	<tr align="center"><td>Apr 2018</td><td></td><td></td></tr>	
	<tr align="center"><td>May 2018</td><td></td><td></td></tr>	
	<tr align="center"><td>Jun 2018</td><td></td><td></td></tr>	
	<tr align="center"><td>Jul 2018</td><td></td><td></td></tr>	
	<tr align="center"><td>Aug 2018</td><td></td><td></td></tr>	
	<tr align="center"><td>Sep 2018</td><td></td><td></td></tr>	
	<tr align="center"><td>Oct 2018</td><td></td><td></td></tr>	
	<tr align="center"><td>Nov 2018</td><td></td><td></td></tr>	
	<tr align="center"><td>Dec 2018</td><td></td><td></td></tr>	
	<tr align="center"><td>Jan 2019</td><td></td><td></td></tr>	
	<tr align="center"><td>Feb 2019</td><td></td><td></td></tr>	
	<tr align="center"><td>Mar 2019</td><td></td><td></td></tr>	
	</table>
<br><br>
<table>
  <tr>
	  <td>
		 <b>6.(d)</b>(Copy of my PAN and Form 16 (TDS certificate) for financial year 2016-17 are attached)
	  </td>
  </tr>
</table>
<br>
<p style="page-break-after:always;"></p>
<H3 align="center">DECLARATION</H3>
<table>
<tr><td valign="top" style="width:5%;">1.</td><td style="width:95%;"> I, Dr. ';
echo $staff_detail['fullname']; echo'.am working as ';
echo $current_appointment['post'];echo' in the Department of ';
echo $current_appointment['department']; echo' at ';
echo $current_appointment['institute']; echo',';
	$ex=explode(' ', $current_appointment['institute']);
	$city=$ex[count($ex)-1]; 
	echo $city ;
echo' and do hereby give an undertaking that I am a full time teacher in ';
echo $current_appointment['department'];
echo', working from  9 A.M. to 5 P.M. daily at this Institute.</td></tr>
<tr><td  valign="top">2.</td><td>I have not presented myself to any other Institution as a faculty in the current academic year for the purpose of MCI assessment.</td></tr>
<tr><td  valign="top">3</td>
<td>
	<table>
	<tr><td>
		I am not having private practice anywhere.
	</td></tr>
	<tr><td>
	I am practicing at ________________ in the city of ________________ and my hours of practice are _____ to _____ .Further I state that I am not doing any Private Practice or not working in any other hospital during college hours.
	</td></tr>
	</table>
</td></tr>
<tr><td valign="top">4.</td><td>Complete details with regard to work experience has been provided; nothing has been concealed by me.</td></tr>
<tr><td valign="top">5.</td><td>I am not working in any other medical/dental college in the state or outside the state in any capacity Regular/Contractual/Adhoc, Fulltime/Part time/Honorary.</td></tr>
<tr><td valign="top">6.</td><td>It is declared that each statement and/or contents of this declaration and /or documents, certificates submitted along with the declaration form, by the undersigned are absolutely true, correct and authentic.  In the event of any statement made in this declaration subsequently turning out to be incorrect or false the undersigned has understood and accepted that such misdeclaration in respect to any content of this declaration shall also be treated as a gross misconduct thereby rendering the undersigned liable for necessary disciplinary action (including removal of his name from Indian Medical Register).</td></tr>
</table>

<br><br><br>
<table style="width:100%">
	<tr><td  style="width:20%"><br>Date:</td><td  style="width:20%">________________</td><td align="right" style="width:50%">SIGNATURE OF THE EMPLOYEE</td></tr>
	<tr><td ><br><br>Place:</td><td><br><br>________________</td><td align="right"><br><br>________________</td></tr>
</table>
<br>
<H3 align="center">ENDORSEMENT</H3>
<br>1. This endorsement is the certification that the undersigned has satisfied himself /herself about the correctness and veracity of each content of this declaration and endorses the above mentioned declaration as true and correct.I have verified the certificates / documents submitted by the candidate with the original certificates/documents as submitted by the teacher to the Institute and with the concerned Institute and have found themto be correct and authentic.

<br><br>2. I also confirm that Dr.';

echo $staff_detail['fullname'];

echo'
is not practicing or carrying out any other activity during college working hours i.e. from 9.00 AM to 5 PM , since he/she has joined the Institute.
<br><br>3.	In the event of this declaration turning out to be either incorrect or any part of this declaration subsequently turning out to be incorrect or false it is understood and accepted that the undersigned shall also be equally responsible besides the declarant himself/herself for any such misdeclaration or misstatement.                             
<br><br>
<table style="width:100%">
	<tr>
		<td  style="width:10%"  align="left">Date:</td>
		<td  style="width:20%">________________</td>
		<td   style="width:30%" align="center">Signed by HOD</td>
		<td  style="width:40%" align="right">Countersigned by the Director/Dean/Principal</td>
	</tr>
	<tr>
		<td align="left"><br><br>Place:</td>
		<td><br><br>________________</td>
		<td align="center"><br><br>________________</td>
		<td align="right"><br><br>________________</td>
	</tr>
</table>
<p style="page-break-after:always;"></p>
<H3 align="center">REMARKS</H3>
<table border="1" align="center">
<tr><td width="15%"><b>S.No</b></td><td width="70%"><b>Documents</b></td><td width="15%"><b>Submitted</b></td></tr>
<tr><td>1</td><td>Recent Passport size photo of the Employee Signed by Dean / Principal of the college.</td><td>Yes / No</td></tr>
<tr><td>2</td><td>Photo ID proof issued by Govt. Authorities : Passport / PAN Card / Voter ID / Aadhar Card</td><td>Yes / No</td></tr>
<tr><td>3</td><td>Certified copies of present appointment order at present Institute.</td><td>Yes / No</td></tr>
<tr><td>4</td><td>Copy of Passport /Voter Card / Electricity Bill / Telephone Bill / Aadhar Card attached as a proof of present residence. </td><td>Yes / No</td></tr>
<tr><td>4(a)</td><td>Copy of Passport /Voter Card / Electricity Bill / Telephone Bill / Aadhar Card attached as a proof of permanent residence. </td><td>Yes / No</td></tr>
<tr><td>5</td><td>Joining report at the present institute.</td><td>Yes / No</td></tr>
<tr><td>6</td><td>Copies of Degree certificates of MBBS and PG degree.</td><td>Yes / No</td></tr>
<tr><td>7</td><td>Copies of Registration of MBBS and PG degree.</td><td>Yes / No</td></tr>
<tr><td>8</td><td>Copy of experience certificate for all teaching appointments held before joining present institute.</td><td>Yes / No</td></tr>
<tr><td>9</td><td>Relieving order from the previous institution.</td><td>Yes / No</td></tr>
<tr><td>10</td><td>PAN Card</td><td>Yes / No</td></tr>
<tr><td>11</td><td>Form 16 (TDS certificate) for the last financial year. </td><td>Yes / No</td></tr>
<tr><td>12</td><td>Letter head (in case of teachers who are practicing)</td><td>Yes / No</td></tr>
<tr><td>13</td><td>Copy of UG recognized teacher, letter from affiliated university</td><td>Yes / No</td></tr>
<tr><td>14</td><td>Copy of PG recognized teacher, letter from affiliated university (for PG Assessment)</td><td>Yes / No</td></tr>
<tr><td>15</td><td>Copy of AADHAR card</td><td>Yes / No</td></tr>
</table>
<br><br><br>
<table  style="width:100%;">
<tr><td><br><b>Signed by the Teacher</b></td><td><br><b>Signed by the HOD</b></td></tr>
<tr><td><b>Date</b></td><td><b>Date</b></td></tr>
</table>
<br><br><br>
<table>
<tr><th><br><b>Countersigned by Dean / Principal:</b></th></tr>
<tr><td><b>Date</b></td></tr>
</table>
<br><br><br>
<table>
<tr><th><br><b>Signed & Verified by the Assessor:</b></th></tr>
<tr><td><b>Date</b></td></tr>
</table>
<H3 align="center">Note:</H3>
<br><b>1.</b> The Declaration Form will not be accepted and the person will not be counted as teacher if any of the above documents are not enclosed /attached with the Declaration Form.<br>
<br><b>2.</b> The person will not be counted as a teacher if the original of Photo ID proof, Registration Certificates / Degree certificates / PAN Card /State Medical Council ID (if issued) are not produced for verification at the time of assessment.<br>
<br><b>3.</b> All the teachers must submit the revised declaration form in this format only. (Any declaration form submitted in an old format will not be accepted and he will not be counted as a teacher.)<br>
';
?>
<?php

function add_qualification_raw($link)
{

	echo '<tr style="background-color:lightblue;">
			<td></td><td>';
	mk_select_from_table($link,'qualification_degree','','');
	
	$sql_qs='select department from department';
	mk_select_from_sql($link,$sql_qs,'department','qualification_subject','','');
	
	echo '<input  class="upload" type="file" name="file_qualification_degree" ><br>^Upload qualification^';
	
	echo '	</td>
			<td><input  type="text" name="college_qualification"></td>
			<td><input  type="text" name="university_qualification"></td>
			<td>';
			read_year('year_qualification',date("Y")-100,date("Y"));
			echo '</td>
			<td>
				<table><tr><td>
				<input placeholder="Reg. No" type="text" name="reg_no_qualification" id="reg_no_qualification">
				</td></tr><tr><td>
				<input placeholder="Reg. Dt" readonly name=reg_date_qualification id=reg_date_qualification class="datepicker" >
				</td></tr><tr><td>';

			echo '<input  type=file class=upload name=file_qualification_reg ><br>^Upload Reg^';
			echo '</div>';				
					
			echo '</td></tr></table>
			</td>
			<td><input type="text" name="council_qualification" id="council_qualification"></td>
			</tr>
			';
	echo '<tr>
			<td colspan="7"><button type="submit" name="action" value="add_qualification" style="background-color:lightgreen;">Add Qualification</button></td>
			</tr>';
}

function find_qualification_attachment_name($link,$qualification_id,$type)
{
	$sql='select * from qualification_attachment where qualification_id=\''.$qualification_id.'\' and type=\''.$type.'\'';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	
	$ra=mysqli_fetch_assoc($result);
	return $ra['attachment_filename'];
}
function view_table_qualification($link)
{
	$sql='select * from qualification where staff_id=\''.$_SESSION['login'].'\' order by `year`';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	
	while($ra=mysqli_fetch_assoc($result))
	{		
	$raw_html='<tr align="center">
			<td>
			'.$ra['qualification'].'('.$ra['subject'].')
			</td>
			<td>'.$ra['college'].'</td>
			<td>'.$ra['university'].'</td>
			<td>'.$ra['year'].'</td>
			<td>
				'.$ra['registration_number'].', date:'.mysql_to_india_date($ra['registration_date']).'
			</td>
			<td>'.$ra['medical_council'].'</td>
			</tr>
			';
			echo $raw_html;
			$GLOBALS['degree_attachment_str'].=find_qualification_attachment_name($link,$ra['qualification_id'],'degree_certificate').',';
			$GLOBALS['reg_attachment_str'].=find_qualification_attachment_name($link,$ra['qualification_id'],'reg_certificate').',';
		}
}

function add_experience_raw($link)
{
	

	echo '	<tr><td></td><td>';
			mk_select_from_sql($link,'select designation_type from designation_type',
			'designation_type','experience_designation','','');

	echo '	</td><td>';
			mk_select_from_sql($link,'select appointment_type from appointment_type',
			'appointment_type','experience_type','','');
	echo '	</td><td>';
			mk_select_from_sql($link,'select department from department',
			'department','experience_department','','');
	echo '	</td>
			<td >';
			mk_select_from_sql_with_separate_id($link,'select institute from institute',
						'institute','experience_institute_select','experience_institute_select','','');
						
	echo 	'<table class="noborder" ><tr><td>
								<input size="30" placeholder="Write Institute Name Here" style="display:block;" 
								type="text" name="experience_institute_text" id="experience_institute_text">	
									</td></tr>
									<tr><td>Other Institutes:
								<input type=checkbox
								id="experience_institute_checkbox"  name="experience_institute_checkbox" title="Tick to enter name of other medical colleges"
								onclick="my_combo(this,\'experience_institute_text\',\'experience_institute_select\' )" >
							</td></tr>
							</table>
			</td><td>
				<table>
					<tr>
						<td>From:</td>
						<td><input readonly class="datepicker" name="from_experience" id="from_experience"></td>
						<td><select name="from_experience_time"><option selected>FN</option><option>AN</option></select></td>
					</tr>
					<tr>
						<td rowspan="2">To:</td>
						<td><div id="to_experience_date">
							<input  readonly class="datepicker" name="to_experience_pk" id="to_experience_date_pk">
							</div>
							<input readonly style="display:block;" id="to_experience_text" 
							name="to_experience_text" type="text" value="till_date" >
						</td>
						<td>
							<select name="to_experience_time"><option>FN</option><option selected>AN</option></select>
						</td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" name="to_experience_checkbox" id="to_experience_checkbox"
							onclick="my_combo(this,\'to_experience_text\',\'to_experience_date\' )";				
						>Till Date (Current)
						</td>
					</tr>
				</table>
			</td>
			</tr>
			';
	echo '<tr>
	<td colspan="7"><button type="submit" name="action" value="add_experience" style="background-color:lightgreen;"  >Add Experience</button></td>';
	echo '</tr>';
}


function view_table_mci($link)
{
	$sql='select * from mci where staff_id=\''.$_SESSION['login'].'\' order by `date`';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	echo '<table>';
	echo '<tr><th>Del</th><th>MCI appearance dates</th></tr>';
	while($ra=mysqli_fetch_assoc($result))
	{	
		echo '<tr>
				<td><button type=submit name=delete_mci value=\''.mysql_to_india_date($ra['date']).'\'>X</button></td>
				<td>'.mysql_to_india_date($ra['date']).'</td>
			</tr>';
	}
	echo '</table>';
}
function print_experience_delcaration($ar)
{

	echo'
	<table border="1"><tr>
	<td align="center">Designation</td>
	<td align="center">Department</td>
	<td align="center">Institute</td>
	<td align="center">From</td>
	<td align="center">To</td>
	<td align="center">Total</td>
	</tr>';
    foreach($ar as $v)
	{
		
		if(strlen($v['to_date'])==0)
		{
			$to_date='till date';
			$diff=get_date_diff_as_ymd($v['from_date'],date('Y-m-d'));
		}
		else
		{
			$to_date=mysql_to_india_date($v['to_date']);
			$diff=get_date_diff_as_ymd($v['from_date'],$v['to_date']);
		}
	
		echo '<tr align="center">';
		echo '<td>'.$v['post'].'</td>';
		echo '<td>'.$v['department'].'</td>';
		echo '<td>'.$v['institute'].'</td>';
		echo '<td>'.mysql_to_india_date($v['from_date']).'</td>';
		echo '<td>'.$to_date.'</td>';
		echo '<td>'.$diff.'</td>';
		echo '</tr>';
		
	}
	echo '</table>';
}

	$myStr = ob_get_contents();
	//echo $mystr;
	//exit(0);
	ob_end_clean();
   
   
//echo $myStr;
//exit(0);
     $pdf = new ACCOUNT1('P', 'mm', 'A4', true, 'UTF-8', false);
	     $pdf->SetFont('dejavusans', '', 9);
	     $pdf->SetMargins(30, 20, 30);
	     $pdf->AddPage();
	     $pdf->writeHTML($myStr, true, false, true, false, '');
	     $pdf->Output($_SESSION['login'].'_declaration.pdf', 'I');
?>

<div>
</div>

